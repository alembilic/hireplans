<?php

namespace App\Orchid\Screens;

use App\Models\EmailCampaign;
use Orchid\Screen\Screen;
use Orchid\Screen\Actions\Button;
use Orchid\Screen\Actions\Link;
use Orchid\Support\Facades\Layout;
use Orchid\Screen\TD;

class OutreachViewScreen extends Screen
{
    /**
     * @var EmailCampaign
     */
    public $campaign;

    /**
     * Fetch data to be displayed on the screen.
     *
     * @return array
     */
    public function query(EmailCampaign $campaign): iterable
    {
        $this->campaign = $campaign;

        return [
            'campaign' => $campaign->load(['users', 'creator']),
        ];
    }

    /**
     * The name of the screen displayed in the header.
     *
     * @return string|null
     */
    public function name(): ?string
    {
        return 'Campaign: ' . $this->campaign->name;
    }

    /**
     * The screen's action buttons.
     *
     * @return \Orchid\Screen\Action[]
     */
    public function commandBar(): iterable
    {
        return [
            Link::make('Edit Campaign')
                ->icon('bs.pencil')
                ->route('platform.outreach.edit', $this->campaign)
                ->class('btn btn-outline-primary')
                ->canSee($this->campaign->status === 'draft'),

            Button::make('Send Campaign')
                ->icon('bs.send')
                ->method('sendCampaign')
                ->class('btn btn-primary')
                ->canSee($this->campaign->status === 'draft'),

            Link::make('Back to List')
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
            Layout::tabs([
                'Overview' => Layout::rows([
                    Layout::view('orchid.outreach.campaign-overview', [
                        'campaign' => $this->campaign,
                    ]),
                ]),
                'Users' => Layout::table('campaign.users', [
                    TD::make('name', 'Name')
                        ->sort()
                        ->filter(),
                    TD::make('email', 'Email')
                        ->sort()
                        ->filter(),
                    TD::make('status', 'Status')
                        ->render(fn ($user) => $this->getUserStatus($user)),
                ]),
            ]),
        ];
    }

    /**
     * Send campaign.
     */
    public function sendCampaign(): void
    {
        $this->campaign->update([
            'status' => 'sent',
            'sent_at' => now(),
        ]);

        // TODO: Implement actual email sending logic here
        // This would typically involve a job queue and email service

        session()->flash('message', 'Campaign sent successfully!');
    }

    /**
     * Get user status for this campaign.
     */
    private function getUserStatus($user): string
    {
        $pivot = $this->campaign->campaignUsers->where('user_id', $user->id)->first();
        
        if (!$pivot) {
            return '<span class="badge bg-secondary">Pending</span>';
        }

        $statuses = [
            'pending' => '<span class="badge bg-warning text-dark">Pending</span>',
            'sent' => '<span class="badge bg-success">Sent</span>',
            'failed' => '<span class="badge bg-danger">Failed</span>',
        ];

        return $statuses[$pivot->status] ?? '<span class="badge bg-secondary">' . ucfirst($pivot->status) . '</span>';
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