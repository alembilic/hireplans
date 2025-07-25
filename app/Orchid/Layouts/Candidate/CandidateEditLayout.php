<?php

namespace App\Orchid\Layouts\Candidate;

use Orchid\Screen\Fields\Select;
use Orchid\Screen\Fields\Input;
use Orchid\Screen\Fields\SimpleMDE;
use Orchid\Screen\Field;
use Orchid\Screen\Fields\TextArea;
use Orchid\Screen\Layouts\Rows;
use Illuminate\Support\Facades\Auth;

class CandidateEditLayout extends Rows
{
    /**
     * Used to create the title of a group of form elements.
     *
     * @var string|null
     */
    protected $title;

    /**
     * Get the fields elements to be displayed.
     *
     * @return Field[]
     */
    protected function fields(): iterable
    {
        $fields = [
            Select::make('candidate.gender')
                ->title('Gender')
                ->options(['Male'=>'Male','Female'=>'Female','Other'=>'Other','Prefer not to say'=>'Prefer not to say'])
                ->empty('')
                ->horizontal(),

            Input::make('candidate.current_company')
                ->type('text')
                ->max(255)
                ->title(__('Current Company'))
                ->placeholder(__('Current Company'))
                ->horizontal(),

            Input::make('candidate.current_job_title')
                ->type('text')
                ->max(255)
                ->title(__('Current Job Title'))
                ->placeholder(__('Current Job Title'))
                ->horizontal(),

            Input::make('candidate.languages')
                ->type('text')
                ->max(255)
                ->title(__('Languages'))
                ->placeholder(__('Languages'))
                ->help('Separate languages with a comma. Eg. English, Spanish, French')
                ->horizontal(),

            Input::make('candidate.skills')
                ->type('text')
                ->max(255)
                ->title(__('Skills'))
                ->placeholder(__('Skills'))
                ->help('Separate skills with a comma. Eg. PHP, Java, Python')
                ->horizontal(),
        ];

        if (Auth::user()->hasAccess('platform.systems.users')) {
            // $fields[] = SimpleMDE::make('candidate.notes')
            //                 ->title(__('Admin Notes'))
            //                 // ->popover(__('Notes'))
            //                 ->help('These notes are visible to the admins only. They are not visible to the candidate.')
            //                 ->horizontal();
            $fields[] = TextArea::make('candidate.notes')
                            ->title(__('Admin Notes'))
                            // ->popover(__('Notes'))
                            ->rows(10)
                            ->help('These notes are visible to the admins only. They are not visible to the candidate.')
                            ->horizontal();
        }

        return $fields;
    }
}
