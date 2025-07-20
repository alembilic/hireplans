<?php

namespace App\Orchid\Layouts\Reference;

use App\Orchid\Filters\Reference\CandidateNameFilter;
use App\Orchid\Filters\Reference\ReferenceNameFilter;
use Orchid\Filters\Filter;
use Orchid\Screen\Layouts\Selection;

class ReferenceFiltersLayout extends Selection
{
    public $template = self::TEMPLATE_LINE; // or self::TEMPLATE_LINE

    /**
     * @return Filter[]
     */
    public function filters(): iterable
    {
        return [
            ReferenceNameFilter::class,
            CandidateNameFilter::class,
        ];
    }
}
