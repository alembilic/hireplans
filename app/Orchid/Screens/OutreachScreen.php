<?php

namespace App\Orchid\Screens;

use App\Models\EmailCampaign;
use Orchid\Screen\Screen;
use Orchid\Support\Facades\Layout;
use Orchid\Screen\Actions\Link;
use Orchid\Screen\Fields\Input;
use Orchid\Screen\TD;
use Orchid\Screen\Actions\DropDown;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Orchid\Screen\Actions\Button;

class OutreachScreen extends Screen
{
    /**
     * Fetch data to be displayed on the screen.
     *
     * @return array
     */
    public function query(): iterable
    {
        $campaigns = EmailCampaign::where('created_by', Auth::id())
            ->orderBy('created_at', 'desc')
            ->get();

        return [
            'campaigns' => $campaigns,
        ];
    }

    /**
     * The name of the screen displayed in the header.
     *
     * @return string|null
     */
    public function name(): ?string
    {
        return 'Email Marketing Outreach';
    }

    /**
     * The screen's action buttons.
     *
     * @return \Orchid\Screen\Action[]
     */
    public function commandBar(): iterable
    {
        return [
            Link::make('Create Campaign')
                ->icon('bs.plus-circle')
                ->route('platform.outreach.create')
                ->class('btn btn-primary'),
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
            Layout::table('campaigns', [
                    TD::make('name', 'Campaign Name')
                        ->sort()
                        ->filter(Input::make())
                        ->render(fn (EmailCampaign $campaign) => 
                            Link::make($campaign->name)
                                ->route('platform.outreach.edit', $campaign)
                        ),
                    TD::make('title', 'Email Subject')
                        ->sort()
                        ->filter(Input::make()),
                    TD::make('status', 'Status')
                        ->sort()
                        ->render(fn (EmailCampaign $campaign) => 
                            $this->getStatusBadge($campaign->status)
                        ),
                    TD::make('user_count', 'Users')
                        ->sort()
                        ->render(fn (EmailCampaign $campaign) => 
                            '<span class="badge bg-secondary">' . $campaign->user_count . '</span>'
                        ),
                    TD::make('created_at', 'Created')
                        ->sort()
                        ->render(fn (EmailCampaign $campaign) => 
                            $campaign->created_at->format('M d, Y')
                        ),
                    TD::make(__('Actions'))
                        ->align(TD::ALIGN_CENTER)
                        ->width('100px')
                        ->render(fn (EmailCampaign $campaign) => DropDown::make()
                            ->icon('bs.three-dots-vertical')
                            ->list([
                                Link::make('Edit')
                                    ->route('platform.outreach.edit', $campaign)
                                    ->icon('bs.pencil'),
                                Button::make('Delete')
                                    ->icon('bs.trash')
                                    ->method('deleteCampaign', ['id' => $campaign->id])
                                    ->confirm('Are you sure you want to delete this campaign?'),
                            ])),
                ])
        ];
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



    /**
     * Delete campaign.
     */
    public function deleteCampaign(Request $request): void
    {
        $campaign = EmailCampaign::findOrFail($request->get('id'));
        $campaign->delete();
    }

    /**
     * Get status badge HTML.
     */
    private function getStatusBadge(string $status): string
    {
        $badges = [
            'draft' => '<span class="badge bg-warning text-dark">Draft</span>',
            'sent' => '<span class="badge bg-success">Sent</span>',
            'scheduled' => '<span class="badge bg-info">Scheduled</span>',
        ];

        return $badges[$status] ?? '<span class="badge bg-secondary">' . ucfirst($status) . '</span>';
    }
} 