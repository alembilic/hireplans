<?php

namespace App\Orchid\Filters\JobApplication;

use Illuminate\Database\Eloquent\Builder;
use Orchid\Filters\Filter;
use Orchid\Screen\Field;
use Orchid\Screen\Fields\Input;

class JobApplicationJobFilter extends Filter
{
    /**
     * The displayable name of the filter.
     *
     * @return string
     */
    public function name(): string
    {
        return '';
    }

    /**
     * The array of matched parameters.
     *
     * @return array|null
     */
    public function parameters(): ?array
    {
        return [];
    }

    /**
     * Apply to a given Eloquent query builder.
     *
     * @param Builder $builder
     *
     * @return Builder
     */
    public function run(Builder $builder): Builder
    {
        $title = '%' . $this->request->get('job_title') . '%';
        return $builder->where('title', 'like', $title);
    }

    /**
     * Get the display fields.
     *
     * @return Field[]
     */
    public function display(): iterable
    {
        return [
            Input::make('job_title')
                ->type('text')
                ->value($this->request->get('job_title'))
                ->placeholder('Job title')
                ->title('Search by job title'),
        ];
    }
}
