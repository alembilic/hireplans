<?php

namespace App\Orchid\Screens\Reference;

use App\Orchid\Layouts\Reference\ReferenceEditLayout;
use App\Orchid\Layouts\Reference\ReferenceFeedbackEditLayout;
use Illuminate\Http\Request;
use Orchid\Screen\Actions\Button;
use Orchid\Screen\Fields\Group;
use Orchid\Support\Facades\Layout;
use Orchid\Screen\Screen;
use App\Models\Reference;
use Orchid\Screen\Layouts\Selection;
use Illuminate\Support\Facades\Auth;
use Orchid\Support\Color;
use Illuminate\Validation\Rule;
use Orchid\Support\Facades\Toast;
use Illuminate\Database\Eloquent\Builder;
use App\Models\ReferenceCandidateFeedback as Feedback;

class PublicReferenceFeedbackEditScreen extends Screen
{

    /**
     * @var Reference
     */
    public $reference;

    /**
     * @var Feedback
     */
    public $feedback;

    /**
     * Fetch data to be displayed on the screen.
     *
     * @return array
     */
    public function query(Reference $reference): iterable
    {
        $reference->load('candidate');
        $reference->load('candidate.user');
        $reference->load('feedback');

        $this->reference = $reference;

        $this->feedback = $reference->feedback ?? new Feedback;

        // dd($this->feedback);
        // $this->reference->candidate_employed_from = $this->reference->candidate_employed_from ? \Carbon\Carbon::parse($this->reference->candidate_employed_from)->format('Y/m/d') : null;

        return [
            'reference' => $reference,
            'feedback' => $this->feedback,
        ];
    }

    /**
     * The name of the screen displayed in the header.
     *
     * @return string|null
     */
    public function name(): ?string
    {
        return 'RefereneFeedbackEditScreen';
    }

    /**
     * The screen's action buttons.
     *
     * @return \Orchid\Screen\Action[]
     */
    public function commandBar(): iterable
    {
        return [];
    }

    /**
     * The screen's layout elements.
     *
     * @return \Orchid\Screen\Layout[]|string[]
     */
    public function layout(): iterable
    {
        return [
            Layout::block([ReferenceEditLayout::class])->vertical()->title('Professional Reference Information'),
            Layout::block([ReferenceFeedbackEditLayout::class])->vertical()->title('Feedback Details'),

            Layout::rows([
                Group::make([
                    Button::make('Save Feedback')
                        ->method('saveFeedback')
                        ->type(Color::PRIMARY)
                        ->icon('bs.check-circle'),
                    Button::make('Cancel')
                        ->method('cancel')
                        ->type(Color::SECONDARY)
                        ->icon('bs.x-circle')
                        ->rawClick(),
                    // Link::make('Cancel')
                    //     ->icon('close')
                    //     ->route('platform.candidates.list'),
                ])->autoWidth()->alignCenter(),
            ]),
        ];
    }

    /**
     * @param \Illuminate\Http\Request $request
     *
     * @return void
     */
    public function saveFeedback(Request $request)
    {
        // dd($request->all());

        $request->validate([
            'reference.name' => 'required|string',
            'reference.email' => 'required|email',
            'reference.phone' => 'required|string',
            'reference.relationship' => 'required|string',
            'feedback.declaration' => 'required',
        ]);

        // save feedback data
        $feedbackData = collect($request->input('feedback'))->except(['declaration'])->toArray();
        $feedbackData['candidate_id'] = $this->reference->candidate_id;
        $feedbackData['signed_at'] = now();
        $this->feedback->updateOrCreate(['reference_id' => $this->reference->id], $feedbackData);

        // save reference data
        $referenceData = collect($request->input('reference'))->except([])->toArray();
        $referenceData['completed_at'] = now();
        $this->reference->updateOrCreate(['candidate_id' => $this->reference->candidate_id], $referenceData);

        Toast::info('Reference feedback saved successfully.');

        // return redirect()->route('platform.candidates.list');
    }


}
