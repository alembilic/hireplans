<?php

namespace App\Orchid\Filters\JobApplication;

use Illuminate\Database\Eloquent\Builder;
use Orchid\Filters\Filter;
use Orchid\Screen\Field;
use Orchid\Screen\Fields\Input;

class JobApplicationCandidateFilter extends Filter
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
        $user_name = '%' . $this->request->get('user_name') . '%';
        return $builder->where('users.name', 'like', $user_name);
    }

    /**
     * Get the display fields.
     *
     * @return Field[]
     */
    public function display(): iterable
    {
        return [
            Input::make('user_name')
                ->type('text')
                ->value($this->request->get('user_name'))
                ->placeholder('Applicant name')
                ->title('Search by applicant name'),
            ];
    }
}
