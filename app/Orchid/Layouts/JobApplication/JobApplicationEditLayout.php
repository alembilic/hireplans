<?php

namespace App\Orchid\Layouts\JobApplication;

use Orchid\Screen\Field;
use Orchid\Screen\Layouts\Rows;
use Orchid\Screen\Fields\TextArea;
use Illuminate\Support\Facades\Auth;
use Orchid\Screen\Fields\Upload;
use Orchid\Screen\Fields\Label;
use Orchid\Screen\Fields\Select;
use App\Helpers\HelperFunc;
use Orchid\Screen\Fields\Group;
use Orchid\Support\Facades\Layout;
use App\Orchid\Fields\HtmlField;

class JobApplicationEditLayout extends Rows
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
        $fields = [
            Label::make('static_field')
                ->title('Job title')
                ->horizontal()
                ->value($this->query['job']->title)
                ->hr(),

            // Upload::make('job_application.cv2')
            //     ->title('CV')
            //     ->required()
            //     ->horizontal()
            //     ->maxFiles(1)
            //     ->help('Use an existing CV or upload a new one.')
            //     ->value($this->query['cv_id'])
            //     ->hr(),

            Select::make('job_application.cv')
                ->options(HelperFunc::getUserCvs())
                ->title('CV')
                ->required()
                // ->empty('Select a CV...')
                ->horizontal()
                ->hr(),

            Upload::make('job_application.cover_letter')
                ->title('Cover letter')
                ->horizontal()
                ->maxFiles(1)
                ->help('Upload a cover letter if you have one.')
                ->value($this->query['cover_letter']),
        ];

        if (auth::user()->hasAccess('platform.systems.users')) {
            // $fields[] = SimpleMDE::make('job_application.notes')
            //                 ->title(__('Admin Notes'))
            //                 // ->popover(__('Notes'))
            //                 ->help('These notes are visible to the admins only. They are not visible to the candidate.')
            //                 ->horizontal();
            $fields[] = TextArea::make('job_application.notes')
                            ->title(__('Admin Notes'))
                            // ->popover(__('Notes'))
                            ->rows(10)
                            ->help('These notes are visible to the admins only. They are not visible to the candidate.')
                            ->horizontal();
        }

        return $fields;
    }
}
