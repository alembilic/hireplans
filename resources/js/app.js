import './bootstrap';
import Alpine from 'alpinejs';

// Don't start Alpine automatically - Livewire will handle this
window.Alpine = Alpine;

// Add navigate function to Alpine for Livewire compatibility
Alpine.navigate = function(url) {
    window.location.href = url;
};

// Let Livewire handle Alpine startup

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
