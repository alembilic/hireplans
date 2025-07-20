<?php

namespace App\Orchid\Layouts\Reference;


use App\Helpers\HelperFunc;
use App\Orchid\Fields\HtmlField;
use Orchid\Screen\Field;
use Orchid\Screen\Layouts\Rows;
use Orchid\Screen\Fields\Select;
use Orchid\Screen\Fields\Input;
use Orchid\Screen\Fields\TextArea;
use Illuminate\Support\Facades\Auth;

class ReferenceEditLayout extends Rows
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
        // dd(\Carbon\Carbon::parse($this->query['reference']->candidate_employed_from)->format('Y/m/d'));

        // Convert to DateTime objects and format
        // $employedFromDateFormatted = (new \DateTime($candidateEmployedFrom))->format('Y-m-d');
        // $employedToDateFormatted = (new \DateTime($candidateEmployedTo))->format('Y-m-d');


        return [
            Input::make('reference.id')
                ->type('hidden')
                ->value($this->query->get('referece')->id ?? null)
                ->horizontal(),

            Input::make('reference.candidate_id')
                ->type('hidden')
                ->value($this->query->get('candidate')->id ?? null)
                ->horizontal(),

            HtmlField::make('referee_details')
                ->label('<h2 class="fw-boldx text-xl">Referee details</h2>')
                ->horizontal()
                ->html(''),

            Input::make('reference.name')
                ->type('text')
                ->max(255)
                ->title(__('Referee Name'))
                ->placeholder(__('Name'))
                ->required()
                ->horizontal(),

            Input::make('reference.email')
                ->type('email')
                ->max(255)
                ->title(__('Referee Email'))
                ->placeholder(__('Email'))
                ->required()
                ->horizontal(),

            Input::make('reference.phone')
                ->type('text')
                ->max(255)
                ->title(__('Referee Phone'))
                ->placeholder(__('Phone'))
                ->required()
                ->horizontal(),

            Input::make('reference.relationship')
                ->type('text')
                ->max(255)
                ->title(__('Referee Relationship'))
                ->placeholder(__('Relationship'))
                ->required()
                ->horizontal(),

            Input::make('reference.position')
                ->type('text')
                ->max(255)
                ->title(__('Referee Position'))
                ->placeholder(__('Position'))
                ->required()
                ->horizontal(),

            Input::make('reference.company')
                ->type('text')
                ->max(255)
                ->title(__('Company'))
                ->placeholder(__('Company'))
                ->required()
                ->horizontal(),

            Input::make('reference.company_address')
                ->type('text')
                ->max(255)
                ->title(__('Company Address'))
                ->placeholder(__('Company Address'))
                ->horizontal()
                ->required()
                ->hr(),

            HtmlField::make('my_details')
                ->label('<h2 class="fw-boldx text-xl">Candidate details</h2>')
                ->horizontal()
                ->html(''),

            Input::make('reference.candidate_position')
                ->type('text')
                ->max(255)
                ->title(__('Position'))
                ->placeholder(__('My Position'))
                ->horizontal()
                ->nullable(),

            Input::make('reference.candidate_employed_from')
                ->type('date')
                // ->required()
                ->title(__('Employed From'))
                ->placeholder(__('Employed From'))
                // ->value($this->query['reference']->candidate_employed_from ? \Carbon\Carbon::parse($this->query['reference']->candidate_employed_from)->format('Y-m-d') : null)
                ->value(function () {
                    $reference = $this->query->get('referece');
                    return $reference && $reference->candidate_employed_from ? \Carbon\Carbon::parse($reference->candidate_employed_from)->format('Y-m-d') : null;
                })
                ->horizontal(),

            Input::make('reference.candidate_employed_to')
                ->type('date')
                // ->required()
                ->title(__('Employed To'))
                ->placeholder(__('Employed To'))
                // ->value($this->query['reference']->candidate_employed_to ? \Carbon\Carbon::parse($this->query['reference']->candidate_employed_to)->format('Y-m-d') : null)
                ->value(function () {
                    $reference = $this->query->get('referece');
                    return $reference && $this->query->get('referece')->candidate_employed_to ? \Carbon\Carbon::parse($this->query->get('referece')->candidate_employed_to)->format('Y-m-d') : null;
                })
                ->horizontal(),

            Select::make('reference.candidate_job_type')
                ->options(HelperFunc::getJobTypes())
                ->title(__('Job Type'))
                ->empty('Select a job type')
                ->horizontal(),

            Input::make('reference.candidate_service_duration')
                ->type('text')
                ->max(255)
                ->title(__('Service Duration'))
                ->placeholder(__('Service Duration'))
                ->horizontal(),

            TextArea::make('reference.candidate_leaving_reason')
                ->title(__('Leaving Reason'))
                ->placeholder(__('Leaving Reason'))
                ->horizontal(),
        ];
    }
}
