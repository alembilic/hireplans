<?php

namespace App\Jobs;

use App\Mail\CampaignMail;
use App\Models\CampaignUser;
use App\Models\EmailCampaign;
use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Log;
use App\Services\ActivityService;
use Exception;

class SendCampaignJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * The number of times the job may be attempted.
     *
     * @var int
     */
    public $tries = 3;

    /**
     * The maximum number of seconds the job can run before timing out.
     *
     * @var int
     */
    public $timeout = 120;

    /**
     * Create a new job instance.
     */
    public function __construct(
        public EmailCampaign $campaign,
        public User $user
    ) {
        $this->onQueue('default');
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        try {
            // Send the email
            Mail::to($this->user->email)->send(new CampaignMail($this->campaign, $this->user));

            // Update the pivot table with success status
            CampaignUser::where('email_campaign_id', $this->campaign->id)
                ->where('user_id', $this->user->id)
                ->update([
                    'status' => 'sent',
                    'sent_at' => now(),
                    'error_message' => null,
                ]);

            // Log activity if user has a candidate profile
            if ($this->user->candidate) {
                ActivityService::campaignDelivered($this->user->candidate, $this->campaign);
            }

            Log::info('Campaign email sent successfully', [
                'campaign_id' => $this->campaign->id,
                'user_id' => $this->user->id,
                'user_email' => $this->user->email,
            ]);

        } catch (Exception $e) {
            // Update the pivot table with failure status
            CampaignUser::where('email_campaign_id', $this->campaign->id)
                ->where('user_id', $this->user->id)
                ->update([
                    'status' => 'failed',
                    'error_message' => $e->getMessage(),
                ]);

            // Log activity if user has a candidate profile
            if ($this->user->candidate) {
                ActivityService::campaignFailed($this->user->candidate, $this->campaign, $e->getMessage());
            }

            Log::error('Failed to send campaign email', [
                'campaign_id' => $this->campaign->id,
                'user_id' => $this->user->id,
                'user_email' => $this->user->email,
                'error' => $e->getMessage(),
            ]);

            // Re-throw the exception to allow job retry mechanism
            throw $e;
        }
    }

    /**
     * Handle a job failure.
     */
    public function failed(Exception $exception): void
    {
        // Update the pivot table with final failure status
        CampaignUser::where('email_campaign_id', $this->campaign->id)
            ->where('user_id', $this->user->id)
            ->update([
                'status' => 'failed',
                'error_message' => 'Job failed after ' . $this->tries . ' attempts: ' . $exception->getMessage(),
            ]);

        Log::error('Campaign email job failed permanently', [
            'campaign_id' => $this->campaign->id,
            'user_id' => $this->user->id,
            'user_email' => $this->user->email,
            'error' => $exception->getMessage(),
        ]);
    }
}
