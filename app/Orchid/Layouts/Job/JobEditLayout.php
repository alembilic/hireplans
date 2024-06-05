<?php

namespace App\Orchid\Layouts\Job;

use Orchid\Screen\Field;
use Orchid\Screen\Fields\CheckBox;
use Orchid\Screen\Layouts\Rows;
use Orchid\Screen\Fields\Select;
use Orchid\Screen\Fields\Input;
use Orchid\Screen\Fields\SimpleMDE;
use App\Helpers\CountryHelper;
use App\Helpers\HelperFunc;
use Orchid\Screen\Fields\Relation;
use App\Models\Employer;

class JobEditLayout extends Rows
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
        return [
            // Relation::make('job.employer_id')
            //     ->title('Employer')
            //     ->fromModel(Employer::class, 'name') // Display employer name
            //     ->empty('No employer')
            //     ->required()
            //     ->horizontal(),

            Relation::make('job.employer_id')
                ->fromModel(Employer::class, 'name')
                ->title(__('Employer'))
                ->required()
                ->horizontal(),

            input::make('job.title')
                ->type('text')
                ->max(255)
                ->required()
                ->title(__('Job Title'))
                ->placeholder(__('Job Title'))
                ->horizontal(),

            Input::make('job.location')
                ->type('text')
                ->max(255)
                ->title(__('Location'))
                ->placeholder(__('Location'))
                ->horizontal(),

            Input::make('job.salary')
                ->type('text')
                ->max(255)
                ->title(__('Salary'))
                ->placeholder(__('Salary'))
                ->horizontal(),

            Select::make('job.job_type')
                ->options(HelperFunc::getJobTypes())
                ->title('Job Type')
                ->empty('Select a job type')
                ->horizontal(),

            Select::make('job.category')
                ->options(HelperFunc::getJobCategories())
                ->title('Category')
                ->empty('Select a category')
                ->horizontal(),

            Select::make('job.experience_level')
                ->options(HelperFunc::getExperienceLevels())
                ->title('Experience Level')
                ->empty('Select an experience level')
                ->horizontal(),

            Input::make('job.application_deadline')
                ->type('date')
                ->title(__('Application Deadline'))
                ->placeholder(__('Application Deadline'))
                ->horizontal(),

            // Input::make('job.is_active')
            //     ->type('checkbox')
            //     ->sendTrueOrFalse()
            //     ->title(__('Is Active'))
            //     ->placeholder(__('Is Active'))
            //     ->horizontal(),
            CheckBox::make('job.is_active')
                ->title(__('Is Active'))
                ->placeholder(__('Is Active'))
                ->horizontal(),

            SimpleMDE::make('job.details')
                ->title(__('Details'))
                // ->required()
                ->horizontal(),

        ];
    }
}
