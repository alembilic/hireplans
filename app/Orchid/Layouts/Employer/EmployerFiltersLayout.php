<?php

namespace App\Orchid\Layouts\Employer;

use App\Orchid\Filters\Employer\EmployerNameFilter;
use App\Orchid\Filters\Employer\EmployerRefFilter;
use Orchid\Filters\Filter;
use Orchid\Screen\Layouts\Selection;

class EmployerFiltersLayout extends Selection
{
    public $template = self::TEMPLATE_LINE; // or self::TEMPLATE_LINE

    /**
     * @return Filter[]
     */
    public function filters(): iterable
    {
        return [
            EmployerNameFilter::class,
            EmployerRefFilter::class,
        ];
    }
}
