<?php

namespace App\Orchid\Filters\Reference;

use Illuminate\Database\Eloquent\Builder;
use Orchid\Filters\Filter;
use Orchid\Screen\Field;
use Orchid\Screen\Fields\Input;

class ReferenceNameFilter extends Filter
{
    /**
     * The displayable name of the filter.
     *
     * @return string
     */
    public function name(): string
    {
        return 'Reference Name';
    }

    /**
     * The array of matched parameters.
     *
     * @return array|null
     */
    public function parameters(): ?array
    {
        return ['reference_name'];
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
        return $builder->where('references.name', 'like', '%' . $this->request->get('reference_name') . '%');
    }

    /**
     * Get the display fields.
     *
     * @return Field[]
     */
    public function display(): iterable
    {
        return [
            Input::make('reference_name')
                ->type('text')
                ->value($this->request->get('reference_name'))
                ->placeholder('Enter reference name')
                ->title('Reference name'),
        ];
    }
}
