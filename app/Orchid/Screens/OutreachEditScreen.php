<?php

namespace App\Orchid\Screens;

use App\Models\EmailCampaign;
use App\Models\User;
use Orchid\Screen\Screen;
use Orchid\Screen\Actions\Button;
use Orchid\Screen\Fields\Input;
use Orchid\Screen\Fields\TextArea;
use Orchid\Screen\Fields\Quill;
use Orchid\Screen\Fields\Relation;
use Orchid\Screen\Components\Cells\Boolean;
use Orchid\Support\Facades\Layout;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Orchid\Screen\Repository;
use App\Jobs\SendCampaignJob;
use App\Models\CampaignUser;
use App\Services\ActivityService;

class OutreachEditScreen extends Screen
{
    /**
     * @var EmailCampaign|null
     */
    public $campaign;
    public $is_edit = false;

    /**
     * Fetch data to be displayed on the screen.
     *
     * @return array
     */
    public function query(EmailCampaign $campaign = null): iterable
    {
        // Only set $this->campaign if we have an actual model instance
        $this->campaign = $campaign;
        $this->is_edit = request()->route()->getName() === 'platform.outreach.edit' ? true : false;
        
        if ($this->is_edit) {
            // Load existing campaign with users
            $campaignData = $campaign->toArray();
            $campaignData['users'] = $campaign->users()->pluck('users.id')->toArray();
            return [
                'campaign' => $campaignData,
            ];
        } else {
            // New campaign
            return [
                'campaign' => [
                    'name' => '',
                    'title' => '',
                    'email_content' => '',
                    'users' => [],
                ],
            ];
        }
    }

    /**
     * The name of the screen displayed in the header.
     *
     * @return string|null
     */
    public function name(): ?string
    {
        return $this->is_edit ? 'Edit Campaign' : 'Create Campaign';
    }

    /**
     * The screen's action buttons.
     *
     * @return \Orchid\Screen\Action[]
     */
    public function commandBar(): iterable
    {
        return [
            Button::make('Save as Draft')
                ->icon('bs.download')
                ->method('saveAsDraft')
                ->class('btn btn-link'),

            Button::make('Send Campaign')
                ->icon('bs.send')
                ->method('sendCampaign')
                ->class('btn btn-primary'),

            Button::make('Back to List')
                ->icon('bs.arrow-left')
                ->route('platform.outreach')
                ->class('btn btn-secondary'),
        ];
    }

    /**
     * The screen's layout elements.
     *
     * @return \Orchid\Screen\Layout[]|string[]
     */
    public function layout(): iterable
    {
        return [
            Layout::rows([
                Input::make('campaign.name')
                    ->title('Campaign Name')
                    ->placeholder('Enter campaign name')
                    ->required()
                    ->help('A descriptive name for your campaign'),

                Input::make('campaign.title')
                    ->title('Email Subject')
                    ->placeholder('Enter email subject line (supports variables like {{name}})')
                    ->required()
                    ->help('This will appear as the email subject. You can use variables like {{name}} or {{first_name}} here too.'),

                Quill::make('campaign.email_content')
                    ->title('Email Content')
                    ->placeholder('Write your email content here...')
                    ->required()
                    ->help('Use the rich text editor to format your email content with styling, links, and more.<br><br>
                        <strong>Available Variables:</strong><br>
                        <span class="small text-muted">
                            • <code>{{name}}</code> - Full name<br>
                            • <code>{{first_name}}</code> - First name only<br>
                            • <code>{{email}}</code> - Email address<br>
                            • <code>{{phone}}</code> - Phone number<br>
                            • <code>{{city}}</code> - City<br>
                            • <code>{{country}}</code> - Country<br>
                            • <code>{{nationality}}</code> - Nationality<br>
                            • <code>{{current_company}}</code> - Current company (if candidate profile exists)<br>
                            • <code>{{job_title}}</code> - Current job title (if candidate profile exists)<br>
                            • <code>{{candidate_ref}}</code> - Candidate reference (if candidate profile exists)
                        </span>'),

                Relation::make('campaign.users')
                    ->fromModel(User::class, 'name')
                    ->multiple()
                    ->title('Choose users to receive this email')
                    ->help('Select the users who will receive this email campaign'),
            ]),
        ];
    }

    /**
     * Save campaign as draft.
     */
    public function saveAsDraft(Request $request): void
    {
        $request->validate([
            'campaign.name' => 'required|min:3|max:255',
            'campaign.title' => 'required|min:3|max:255',
            'campaign.email_content' => 'required|min:10',
            'campaign.users' => 'required|array|min:1',
        ]);

        $data = $request->get('campaign');

        if ($this->is_edit) {
            // Update existing campaign
            $this->campaign->update([
                'name' => $data['name'],
                'title' => $data['title'],
                'email_content' => $data['email_content'],
                'status' => 'draft',
            ]);
        } else {
            // Create new campaign
            $this->campaign = EmailCampaign::create([
                'name' => $data['name'],
                'title' => $data['title'],
                'email_content' => $data['email_content'],
                'status' => 'draft',
                'created_by' => Auth::id(),
            ]);
        }

        // Sync selected users to the campaign
        $this->campaign->users()->sync($data['users']);

        session()->flash('message', 'Campaign saved as draft successfully!');
    }

    /**
     * Send campaign.
     */
    public function sendCampaign(Request $request): void
    {
        $request->validate([
            'campaign.name' => 'required|min:3|max:255',
            'campaign.title' => 'required|min:3|max:255',
            'campaign.email_content' => 'required|min:10',
            'campaign.users' => 'required|array|min:1',
        ]);

        $data = $request->get('campaign');
        
        if ($this->is_edit) {
            // Update existing campaign
            $this->campaign->update([
                'name' => $data['name'],
                'title' => $data['title'],
                'email_content' => $data['email_content'],
                'status' => 'sent',
                'sent_at' => now(),
            ]);
            
        } else {
            // Create new campaign
            $this->campaign = EmailCampaign::create([
                'name' => $data['name'],
                'title' => $data['title'],
                'email_content' => $data['email_content'],
                'status' => 'sent',
                'created_by' => Auth::id(),
                'sent_at' => now(),
            ]);
        }

        // Sync selected users to the campaign
        $this->campaign->users()->sync($data['users']);

        // Initialize all campaign users as pending
        foreach ($data['users'] as $userId) {
            CampaignUser::updateOrCreate(
                [
                    'email_campaign_id' => $this->campaign->id,
                    'user_id' => $userId,
                ],
                [
                    'status' => 'pending',
                    'sent_at' => null,
                    'error_message' => null,
                ]
            );
        }

        // Dispatch email sending jobs for each user and log activity
        foreach ($this->campaign->users as $user) {
            SendCampaignJob::dispatch($this->campaign, $user);
            
            // Log activity if user has a candidate profile
            if ($user->candidate) {
                ActivityService::campaignSent($user->candidate, $this->campaign, Auth::id());
            }
        }

        $userCount = count($data['users']);
        session()->flash('message', "Campaign queued for sending to {$userCount} users successfully!");
    }

    /**
     * Get the permissions required to access this screen.
     *
     * @return iterable|null The permissions required to access this screen.
     */
    public function permission(): ?iterable
    {
        return [
            'platform.systems.users',
        ];
    }
} 