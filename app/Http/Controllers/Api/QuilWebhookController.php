<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\QuilMeeting;
use App\Models\User;
use App\Models\Candidate;
use App\Models\Activity;
use App\Services\ActivityService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\DB;

class QuilWebhookController extends Controller
{
    protected $activityService;

    public function __construct(ActivityService $activityService)
    {
        $this->activityService = $activityService;
    }

    /**
     * Handle the meeting.completed webhook from Quil.
     */
    public function handleMeetingCompleted(Request $request)
    {
        $startTime = microtime(true);
        
        try {
            // Log the incoming webhook for debugging
            Log::channel('quil_webhooks')->info('Starting webhook processing', $request->all());

            // Validate the webhook payload
            Log::channel('quil_webhooks')->debug('Validating webhook payload');
            $validated = $request->validate([
                'id' => 'required|string',
                'eventType' => 'required|string',
                'createdAt' => 'required|integer',
                'data' => 'required|array',
                'data.meeting' => 'required|array',
                'data.meeting.id' => 'required|string',
                'data.meeting.name' => 'required|string',
                'data.meeting.startTime' => 'nullable|string',
                'data.meeting.ownerName' => 'nullable|string',
                'data.meeting.participants' => 'nullable|array',
                'data.meeting.atsRecordName' => 'nullable|string',
                'data.meeting.isPrivate' => 'nullable|boolean',
                'data.organization' => 'nullable|array',
                'data.assets' => 'nullable|array',
            ]);

            Log::channel('quil_webhooks')->info('Payload validated successfully');

            // Check if we already processed this webhook (idempotency)
            Log::channel('quil_webhooks')->debug('Checking for duplicate webhook', [
                'event_id' => $validated['id']
            ]);
            
            $existingMeeting = QuilMeeting::where('event_id', $validated['id'])->first();
            if ($existingMeeting) {
                Log::channel('quil_webhooks')->warning('Duplicate webhook detected', [
                    'event_id' => $validated['id'],
                    'existing_meeting_id' => $existingMeeting->id,
                    'created_at' => $existingMeeting->created_at
                ]);
                return response()->json(['message' => 'Webhook already processed'], 200);
            }

            Log::channel('quil_webhooks')->debug('No duplicate found, proceeding with processing');

            // Extract data from webhook
            $meetingData = $validated['data']['meeting'];
            $organizationData = $validated['data']['organization'] ?? [];
            $assetsData = $validated['data']['assets'] ?? [];
            
            // Fetch database notes from URL if provided
            $databaseNotesData = [];
            if (!empty($assetsData['databaseNotes']) && filter_var($assetsData['databaseNotes'], FILTER_VALIDATE_URL)) {
                try {
                    Log::channel('quil_webhooks')->debug('Fetching database notes from URL', [
                        'url' => $assetsData['databaseNotes']
                    ]);
                    
                    $response = \Illuminate\Support\Facades\Http::timeout(10)->get($assetsData['databaseNotes']);
                    
                    if ($response->successful()) {
                        $notesJson = $response->json();
                        // Extract the 'data' array from the response
                        $databaseNotesData = $notesJson['data'] ?? [];
                        
                        Log::channel('quil_webhooks')->info('Database notes fetched successfully', [
                            'notes_count' => count($databaseNotesData)
                        ]);
                    } else {
                        Log::channel('quil_webhooks')->warning('Failed to fetch database notes', [
                            'status_code' => $response->status(),
                            'url' => $assetsData['databaseNotes']
                        ]);
                    }
                } catch (\Exception $e) {
                    Log::channel('quil_webhooks')->error('Error fetching database notes', [
                        'error' => $e->getMessage(),
                        'url' => $assetsData['databaseNotes']
                    ]);
                }
            }
            
            // Store the fetched notes data (not the URL)
            $assetsData['databaseNotes'] = $databaseNotesData;

            // Try to match user by phone number
            Log::channel('quil_webhooks')->info('Starting phone number matching', [
                'participants' => $meetingData['participants'] ?? [],
                'participant_count' => count($meetingData['participants'] ?? [])
            ]);
            
            $matchedUser = null;
            $matchedCandidate = null;
            $processingStatus = 'unmatched';
            $processingNotes = [];

            if (!empty($meetingData['participants'])) {
                foreach ($meetingData['participants'] as $index => $phoneNumber) {
                    // Clean phone number for matching
                    $cleanPhone = preg_replace('/[^0-9+]/', '', $phoneNumber);
                    
                    Log::channel('quil_webhooks')->debug('Attempting phone match', [
                        'attempt' => $index + 1,
                        'original_phone' => $phoneNumber,
                        'cleaned_phone' => $cleanPhone,
                        'search_pattern' => substr($cleanPhone, -10)
                    ]);
                    
                    // Try to find user by phone
                    $user = User::where('phone', 'LIKE', '%' . substr($cleanPhone, -10) . '%')->first();
                    
                    if ($user) {
                        $matchedUser = $user;
                        $matchedCandidate = $user->candidate;
                        $processingStatus = 'matched';
                        $processingNotes[] = "Matched to user: {$user->name} (ID: {$user->id}) via phone: {$phoneNumber}";
                        
                        Log::channel('quil_webhooks')->info('Phone match found!', [
                            'user_id' => $user->id,
                            'user_name' => $user->name,
                            'candidate_id' => $matchedCandidate?->id,
                            'matched_phone' => $phoneNumber,
                            'attempt_number' => $index + 1
                        ]);
                        
                        break; // Use first match
                    } else {
                        Log::channel('quil_webhooks')->debug('No match for phone number', [
                            'phone' => $phoneNumber
                        ]);
                    }
                }
            }

            if (!$matchedUser) {
                $processingNotes[] = 'No user matched by phone number: ' . implode(', ', $meetingData['participants'] ?? []);
                Log::channel('quil_webhooks')->warning('No phone number match found', [
                    'participants' => $meetingData['participants'] ?? [],
                    'attempts_made' => count($meetingData['participants'] ?? [])
                ]);
            }

            // Create the Quil meeting record
            Log::channel('quil_webhooks')->info('Creating Quil meeting record', [
                'meeting_name' => $meetingData['name'],
                'processing_status' => $processingStatus,
                'matched_user_id' => $matchedUser?->id,
                'has_transcription' => !empty($assetsData['transcriptionUrl']),
                'has_recording' => !empty($assetsData['recordingUrl']),
                'has_notes' => !empty($assetsData['databaseNotes']),
            ]);
            
            $quilMeeting = QuilMeeting::create([
                'event_id' => $validated['id'],
                'event_type' => $validated['eventType'],
                'event_created_at' => $validated['createdAt'],
                'quil_meeting_id' => $meetingData['id'],
                'meeting_name' => $meetingData['name'],
                'start_time' => isset($meetingData['startTime']) ? \Carbon\Carbon::parse($meetingData['startTime']) : null,
                'owner_name' => $meetingData['ownerName'] ?? null,
                'participants' => $meetingData['participants'] ?? [],
                'ats_record_name' => $meetingData['atsRecordName'] ?? null,
                'is_private' => $meetingData['isPrivate'] ?? false,
                'account_id' => $organizationData['accountId'] ?? null,
                'team_id' => $organizationData['teamId'] ?? null,
                'transcription_url' => $assetsData['transcriptionUrl'] ?? null,
                'recording_url' => $assetsData['recordingUrl'] ?? null,
                'action_items_url' => $assetsData['actionItemsUrl'] ?? null,
                'database_notes' => $assetsData['databaseNotes'] ?? null,
                'follow_up_materials' => $assetsData['followUpMaterials'] ?? null,
                'user_id' => $matchedUser?->id,
                'candidate_id' => $matchedCandidate?->id,
                'processing_status' => $processingStatus,
                'processing_notes' => implode(' | ', $processingNotes),
            ]);

            Log::channel('quil_webhooks')->info('Quil meeting record created successfully', [
                'quil_meeting_id' => $quilMeeting->id,
                'database_id' => $quilMeeting->id
            ]);

            // Create activity log if matched to a candidate
            if ($matchedCandidate) {
                Log::channel('quil_webhooks')->info('Creating activity log entry', [
                    'candidate_id' => $matchedCandidate->id,
                    'quil_meeting_id' => $quilMeeting->id
                ]);
                
                try {
                    $this->activityService->log(
                        candidate: $matchedCandidate,
                        activityType: 'quil_meeting_completed',
                        title: 'AI Meeting Summary Available',
                        description: "Meeting '{$meetingData['name']}' has been completed and processed by Quil AI. Transcription, recording, and summary are now available.",
                        metadata: [
                            'quil_meeting_id' => $quilMeeting->id,
                            'meeting_name' => $meetingData['name'],
                            'participants' => $meetingData['participants'] ?? [],
                            'has_transcription' => !empty($assetsData['transcriptionUrl']),
                            'has_recording' => !empty($assetsData['recordingUrl']),
                            'summary' => $quilMeeting->getSummary(),
                        ],
                        createdBy: null // System generated
                    );
                    
                    Log::channel('quil_webhooks')->info('Activity log created successfully');
                } catch (\Exception $e) {
                    Log::channel('quil_webhooks')->error('Failed to create activity log', [
                        'error' => $e->getMessage(),
                        'candidate_id' => $matchedCandidate->id
                    ]);
                }
            } else {
                Log::channel('quil_webhooks')->info('Skipping activity log - no candidate matched');
            }

            $processingTime = round((microtime(true) - $startTime) * 1000, 2);
            
            Log::channel('quil_webhooks')->info('✓ Webhook processing completed successfully', [
                'quil_meeting_id' => $quilMeeting->id,
                'processing_status' => $processingStatus,
                'processing_time_ms' => $processingTime,
                'matched_user' => $matchedUser?->name,
                'assets_available' => [
                    'transcription' => !empty($assetsData['transcriptionUrl']),
                    'recording' => !empty($assetsData['recordingUrl']),
                    'action_items' => !empty($assetsData['actionItemsUrl']),
                    'notes_count' => is_array($assetsData['databaseNotes'] ?? null) ? count($assetsData['databaseNotes']) : 0,
                ]
            ]);

            // Return 200 OK immediately to acknowledge receipt
            return response()->json([
                'message' => 'Webhook processed successfully',
                'quil_meeting_id' => $quilMeeting->id,
                'processing_status' => $processingStatus,
            ], 200);

        } catch (\Illuminate\Validation\ValidationException $e) {
            Log::channel('quil_webhooks')->error('✗ Webhook validation failed', [
                'event_id' => $request->input('id'),
                'errors' => $e->errors(),
                'failed_fields' => array_keys($e->errors()),
            ]);
            
            // Log full payload in debug mode
            Log::channel('quil_webhooks')->debug('Invalid payload details', [
                'payload' => $request->all()
            ]);
            
            return response()->json([
                'message' => 'Invalid webhook payload',
                'errors' => $e->errors(),
            ], 422);
        } catch (\Exception $e) {
            $processingTime = round((microtime(true) - $startTime) * 1000, 2);
            
            Log::channel('quil_webhooks')->error('✗ Webhook processing failed', [
                'event_id' => $request->input('id'),
                'error_message' => $e->getMessage(),
                'error_file' => $e->getFile(),
                'error_line' => $e->getLine(),
                'processing_time_ms' => $processingTime,
            ]);
            
            // Log stack trace separately for debugging
            Log::channel('quil_webhooks')->debug('Error stack trace', [
                'trace' => $e->getTraceAsString()
            ]);
            
            // Still return 200 to prevent retries for unrecoverable errors
            return response()->json([
                'message' => 'Webhook received but processing failed',
                'error' => $e->getMessage(),
            ], 200);
        }
    }
}
