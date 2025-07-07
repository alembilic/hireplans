<?php
namespace App\Orchid\Fields;

use Orchid\Screen\Field;
use Orchid\Screen\Fields\Upload;

class CustomUpload extends Upload
{
    public function __construct()
    {
        parent::__construct();

        // Custom JavaScript to handle delete button
        // $this->addBeforeRender(function () {
        //     return "
        //         document.addEventListener('DOMContentLoaded', function () {
        //             document.querySelectorAll('.btn-remove').forEach(function (button) {
        //                 button.addEventListener('click', function (event) {
        //                     event.preventDefault();
        //                     console.log('Remove button clicked');
        //                     var fileId = this.getAttribute('data-file-id');
        //                     var input = document.createElement('input');
        //                     input.type = 'hidden';
        //                     input.name = 'delete_files[]';
        //                     input.value = fileId;
        //                     document.querySelector('form').appendChild(input);
        //                     this.closest('.file-preview').style.display = 'none';
        //                 });
        //             });
        //         });
        //     ";
        // });

        // Custom JavaScript to handle delete button
        $this->addBeforeRender(function () {
            return view('partials.custom-upload')->render();
        });
    }
}
