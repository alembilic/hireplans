<?php

declare(strict_types=1);

use App\Orchid\Screens\Candidate\CandidateEditScreen;
use App\Orchid\Screens\Employer\EmployerListScreen;
use App\Orchid\Screens\Examples\ExampleActionsScreen;
use App\Orchid\Screens\Examples\ExampleCardsScreen;
use App\Orchid\Screens\Examples\ExampleChartsScreen;
use App\Orchid\Screens\Examples\ExampleFieldsAdvancedScreen;
use App\Orchid\Screens\Examples\ExampleFieldsScreen;
use App\Orchid\Screens\Examples\ExampleGridScreen;
use App\Orchid\Screens\Examples\ExampleLayoutsScreen;
use App\Orchid\Screens\Examples\ExampleScreen;
use App\Orchid\Screens\Examples\ExampleTextEditorsScreen;
use App\Orchid\Screens\Job\JobEditScreen;
use App\Orchid\Screens\Job\JobListScreen;
use App\Orchid\Screens\Job\JobViewScreen;
use App\Orchid\Screens\JobApplication\JobApplicationEditScreen;
use App\Orchid\Screens\JobApplication\JobApplicationListScreen;
use App\Orchid\Screens\JobApplication\JobApplicationViewScreen;
use App\Orchid\Screens\PlatformScreen;
use App\Orchid\Screens\Reference\ReferenceEditScreen;
use App\Orchid\Screens\Reference\ReferenceFeedbackEditScreen;
use App\Orchid\Screens\Reference\ReferenceListScreen;
use App\Orchid\Screens\Reference\ReferenceMyScreen;
use App\Orchid\Screens\Reference\ReferenceViewScreen;
use App\Orchid\Screens\Role\RoleEditScreen;
use App\Orchid\Screens\Role\RoleListScreen;
use App\Orchid\Screens\User\UserEditScreen;
use App\Orchid\Screens\User\UserListScreen;
use App\Orchid\Screens\User\UserProfileScreen;
use App\Orchid\Screens\User\UserPasswordUpdateScreen;
use Illuminate\Support\Facades\Route;
use Tabuna\Breadcrumbs\Trail;
use App\Orchid\Screens\Candidate\CandidateListScreen;
use App\Orchid\Screens\Candidate\CandidateViewScreen;
use App\Orchid\Screens\Employer\EmployerEditScreen;

use App\Http\Middleware\ReferenceAccessControl;

/*
|--------------------------------------------------------------------------
| Dashboard Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the need "dashboard" middleware group. Now create something great!
|
*/

// Main
Route::screen('/main', PlatformScreen::class)
    ->middleware(['auth'])
    ->name('platform.main');

// Platform > Profile
Route::screen('profile', UserProfileScreen::class)
    ->middleware(['auth'])
    ->name('platform.profile')
    ->breadcrumbs(fn (Trail $trail) => $trail
        ->parent('platform.index')
        ->push(__('Profile'), route('platform.profile')));

// Platform > Profile -> update password
Route::screen('update-password', UserPasswordUpdateScreen::class)
    ->name('platform.update-password')
    ->breadcrumbs(fn (Trail $trail) => $trail
        ->parent('platform.index')
        ->push(__('Update password'), route('platform.update-password')));

// Platform > System > Users > User
Route::screen('users/{user}/edit', UserEditScreen::class)
    ->name('platform.systems.users.edit')
    ->breadcrumbs(fn (Trail $trail, $user) => $trail
        ->parent('platform.systems.users')
        ->push($user->name, route('platform.systems.users.edit', $user)));

// Platform > System > Users > Create
Route::screen('users/create', UserEditScreen::class)
    ->name('platform.systems.users.create')
    ->breadcrumbs(fn (Trail $trail) => $trail
        ->parent('platform.systems.users')
        ->push(__('Create'), route('platform.systems.users.create')));

// Platform > System > Users
Route::screen('users', UserListScreen::class)
    ->name('platform.systems.users')
    ->breadcrumbs(fn (Trail $trail) => $trail
        ->parent('platform.index')
        ->push(__('Users'), route('platform.systems.users')));

// Platform > System > Roles > Role
Route::screen('roles/{role}/edit', RoleEditScreen::class)
    ->name('platform.systems.roles.edit')
    ->breadcrumbs(fn (Trail $trail, $role) => $trail
        ->parent('platform.systems.roles')
        ->push($role->name, route('platform.systems.roles.edit', $role)));

// Platform > System > Roles > Create
Route::screen('roles/create', RoleEditScreen::class)
    ->name('platform.systems.roles.create')
    ->breadcrumbs(fn (Trail $trail) => $trail
        ->parent('platform.systems.roles')
        ->push(__('Create'), route('platform.systems.roles.create')));

// Platform > System > Roles
Route::screen('roles', RoleListScreen::class)
    ->name('platform.systems.roles')
    ->breadcrumbs(fn (Trail $trail) => $trail
        ->parent('platform.index')
        ->push(__('Roles'), route('platform.systems.roles')));

// Example...
Route::screen('example', ExampleScreen::class)
    ->name('platform.example')
    ->breadcrumbs(fn (Trail $trail) => $trail
        ->parent('platform.index')
        ->push('Example Screen'));

Route::screen('/examples/form/fields', ExampleFieldsScreen::class)->name('platform.example.fields');
Route::screen('/examples/form/advanced', ExampleFieldsAdvancedScreen::class)->name('platform.example.advanced');
Route::screen('/examples/form/editors', ExampleTextEditorsScreen::class)->name('platform.example.editors');
Route::screen('/examples/form/actions', ExampleActionsScreen::class)->name('platform.example.actions');

Route::screen('/examples/layouts', ExampleLayoutsScreen::class)->name('platform.example.layouts');
Route::screen('/examples/grid', ExampleGridScreen::class)->name('platform.example.grid');
Route::screen('/examples/charts', ExampleChartsScreen::class)->name('platform.example.charts');
Route::screen('/examples/cards', ExampleCardsScreen::class)->name('platform.example.cards');

//Route::screen('idea', Idea::class, 'platform.screens.idea');

// Platform > Candidates > List
Route::screen('candidates/list', CandidateListScreen::class)
    ->name('platform.candidates.list')
    ->breadcrumbs(fn (Trail $trail) => $trail
        ->parent('platform.index')
        ->push(__('Candidates'), route('platform.candidates.list'))
    );

// Platform > Candidates > Create
Route::screen('candidates/create', CandidateEditScreen::class)
    ->name('platform.candidates.create')
    ->breadcrumbs(fn (Trail $trail) => $trail
        ->parent('platform.candidates.list')
        ->push(__('Create candidate'), route('platform.candidates.create'))
    );

// Platform > Candidates > Edit
// By making the candidate parameter optional (using {candidate?}),
// you allow the route to handle both new and existing candidates.
Route::screen('candidates/{candidate?}/edit', CandidateEditScreen::class)
    ->name('platform.candidates.edit')
    ->breadcrumbs(fn (Trail $trail, $candidate) => $trail
        ->parent('platform.candidates.list')
        ->push(__('Edit candidate'), route('platform.candidates.edit'))
    );

// Platform > Candidates > View
Route::screen('candidates/{candidate?}/view', CandidateViewScreen::class)
    ->name('platform.candidates.view')
    ->breadcrumbs(fn (Trail $trail, $candidate) => $trail
        ->parent('platform.candidates.list')
        ->push(__('Candidate details'), route('platform.candidates.view'))
    );

// Platform > employers > List
Route::screen('employers/list', EmployerListScreen::class)
    ->name('platform.employers.list')
    ->breadcrumbs(fn (Trail $trail) => $trail
        ->parent('platform.index')
        ->push(__('Employers'), route('platform.employers.list'))
    );

// Platform > employers > Create
Route::screen('employers/create', EmployerEditScreen::class)
    ->name('platform.employers.create')
    ->breadcrumbs(fn (Trail $trail) => $trail
        ->parent('platform.employers.list')
        ->push(__('Create employer'), route('platform.employers.create'))
    );

// Platform > employers > Edit
Route::screen('employers/{employer?}/edit', EmployerEditScreen::class)
->name('platform.employers.edit')
    ->breadcrumbs(fn (Trail $trail, $employer) => $trail
        ->parent('platform.employers.list')
        ->push(__('Edit employer'), route('platform.employers.edit'))
    );

// Platform > jobs > List
Route::screen('jobs/list', JobListScreen::class)
    ->name('platform.jobs.list')
    ->breadcrumbs(fn (Trail $trail) => $trail
        ->parent('platform.index')
        ->push(__('Jobs'), route('platform.jobs.list'))
    );

// Platform > jobs > Create
Route::screen('jobs/create', JobEditScreen::class)
    ->name('platform.jobs.create')
    ->breadcrumbs(fn (Trail $trail) => $trail
        ->parent('platform.jobs.list')
        ->push(__('Create job'), route('platform.jobs.create'))
    );

// Platform > jobs > Edit
Route::screen('jobs/{job?}/edit', JobEditScreen::class)
    ->name('platform.jobs.edit')
    ->breadcrumbs(fn (Trail $trail, $employer) => $trail
        ->parent('platform.jobs.list')
        ->push(__('Edit job'), route('platform.jobs.edit'))
    );

// Platform > jobs > View
Route::screen('jobs/{job?}/view', JobViewScreen::class)
    ->name('platform.jobs.view')
    ->breadcrumbs(fn (Trail $trail, $job) => $trail
        ->parent('platform.jobs.list')
        ->push(__('Job details'), route('platform.jobs.view'))
    );

// Platform > job_applications > List
Route::screen('job_applications/list', JobApplicationListScreen::class)
    ->name('platform.job_applications.list')
    ->breadcrumbs(fn (Trail $trail) => $trail
        ->parent('platform.index')
        ->push(__('Job Applications'), route('platform.job_applications.list'))
    );

// Platform > job_applications > Create
Route::screen('job_application/{job}/create', JobApplicationEditScreen::class)
    ->name('platform.job_application.create')
    // ->breadcrumbs(fn (Trail $trail) => $trail
    //     ->parent('platform.job_applications.list')
    //     ->push(__('New job application'), route('platform.job_application.create'))
    // )
    ;

// Platform > job_applications > Edit
// Route::screen('job_application/{application}/edit', JobApplicationEditScreen::class)
//     ->name('platform.job_application.edit')
//     ->breadcrumbs(fn (Trail $trail, $employer) => $trail
//         ->parent('platform.job_applications.list')
//         ->push(__('Edit job'), route('platform.job_applications.edit'))
//     )
//     ;

// Platform > job_applications > Edit
Route::screen('job_application/{application?}/view', JobApplicationViewScreen::class)
    ->name('platform.job_application.view')
    ->breadcrumbs(fn (Trail $trail, $employer) => $trail
        ->parent('platform.job_applications.list')
        ->push(__('Application Details'), route('platform.job_application.view'))
    )
    ;

// Platform > References
Route::middleware([ReferenceAccessControl::class])->group(function () {
    // Platform > References > List
    Route::screen('references/list', ReferenceListScreen::class)
    ->name('platform.references.list')
    ->breadcrumbs(fn (Trail $trail) => $trail
        ->parent('platform.index')
        ->push(__('References'), route('platform.references.list'))
    );

    // Platform > References > My references
    Route::screen('references/my', ReferenceMyScreen::class)
        ->name('platform.references.my')
        ->breadcrumbs(fn (Trail $trail) => $trail
            ->parent('platform.index')
            ->push(__('References'), route('platform.references.my'))
        );

    // Platform > References > Create
    Route::screen('references/create', ReferenceEditScreen::class)
        ->name('platform.references.create')
        // ->breadcrumbs(fn (Trail $trail) => $trail
        //     ->parent('platform.job_applications.list')
        //     ->push(__('New job application'), route('platform.job_application.create'))
        // )
        ;

    // Platform > References > Create
    Route::screen('references/{candidate}/create', ReferenceEditScreen::class)
        ->name('platform.references.candidate.create')
        // ->breadcrumbs(fn (Trail $trail) => $trail
        //     ->parent('platform.job_applications.list')
        //     ->push(__('New job application'), route('platform.job_application.create'))
        // )
        ;

    // Platform > References > Feedback edit
    Route::screen('references/{reference?}/edit', ReferenceFeedbackEditScreen::class)
        ->name('platform.reference.feedback.edit')
        // ->breadcrumbs(fn (Trail $trail) => $trail
        //     ->parent('platform.job_applications.list')
        //     ->push(__('New job application'), route('platform.job_application.create'))
        // )
        ;

    Route::screen('references/{reference?}/view', ReferenceViewScreen::class)
        ->name('platform.reference.view')
        ->breadcrumbs(fn (Trail $trail, $employer) => $trail
            ->parent('platform.references.list')
            ->push(__('Reference Details'), route('platform.reference.view'))
        )
        ;
});



