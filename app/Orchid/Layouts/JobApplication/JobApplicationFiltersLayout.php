<?php

namespace App\Orchid\Layouts\JobApplication;

use App\Orchid\Filters\JobApplication\JobApplicationCandidateFilter;
use App\Orchid\Filters\JobApplication\JobApplicationJobFilter;
use Orchid\Filters\Filter;
use Orchid\Screen\Layouts\Selection;

class JobApplicationFiltersLayout extends Selection
{
    public $template = self::TEMPLATE_LINE; // or self::TEMPLATE_LINE

    /**
     * @return Filter[]
     */
    public function filters(): iterable
    {
        return [
            JobApplicationJobFilter::class,
            JobApplicationCandidateFilter::class,
        ];
    }
}
