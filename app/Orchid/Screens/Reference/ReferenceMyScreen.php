<?php

namespace App\Orchid\Screens\Reference;

use App\Orchid\Layouts\Reference\ReferenceFiltersLayout;
use App\Orchid\Layouts\Reference\ReferenceListLayout;
use App\Orchid\Layouts\Reference\ReferenceMyLayout;
use Illuminate\Support\Facades\Auth;
use Orchid\Screen\Actions\Button;
use Orchid\Screen\Actions\Link;
use Orchid\Screen\Screen;
use App\Models\Reference;

class ReferenceMyScreen extends Screen
{
    /**
     * Fetch data to be displayed on the screen.
     *
     * @return array
     */
    public function query(): iterable
    {
        $user = Auth::user();

        return [
            'references' => Reference::with('candidate')
                ->leftJoin('candidates', 'references.candidate_id', '=', 'candidates.id')
                ->leftJoin('users', 'candidates.user_id', '=', 'users.id')
                ->select('references.*', 'candidates.candidate_ref', 'users.name as user_name')
                ->where('users.id', $user->id)
                ->filters(ReferenceFiltersLayout::class)
                ->defaultSort('id', 'desc')
                ->paginate(),
        ];
    }

    /**
     * The name of the screen displayed in the header.
     *
     * @return string|null
     */
    public function name(): ?string
    {
        return 'References';
    }

    public function permission(): array
    {
        return [
            'reference.view.my',
        ];
    }

    /**
     * The screen's action buttons.
     *
     * @return \Orchid\Screen\Action[]
     */
    public function commandBar(): iterable
    {
        return [
            Link::make('Add Reference')
                ->icon('bs.plus-circle')
                // ->method('create')
                ->href(route('platform.references.create'))
                ->canSee(true),
        ];
    }

    /**
     * The screen's layout elements.
     *
     * @return \Orchid\Screen\Layout[]|string[]
     */
    public function layout(): iterable
    {
        return [
            // ReferenceFiltersLayout::class,
            ReferenceMyLayout::class,
        ];
    }
}
