<?php

namespace App\Orchid\Layouts\Meeting;

use App\Orchid\Filters\Meeting\MeetingTitleFilter;
use App\Orchid\Filters\Meeting\MeetingCandidateFilter;
use App\Orchid\Filters\Meeting\MeetingTypeFilter;
use App\Orchid\Filters\Meeting\MeetingStatusFilter;
use Orchid\Filters\Filter;
use Orchid\Screen\Layouts\Selection;

class MeetingFiltersLayout extends Selection
{
    public $template = self::TEMPLATE_LINE;

    /**
     * @return Filter[]
     */
    public function filters(): iterable
    {
        return [
            MeetingTitleFilter::class,
            MeetingCandidateFilter::class,
            MeetingTypeFilter::class,
            MeetingStatusFilter::class,
        ];
    }
} 