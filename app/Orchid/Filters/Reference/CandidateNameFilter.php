<?php

namespace App\Orchid\Filters\Reference;

use Illuminate\Database\Eloquent\Builder;
use Orchid\Filters\Filter;
use Orchid\Screen\Field;
use Orchid\Screen\Fields\Input;

class CandidateNameFilter extends Filter
{
    /**
     * The displayable name of the filter.
     *
     * @return string
     */
    public function name(): string
    {
        return 'Candidate Name';
    }

    /**
     * The array of matched parameters.
     *
     * @return array|null
     */
    public function parameters(): ?array
    {
        return ['user_name'];
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
        return $builder->whereHas('candidate.user', function ($query) {
            $query->where('name', 'like', '%' . $this->request->get('user_name') . '%');
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
            Input::make('user_name')
                ->type('text')
                ->value($this->request->get('user_name'))
                ->placeholder('Enter candidate name')
                ->title('Candidate name'),
        ];
    }
}
