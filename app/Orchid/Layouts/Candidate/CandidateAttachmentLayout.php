<?php

namespace App\Orchid\Layouts\Candidate;

use Orchid\Screen\Field;
use Orchid\Screen\Layouts\Rows;
use Orchid\Screen\Fields\Upload;

class CandidateAttachmentLayout extends Rows
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
            Upload::make('candidate.cv')
                ->title('Upload CV')
                ->horizontal()
                ->maxFiles(1)
                ->value($this->query['cv']),

            Upload::make('candidate.other-documents')
                ->title('Upload Other Documents')
                ->horizontal()
                ->value($this->query['other_documents']),
        ];
    }
}
