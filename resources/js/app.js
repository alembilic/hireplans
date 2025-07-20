import './bootstrap';

// $(document).ready(function() {
//     $('div[data-upload-name="cv"]').closest('.form-group').addClass('custom-upload-field');
// });
document.addEventListener('DOMContentLoaded', function () {
    document.querySelectorAll('.form-group').forEach(function (element) {
        if (element.querySelector('div[data-upload-name="candidate[cv]"]')
            || element.querySelector('div[data-upload-name="candidate[other-documents]"]')) {
            element.classList.add('custom-upload-field');
        }
    });
});
