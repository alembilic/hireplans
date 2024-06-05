<?php

namespace App\Orchid\Screens\Job;

use App\Orchid\Layouts\Job\JobNavItemslayout;
use Orchid\Screen\Sight;
use Orchid\Support\Facades\Layout;
use Orchid\Support\Color;
use Orchid\Screen\Fields\Group;
use Orchid\Screen\Actions\Button;
use Orchid\Screen\Screen;
use App\Models\Job;
use App\Helpers\HelperFunc;
use \Illuminate\Support\Str;

class JobViewScreen extends Screen
{

    /**
     * @var Job
     */
    public $job;

    /**
     * Fetch data to be displayed on the screen.
     *
     * @return array
     */
    public function query(Job $job): iterable
    {
        $job->load(['employer']); // Eager load the employer relationship

        return [
            'job' => $job,
        ];
    }

    /**
     * The name of the screen displayed in the header.
     *
     * @return string|null
     */
    public function name(): ?string
    {
        return 'Job details';
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
            Layout::block([JobNavItemslayout::class])->vertical(),
            Layout::legend(
                'job',
                [
                    Sight::make('title', 'Job title'),
                    Sight::make('employer.name', 'Employer'),
                    Sight::make('location', 'Location'),
                    Sight::make('salary', 'Salary'),
                    Sight::make('job_type', 'Job Type')->render(fn(Job $job) => HelperFunc::getJobTypes()[$job->job_type] ?? $job->job_type),
                    Sight::make('category', 'Category')->render(fn(Job $job) => HelperFunc::getJobCategories()[$job->category] ?? $job->category),
                    Sight::make('experience_level', 'Experience Level')->render(fn(Job $job) => HelperFunc::getExperienceLevels()[$job->experience_level] ?? $job->experience_level),
                    Sight::make('application_deadline', 'Application Deadline')->render(fn(Job $job) => $job->application_deadline
                        ? \Carbon\Carbon::parse($job->application_deadline)->format('d/m/Y') : ''),
                    Sight::make('is_active', 'Is Active')->render(fn(Job $job) => $job->is_active ? 'Yes' : 'No'),
                    Sight::make('details', 'Details')->render(fn(Job $job) => nl2br(Str::markdown($job->details))),
                    // Sight::make('details', 'Details')->render(fn(Job $job) => Str::markdown($job->details)),
                    Sight::make('')
                        ->render(function () {
                            return Group::make([
                                Button::make('Edit')
                                    ->type(Color::INFO)
                                    ->icon('bs.pencil')
                                    ->method('redirectToEditScreen'),
                                Button::make('Close')
                                    ->type(Color::DEFAULT)
                                    ->icon('bs.x-circle')
                                    ->method('redirectToListScreen'),
                            ])->autoWidth()->alignCenter();
                        }),
                ]
            )->title('Job Details: '. $this->job->title),
        ];
    }

    public function redirectToEditScreen($job)
    {
        return redirect()->route('platform.jobs.edit', $job);
    }

    public function redirectToListScreen()
    {
        return redirect()->route('platform.jobs.list');
    }

}
