{{-- <script>
    console.log('loading custom-upload.blade.php');
    function attachCustomEventListeners() {
        document.querySelectorAll('.btn-remove').forEach(function(button) {
            console.log(button);
            if (!button.dataset.listenerAttached) {
                console.log('!listenerAttached');
                button.dataset.listenerAttached = true;
                button.addEventListener('click', function(event) {
                    event.preventDefault();
                    console.log('Remove button clicked');
                    const fileId = this.getAttribute('data-file-id');
                    const form = this.closest('form');
                    const input = document.createElement('input');
                    input.type = 'hidden';
                    input.name = 'delete_files[]';
                    input.value = fileId;
                    form.appendChild(input);
                    console.log('File marked for deletion, but not removed yet:', fileId);
                    // Instead of hiding, you might want to visually indicate the file is marked for deletion
                    this.closest('.file-preview').classList.add('marked-for-deletion');
                });
            } else {
                console.log('listenerAlreadyAttached');
            }
        });
    }
    document.addEventListener('DOMContentLoaded', function() {
        attachCustomEventListeners();
        const observer = new MutationObserver(function(mutations) {
            mutations.forEach(function(mutation) {
                if (mutation.addedNodes.length > 0) {
                    attachCustomEventListeners();
                }
            });
        });
        // Observe for changes in the document
        observer.observe(document.body, { childList: true, subtree: true });
    });
</script> --}}
{{-- <script>
    console.log('loading custom-upload.blade.php');
    document.addEventListener('DOMContentLoaded', function() {
        // Use event delegation to catch click events on dynamically added elements
        document.body.addEventListener('click', function(event) {
            console.log('click event detected');
            const target = event.target;
            // Check if the clicked element is the "X" button for file removal
            if (target.classList.contains('btn-remove')) {
                console.log('Remove button clicked');
                event.preventDefault(); // Prevent Orchid's default file removal action
                event.stopPropagation(); // Stop the event from propagating further

                // Optionally, visually indicate that the file is marked for deletion
                const filePreview = target.closest('.file-preview');
                if (filePreview) {
                    filePreview.style.opacity = '0.5'; // Example of visual indication
                }

                // Add a hidden input to indicate this file should be deleted upon form submission
                const input = document.createElement('input');
                input.type = 'hidden';
                input.name = 'files_to_delete[]'; // Use an appropriate name for your input
                input.value = 'candidate.cv'; // Use a unique identifier for the file
                document.forms[0].appendChild(input); // Assuming you have a form; adjust as needed
            }
        });
    });
</script> --}}
{{-- <script>
    console.log('loading custom-upload.blade.php');
    document.addEventListener('DOMContentLoaded', function() {
        // Use event delegation to catch click events on dynamically added elements
        document.body.addEventListener('click', function(event) {
            console.log('click event detected');
            const target = event.target;
            // Check if the clicked element is the "X" button for file removal
            if (target.classList.contains('btn-remove')) {
                console.log('Remove button clicked');
                event.preventDefault(); // Prevent Orchid's default file removal action
                event.stopPropagation(); // Stop the event from propagating further

                // Optionally, visually indicate that the file is marked for deletion
                const filePreview = target.closest('.file-preview');
                if (filePreview) {
                    filePreview.style.opacity = '0.5'; // Example of visual indication
                }

                // Add a hidden input to indicate this file should be deleted upon form submission
                const input = document.createElement('input');
                input.type = 'hidden';
                input.name = 'files_to_delete[]'; // Use an appropriate name for your input
                input.value = 'candidate.cv'; // Use a unique identifier for the file
                document.forms[0].appendChild(input); // Assuming you have a form; adjust as needed
            }
        });
    });
</script> --}}
