<?php

namespace App\Orchid\Filters\Meeting;

use Illuminate\Database\Eloquent\Builder;
use Orchid\Filters\Filter;
use Orchid\Screen\Field;
use Orchid\Screen\Fields\Select;

class MeetingTypeFilter extends Filter
{
    /**
     * The displayable name of the filter.
     *
     * @return string
     */
    public function name(): string
    {
        return 'Meeting Type';
    }

    /**
     * The array of matched parameters.
     *
     * @return array|null
     */
    public function parameters(): ?array
    {
        return ['type'];
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
        $type = $this->request->get('type');
        
        if (!$type) {
            return $builder;
        }

        return $builder->where('type', $type);
    }

    /**
     * Get the display fields.
     *
     * @return Field[]
     */
    public function display(): iterable
    {
        return [
            Select::make('type')
                ->options([
                    'video' => 'Video Call',
                    'phone' => 'Phone Call',
                ])
                ->empty('All Types')
                ->value($this->request->get('type'))
                ->title('Meeting Type'),
        ];
    }
} 