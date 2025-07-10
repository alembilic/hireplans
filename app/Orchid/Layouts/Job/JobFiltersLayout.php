<?php

namespace App\Orchid\Layouts\Job;

use App\Orchid\Filters\Job\JobKeywordFilter;
use App\Orchid\Filters\Job\JobLocationFilter;
use App\Orchid\Filters\JobCreatedByFilter;
use Orchid\Filters\Filter;
use Orchid\Screen\Layouts\Selection;

class JobFiltersLayout extends Selection
{
    public $template = self::TEMPLATE_LINE; // or self::TEMPLATE_LINE

    /**
     * @return Filter[]
     */
    public function filters(): iterable
    {
        return [
            JobKeywordFilter::class,
            JobLocationFilter::class,
            JobCreatedByFilter::class,
        ];
    }
}
