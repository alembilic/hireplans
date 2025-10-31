<?php

namespace App\Orchid\Layouts\Candidate;

use Orchid\Screen\Field;
use Orchid\Screen\Layouts\Rows;
use Orchid\Screen\Fields\Upload;
use App\Orchid\Fields\CustomUpload;
use Orchid\Screen\Fields\Input;
use App\Orchid\Fields\HtmlField;
use Orchid\Screen\Fields\Label;

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
        // dd($this->query['cv_links']);
        return [
            Label::make('cv_parser_info')
                ->title('CV Parser Information')
                ->value('💡 Tip: After uploading your CV, click the "Parse CV" button in the command bar above to automatically extract and populate your profile information from the document. Supports PDF, DOCX, and DOC formats with AI-powered data extraction.')
                ->help('The parser will intelligently extract personal details, work experience, skills, and contact information from your CV without overwriting existing data.'),

            HtmlField::make('custom_html.cv-links')
                ->label('Existing CVs')
                ->horizontal()
                ->html(function() {
                    $html = '<div class="parent-container space-y-2">';
                    if ($this->query['cv_links']) {
                        foreach ($this->query['cv_links'] as $id => $link) {
                            $html .= "
                                <div class='file-link cv-link flex items-center justify-between p-2 border rounded'>
                                    <span class='text-blue-600'>{$link}</span>
                                    <button class='btn-remove bg-red-500 text-white px-2 py-1 rounded hover:bg-red-700 transition' data-file-id='{$id}'
                                        onclick=\"deleteCvFile('{$id}', this)\">
                                        Delete
                                    </button>
                                </div>";
                        }
                    }
                    $html .= '</div>';
                    return $html;
                }),

            Upload::make('candidate.cv')
                ->title('Upload CV')
                ->horizontal()
                ->maxFiles(1)
                ->value($this->query['cv']),

            // CustomUpload::make('candidate.cv')
            //     ->title('Upload CV')
            //     ->horizontal()
            //     ->maxFiles(1)
            //     ->value($this->query['cv'])
            //     ->hr(),

            HtmlField::make('custom_html.other-documents-links')
                ->label('Existing Other Documents')
                ->horizontal()
                ->html(function() {
                    $html = '<div class="parent-container space-y-2">';
                    if ($this->query['other_documents_links']) {
                        foreach ($this->query['other_documents_links'] as $id => $link) {
                            $html .= "
                                <div class='file-link other-documents-link flex items-center justify-between p-2 border rounded'>
                                    <span class='text-blue-600'>{$link}</span>
                                    <button class='btn-remove bg-red-500 text-white px-2 py-1 rounded hover:bg-red-700 transition' data-file-id='{$id}'>
                                        Delete
                                    </button>
                                </div>";
                        }
                    }
                    $html .= '</div>';
                    return $html;
                }),

            Upload::make('candidate.other-documents')
                ->title('Upload Other Documents')
                ->horizontal()
                ->value($this->query['other_documents']),
        ];
    }
}
