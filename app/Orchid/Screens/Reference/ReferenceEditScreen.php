<?php

namespace App\Orchid\Screens\Reference;

use App\Orchid\Layouts\Reference\ReferenceEditLayout;
use App\Models\Candidate;
use App\Models\Reference;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Orchid\Screen\Screen;
use Orchid\Support\Facades\Layout;
use Orchid\Screen\Fields\Group;
use Orchid\Screen\Actions\Button;
use Orchid\Support\Color;
use Illuminate\Validation\Rule;
use Orchid\Support\Facades\Toast;
use Illuminate\Database\Eloquent\Builder;

class ReferenceEditScreen extends Screen
{

    /**
     * @var Candidate
     */
    public $candidate;

    /**
     * Fetch data to be displayed on the screen.
     *
     * @return array
     */
    public function query(Candidate $candidate): iterable
    {
        if (!$candidate->exists) {
            $candidate = Auth::user()->candidate ?? abort(404);
        }

        $this->candidate = $candidate;

        // dd($this->candidate);

        return [
            'candidate' => $candidate,
        ];
    }

    /**
     * The name of the screen displayed in the header.
     *
     * @return string|null
     */
    public function name(): ?string
    {
        return 'Add Reference';
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
     * Get the permissions required to access this screen.
     *
     * @return iterable|null The permissions required to access this screen.
     */
    public function permission(): ?iterable
    {
        // return [
        //     'platform.systems.users',
        // ];

        return null;
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

            Layout::rows([
                Group::make([
                    Button::make('Add Reference')
                        ->method('saveReference')
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
    public function saveReference(Request $request, Candidate $candidate)
    {
        $request->validate([
            'reference.name' => 'required|string',
            'reference.email' => 'required|email',
            'reference.phone' => 'required|string',
            'reference.relationship' => 'required|string',
        ]);

        $candidate->references()->create($request->input('reference'));

        // $referenceData = $request->collect('reference')->except([])->toArray();
        // $candidate->references()->fill($referenceData)->save();

        Toast::info('Reference added successfully.');

        // return redirect()->route('platform.candidates.list');
    }

    /**
     * Cancel the edit operation and return to the list screen.
     *
     * @return void
     */
    public function cancel()
    {
        // return redirect()->route('platform.candidates.list');
    }
}
