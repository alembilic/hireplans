<?php

namespace App\Orchid\Filters\Candidate;

use Illuminate\Database\Eloquent\Builder;
use Orchid\Filters\Filter;
use Orchid\Screen\Field;
use Orchid\Screen\Fields\Input;

class CandidateRefFilter extends Filter
{
    /**
     * The displayable name of the filter.
     *
     * @return string
     */
    public function name(): string
    {
        return 'Candidate Reference';
    }

    /**
     * The array of matched parameters.
     *
     * @return array|null
     */
    public function parameters(): ?array
    {
        return [
            'candidate_ref',
        ];
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
        // return $builder->where('candidate_ref', $this->request->get('candidate_ref'));
        return $builder->where('candidate_ref', 'like', '%' . $this->request->get('candidate_ref') . '%');
    }

    /**
     * Get the display fields.
     *
     * @return Field[]
     */
    public function display(): iterable
    {
        return [
            Input::make('candidate_ref')
                ->type('text')
                ->value($this->request->get('candidate_ref'))
                ->placeholder('Enter candidate reference')
                ->title('Candidate reference'),
        ];
    }
}
