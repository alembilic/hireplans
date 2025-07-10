<?php

namespace App\Orchid\Filters;

use App\Models\User;
use Illuminate\Database\Eloquent\Builder;
use Orchid\Filters\Filter;
use Orchid\Screen\Field;
use Orchid\Screen\Fields\Select;

class JobCreatedByFilter extends Filter
{
    /**
     * The displayable name of the filter.
     *
     * @return string
     */
    public function name(): string
    {
        return 'Recruiter';
    }

    /**
     * The array of matched parameters.
     *
     * @return array|null
     */
    public function parameters(): ?array
    {
        return ['created_by'];
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
        $createdBy = $this->request->get('created_by');
        
        if (!$createdBy) {
            return $builder;
        }

        return $builder->where('created_by', $createdBy);
    }

    /**
     * Get the display fields.
     *
     * @return Field[]
     */
    public function display(): iterable
    {
        $users = User::orderBy('name')->pluck('name', 'id')->toArray();
        
        return [
            Select::make('created_by')
                ->options($users)
                ->empty('All Recruiters')
                ->value($this->request->get('created_by'))
                ->title('Filter by Recruiter'),
        ];
    }
}
