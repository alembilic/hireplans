<?php

namespace App\Orchid\Layouts\Candidate;

use Orchid\Filters\Filter;
use Orchid\Screen\Layouts\Selection;
use App\Orchid\Filters\Candidate\CandidateNameFilter;
use App\Orchid\Filters\Candidate\CandidateRefFilter;

class CandidateFiltersLayout extends Selection
{
    // public $template = self::TEMPLATE_DROP_DOWN; // or self::TEMPLATE_LINE
    public $template = self::TEMPLATE_LINE; // or self::TEMPLATE_LINE

    /**
     * @return string[]|Filter[]
     */
    public function filters(): iterable
    {
        return [
            CandidateNameFilter::class,
            CandidateRefFilter::class,
        ];
    }
}
