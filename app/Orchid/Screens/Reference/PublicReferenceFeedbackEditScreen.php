<?php

namespace App\Orchid\Screens\Reference;

use App\Orchid\Layouts\Reference\ReferenceEditLayout;
use App\Orchid\Layouts\Reference\ReferenceFeedbackEditLayout;
use Illuminate\Http\Request;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Orchid\Screen\Repository;
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
    public function query(Reference $reference, Request $request): iterable
    {
        $reference->load('candidate');
        $reference->load('candidate.user');
        $reference->load('feedback');

        $this->reference = $reference;
        $this->feedback = $reference->feedback ?? new Feedback;

        // dd($this->reference);

        // Retrieve the "code" parameter from the query string
        $code = $request->query('code');

        // Validate the code (example validation logic)
        if (empty($code) || $code !== $this->reference->code) {
            abort(403, 'Unauthorized.');
        }

        if (!empty($this->feedback->signed_at)) {
            abort(403, 'This feedback already submitted.');
        }

        // dd($this->reference);
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
        return 'Referene Feedback';
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
        if (session()->has('feedback_submitted')) {
            return [
                Layout::view('partials.notification_message', [
                    'message' => session('feedback_submitted'),
                ]),
            ];
        }

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

    public function view(Repository|array $httpQueryArguments = []): Factory|View
    {
        // return view('layouts.custom-platform-layout', [
        //     'content' => $this->layout(), // Pass the layout components as data to the view
        // ]);

        $repository = is_a($httpQueryArguments, Repository::class)
            ? $httpQueryArguments
            : $this->buildQueryRepository($httpQueryArguments);

        return view('layouts.custom-platform-layout', [
            'name' => $this->name(),
            'description' => $this->description(),
            'commandBar' => $this->buildCommandBar($repository),
            'layouts' => $this->build($repository),
            'formValidateMessage' => $this->formValidateMessage(),
            'needPreventsAbandonment' => $this->needPreventsAbandonment(),
            'state' => $this->serializeStateWithPublicProperties($repository),
        ]);

    }

    /**
     * @param \Illuminate\Http\Request $request
     *
     * @return void
     */
    public function saveFeedback(Request $request)
    {
        // dd($request->all());
        // dd($request->input('reference.id'));
        // dd($this->reference);

        $request->validate([
            'reference.name' => 'required|string',
            'reference.email' => 'required|email',
            'reference.phone' => 'required|string',
            'reference.relationship' => 'required|string',
            'feedback.declaration' => 'required',
        ]);

        // \DB::enableQueryLog();
        if (empty($this->reference) && !empty($request->input('reference.id'))) {
            $this->reference = Reference::findOrFail($request->input('reference.id'));
        }

        // save reference data
        $referenceData = collect($request->input('reference'))->except(['id', 'candidate_id'])->toArray();
        $referenceData['completed_at'] = now();
        $this->reference->updateOrCreate(['id' => $this->reference->id], $referenceData);
        // dd(\DB::getQueryLog());

        if (empty($this->feedback)) {
            $this->feedback = $this->reference->feedback ?? new Feedback;
        }

        // save feedback data
        $feedbackData = collect($request->input('feedback'))->except(['declaration'])->toArray();
        $feedbackData['candidate_id'] = $this->reference->candidate_id;
        $feedbackData['signed_at'] = now();
        $this->feedback->updateOrCreate(['reference_id' => $this->reference->id], $feedbackData);

        // Toast::info('Reference feedback submitted successfully.');
        session()->flash('feedback_submitted', 'Reference feedback submitted successfully. <br>Thank you for your time.');
        // return redirect()->route('home');
    }


}
