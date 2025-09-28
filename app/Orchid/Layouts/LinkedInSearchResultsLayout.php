<?php

namespace App\Orchid\Layouts;

use Orchid\Screen\Layouts\Table;
use Orchid\Screen\TD;
use Orchid\Screen\Actions\Button;
use Orchid\Screen\Actions\DropDown;
use Orchid\Screen\Actions\Link;

class LinkedInSearchResultsLayout extends Table
{
    /**
     * Data source.
     *
     * The name of the key to fetch it from the query.
     * The results of which will be elements of the table.
     *
     * @var string
     */
    protected $target = 'search_results';

    /**
     * Get the table cells to be displayed.
     *
     * @return TD[]
     */
    protected function columns(): iterable
    {
        return [
            TD::make('profile_image', 'Photo')
                ->render(function ($result) {
                    return "<img src='{$result['profile_image']}' alt='{$result['name']}' class='rounded-circle' style='width: 50px; height: 50px; object-fit: cover;'>";
                })
                ->width('80px'),

            TD::make('name', 'Name')
                ->render(function ($result) {
                    $relevanceColor = $result['relevance_score'] >= 90 ? 'success' : 
                                    ($result['relevance_score'] >= 80 ? 'warning' : 'secondary');
                    
                    return "
                        <div class='d-flex flex-column'>
                            <strong class='mb-1'>{$result['name']}</strong>
                            <span class='badge bg-{$relevanceColor} mb-1' style='font-size: 0.7em; width: fit-content;'>
                                {$result['relevance_score']}% Match
                            </span>
                        </div>
                    ";
                })
                ->sort()
                ->cantHide(),

            TD::make('title', 'Position')
                ->render(function ($result) {
                    return "
                        <div class='d-flex flex-column'>
                            <strong class='text-primary mb-1'>{$result['title']}</strong>
                            <small class='text-muted'>{$result['company']}</small>
                        </div>
                    ";
                })
                ->sort(),

            TD::make('location', 'Location')
                ->render(function ($result) {
                    return "<small class='text-muted'><i class='bi bi-geo-alt'></i> {$result['location']}</small>";
                }),

            TD::make('skills', 'Skills')
                ->render(function ($result) {
                    $skills = array_slice($result['skills'], 0, 3); // Show first 3 skills
                    $skillBadges = array_map(function($skill) {
                        return "<span class='badge bg-light text-dark me-1'>{$skill}</span>";
                    }, $skills);
                    
                    $moreCount = count($result['skills']) - 3;
                    if ($moreCount > 0) {
                        $skillBadges[] = "<span class='badge bg-secondary'>+{$moreCount} more</span>";
                    }
                    
                    return implode('', $skillBadges);
                })
                ->width('200px'),

            TD::make('headline', 'Summary')
                ->render(function ($result) {
                    $headline = strlen($result['headline']) > 80 ? 
                               substr($result['headline'], 0, 80) . '...' : 
                               $result['headline'];
                    return "<small class='text-muted'>{$headline}</small>";
                })
                ->width('250px'),

            TD::make('connections', 'Network')
                ->render(function ($result) {
                    return "<small class='text-info'><i class='bi bi-people'></i> {$result['connections']} connections</small>";
                })
                ->width('120px'),

            TD::make('actions', 'Actions')
                ->render(function ($result) {
                    return DropDown::make()
                        ->icon('bs.three-dots-vertical')
                        ->list([
                            Button::make('View Profile')
                                ->icon('bs.eye')
                                ->method('getCandidateDetails')
                                ->parameters(['profile_id' => $result['id']])
                                ->confirm('This will load detailed candidate information. Continue?'),

                            Link::make('LinkedIn Profile')
                                ->icon('bi.linkedin')
                                ->href($result['profile_url'])
                                ->target('_blank'),
                                
                            Button::make('Add to Pipeline')
                                ->icon('bs.plus-circle')
                                ->method('addToPipeline')
                                ->parameters(['profile_data' => $result])
                                ->confirm('Add this candidate to your pipeline?'),
                                
                            Button::make('Send Message')
                                ->icon('bs.envelope')
                                ->method('sendMessage')
                                ->parameters(['profile_id' => $result['id']])
                        ]);
                })
                ->width('100px')
                ->cantHide(),
        ];
    }

}
