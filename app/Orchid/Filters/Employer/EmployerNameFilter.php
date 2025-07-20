<?php

namespace App\Orchid\Filters\Employer;

use Illuminate\Database\Eloquent\Builder;
use Orchid\Filters\Filter;
use Orchid\Screen\Field;
use Orchid\Screen\Fields\Input;

class EmployerNameFilter extends Filter
{
    /**
     * The displayable name of the filter.
     *
     * @return string
     */
    public function name(): string
    {
        return 'Employer Name';
    }

    /**
     * The array of matched parameters.
     *
     * @return array|null
     */
    public function parameters(): ?array
    {
        return [
            'employer_name'
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
        return $builder->where('employers.name', 'like', '%' . $this->request->get('employer_name') . '%');
    }

    /**
     * Get the display fields.
     *
     * @return Field[]
     */
    public function display(): iterable
    {

        return [
            Input::make('employer_name')
                ->type('text')
                ->value($this->request->get('employer_name'))
                ->placeholder('Enter employer name')
                ->title('Employer name'),
        ];
    }
}
