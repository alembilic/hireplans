<?php

namespace App\Orchid\Layouts\Candidate;

use Orchid\Screen\Fields\Upload;
use Orchid\Screen\Field;
use Orchid\Screen\Layouts\Rows;

class CandidateDocumentImportLayout extends Rows
{
    /**
     * Used to create the title of a group of form elements.
     *
     * @var string|null
     */
    protected $title = 'Document Import';

    /**
     * Get the fields elements to be displayed.
     *
     * @return Field[]
     */
    protected function fields(): iterable
    {
        return [
            Upload::make('document_import')
                ->title('Import from CV/Resume Document')
                ->help('Upload a PDF or DOCX file containing candidate CV/resume data. After uploading, use the "Parse Document" button in the command bar to automatically extract and populate form fields. Supported formats: PDF, DOCX, DOC.')
                ->acceptedFiles('.pdf,.docx,.doc')
                ->maxFiles(1)
                ->horizontal()
                ->groups('document-import'),
        ];
    }
}
