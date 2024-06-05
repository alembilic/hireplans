<?php

namespace App\Orchid\Filters\Job;

use Illuminate\Database\Eloquent\Builder;
use Orchid\Filters\Filter;
use Orchid\Screen\Field;
use Orchid\Screen\Fields\Input;

class JobKeywordFilter extends Filter
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
        // return $builder;
        if (!$this->request->get('keyword')) {
            return $builder;
        }

        $keyword = '%' . $this->request->get('keyword') . '%';

        return $builder->where(function ($query) use ($keyword) {
            $query->where('title', 'like', $keyword)
                ->orWhere('jobs.details', 'like', $keyword)
                ->orWhere('job_ref', 'like', $keyword)
                ->orWhere('job_type', 'like', $keyword)
                ->orWhere('category', 'like', $keyword)
                ->orWhere('experience_level', 'like', $keyword);
        });
    }

    /**
     * Get the display fields.
     *
     * @return Field[]
     */
    public function display(): iterable
    {
        return [
            Input::make('keyword')
                ->type('text')
                ->value($this->request->get('keyword'))
                ->placeholder('Enter keyword')
                ->title('Search keyword'),
            ];
    }
}
