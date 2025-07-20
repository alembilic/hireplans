<?php

namespace App\Orchid\Layouts\Reference;

use Orchid\Screen\Field;
use Orchid\Screen\Fields\CheckBox;
use Orchid\Screen\Fields\Group;
use Orchid\Screen\Fields\RadioButtons;
use Orchid\Screen\Layouts\Rows;
use App\Helpers\HelperFunc;
use App\Orchid\Fields\HtmlField;
use App\Orchid\Fields\RawHtml;
use Orchid\Screen\Fields\Select;
use Orchid\Screen\Fields\Input;
use Orchid\Screen\Fields\TextArea;
use Illuminate\Support\Facades\Auth;

class ReferenceFeedbackEditLayout extends Rows
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
        // dd($this->query);
        return [
            RawHtml::make('feedback_details_start')
                ->html('<div class="feedback-details">'),

            RawHtml::make('feedback_details_desc')
                ->html('<div class="py-2"><p>Please rank the candidate\'s attributes, if known and applicable, using the scale below. In your final comments, please ensure to provide commentary on the rationale for your rankings. Leave unchecked if unsure.</p></div>'),

            RadioButtons::make('feedback.quality_of_teaching')
                ->title('Quality of teaching/management')
                ->options(HelperFunc::feedbackScoreOptions())
                ->horizontal(),

            RadioButtons::make('feedback.breadth_of_knowledge')
                ->title('Breadth and depth of knowledge')
                ->options(HelperFunc::feedbackScoreOptions())
                ->horizontal(),

            RadioButtons::make('feedback.relationship_with_students')
                ->title('Relationship with students')
                ->options(HelperFunc::feedbackScoreOptions())
                ->horizontal(),

            RadioButtons::make('feedback.communication_with_parents')
                ->title('Communication with parents')
                ->options(HelperFunc::feedbackScoreOptions())
                ->horizontal(),

            RadioButtons::make('feedback.relationship_with_colleagues')
                ->title('Relationship with colleagues')
                ->options(HelperFunc::feedbackScoreOptions())
                ->horizontal(),

            RadioButtons::make('feedback.reliability_and_integrity')
                ->title('Reliability and integrity')
                ->options(HelperFunc::feedbackScoreOptions())
                ->horizontal(),

            RadioButtons::make('feedback.class_management')
                ->title('Class management and the ability to attain and sustain good student participation')
                ->options(HelperFunc::feedbackScoreOptions())
                ->horizontal(),

            RadioButtons::make('feedback.embraces_diversity')
                ->title('Embraces student diversity')
                ->options(HelperFunc::feedbackScoreOptions())
                ->horizontal(),

            RadioButtons::make('feedback.creativity')
                ->title('Creativity')
                ->options(HelperFunc::feedbackScoreOptions())
                ->horizontal(),

            RadioButtons::make('feedback.time_keeping')
                ->title('Time keeping and punctuality')
                ->options(HelperFunc::feedbackScoreOptions())
                ->horizontal(),

            RadioButtons::make('feedback.safe_positive_workspace')
                ->title('Provides a safe, positive and collaborative workspace')
                ->options(HelperFunc::feedbackScoreOptions())
                ->horizontal()
                ->hr(),

            // Additional questions (Disclosures)
            RawHtml::make('feedback_details_end')
                ->html('<div class="py-2 font-bold">Disclosure</div>'),

            RadioButtons::make('feedback.work_with_again')
                ->title('Would you work with this person again?')
                ->options(HelperFunc::feedbackDisclosureOptions())
                ->horizontal(),

            RadioButtons::make('feedback.ethical_compromise')
                ->title('To your knowledge, has this candidate ever been in a situation that may compromise their ethical or professional standing?')
                ->options(HelperFunc::feedbackDisclosureOptions())
                ->horizontal(),

            RadioButtons::make('feedback.child_protection_issues')
                ->title('To your knowledge, has the candidate ever been reported/investigated for issues relating to child protection?')
                ->options(HelperFunc::feedbackDisclosureOptions())
                ->horizontal()
                ->hr(),

            RawHtml::make('feedback_details_end')
                ->html('</div>'),

            TextArea::make('feedback.comments')
                ->title('Final comments')
                ->rows(5)
                ->horizontal()
                ->placeholder('Please provide any additional comments or feedback here.')
                ->help('Please use the space to comment on the candidates\'s strengths and potential that would further expand on the characteristics above. Alternatively, you may choose to attach a letter to this form.'),

            CheckBox::make('feedback.declaration')
                ->title(__('Declaration'))
                ->placeholder(__('I declare that to the best of my knowledge, the information I have given in this reference is correct and complete'))
                ->required()
                ->horizontal(),
        ];
    }

}
