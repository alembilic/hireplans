<?php

namespace App\Orchid\Layouts\Reference;

use App\Models\Reference;
use Orchid\Screen\Actions\Link;
use Orchid\Screen\Components\Cells\DateTimeSplit;
use Orchid\Screen\Layouts\Table;
use Orchid\Screen\TD;

class ReferenceListLayout extends Table
{
    /**
     * Data source.
     *
     * The name of the key to fetch it from the query.
     * The results of which will be elements of the table.
     *
     * @var string
     */
    protected $target = 'references';

    /**
     * Get the table cells to be displayed.
     *
     * @return TD[]
     */
    protected function columns(): iterable
    {
        return [
            TD::make('name', 'Name')
                ->sort()
                ->cantHide()
                // ->filter(TD::FILTER_TEXT)
                ->render(function (Reference $reference) {
                    return Link::make($reference->name)
                        ->route('platform.reference.view', $reference->id)
                        ->class('text-primary');
                })
                ,

            TD::make('position', 'Position')
                ->sort()
                ->cantHide()
                // ->filter(TD::FILTER_TEXT)
                ,

            TD::make('company', 'Company')
                ->sort()
                ->cantHide()
                // ->filter(TD::FILTER_TEXT)
                ,

            TD::make('candidate', 'Candidate')
                ->sort()
                ->cantHide()
                // ->filter(TD::FILTER_TEXT)
                ->render(function (Reference $reference) {
                    return $reference->candidate->user->name;
                }),

            TD::make('status', 'Status')
                ->sort()
                ->cantHide()
                // ->filter(TD::FILTER_TEXT)
                ->render(function (Reference $reference) {
                    return $reference->completed_at
                        ? '<span class="btn-success px-2 py-1 text-xs font-semibold text-green-700 bg-green-100 rounded-full">Completed</span>'
                        : '<span class="btn-warning px-2 py-1 text-xs font-semibold text-yellow-700 bg-yellow-100 rounded-full">Pending</span>';
                }),

            TD::make('created_at', __('Added On'))
                ->usingComponent(DateTimeSplit::class)
                ->align(TD::ALIGN_RIGHT)
                // ->defaultHidden()
                ->sort(),

        ];
    }
}
