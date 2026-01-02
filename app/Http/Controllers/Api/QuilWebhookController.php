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
        try {
            // Log the incoming webhook for debugging
            Log::info('Quil webhook received', ['payload' => $request->all()]);

            // Validate the webhook payload
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

            // Check if we already processed this webhook (idempotency)
            $existingMeeting = QuilMeeting::where('event_id', $validated['id'])->first();
            if ($existingMeeting) {
                Log::info('Webhook already processed', ['event_id' => $validated['id']]);
                return response()->json(['message' => 'Webhook already processed'], 200);
            }

            // Extract data from webhook
            $meetingData = $validated['data']['meeting'];
            $organizationData = $validated['data']['organization'] ?? [];
            $assetsData = $validated['data']['assets'] ?? [];

            // Try to match user by phone number
            $matchedUser = null;
            $matchedCandidate = null;
            $processingStatus = 'unmatched';
            $processingNotes = [];

            if (!empty($meetingData['participants'])) {
                foreach ($meetingData['participants'] as $phoneNumber) {
                    // Clean phone number for matching
                    $cleanPhone = preg_replace('/[^0-9+]/', '', $phoneNumber);
                    
                    // Try to find user by phone
                    $user = User::where('phone', 'LIKE', '%' . substr($cleanPhone, -10) . '%')->first();
                    
                    if ($user) {
                        $matchedUser = $user;
                        $matchedCandidate = $user->candidate;
                        $processingStatus = 'matched';
                        $processingNotes[] = "Matched to user: {$user->name} (ID: {$user->id}) via phone: {$phoneNumber}";
                        break; // Use first match
                    }
                }
            }

            if (!$matchedUser) {
                $processingNotes[] = 'No user matched by phone number: ' . implode(', ', $meetingData['participants'] ?? []);
            }

            // Create the Quil meeting record
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

            // Create activity log if matched to a candidate
            if ($matchedCandidate) {
                $this->activityService->log(
                    candidateId: $matchedCandidate->id,
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
            }

            Log::info('Quil meeting processed successfully', [
                'quil_meeting_id' => $quilMeeting->id,
                'processing_status' => $processingStatus,
            ]);

            // Return 200 OK immediately to acknowledge receipt
            return response()->json([
                'message' => 'Webhook processed successfully',
                'quil_meeting_id' => $quilMeeting->id,
                'processing_status' => $processingStatus,
            ], 200);

        } catch (\Illuminate\Validation\ValidationException $e) {
            Log::error('Quil webhook validation failed', [
                'errors' => $e->errors(),
                'payload' => $request->all(),
            ]);
            return response()->json([
                'message' => 'Invalid webhook payload',
                'errors' => $e->errors(),
            ], 422);
        } catch (\Exception $e) {
            Log::error('Quil webhook processing failed', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
                'payload' => $request->all(),
            ]);
            
            // Still return 200 to prevent retries for unrecoverable errors
            return response()->json([
                'message' => 'Webhook received but processing failed',
                'error' => $e->getMessage(),
            ], 200);
        }
    }
}
