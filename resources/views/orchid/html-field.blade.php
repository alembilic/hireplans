<div class="form-group row row-cols-sm-2 align-items-baseline custom-upload-field">
    @if(isset($label))
        <label class="col-sm-3 text-wrap form-label">{{ $label }}</label>
    @endif
    <div>
        {!! $html !!}
    </div>

    {{-- <script>
        document.addEventListener('DOMContentLoaded', function () {
            document.querySelectorAll('.btn-remove').forEach(function (button) {
                button.addEventListener('click', function (event) {
                    event.preventDefault();
                    var fileId = this.getAttribute('data-file-id');
                    // Send a request to your backend to delete the file
                    // Example: axios.post('/delete-file', { fileId: fileId })
                    // On success:
                    this.closest('div').remove();
                });
            });
        });
    </script> --}}

    <script>
        document.addEventListener('DOMContentLoaded', function () {
            // Select all delete buttons
            document.querySelectorAll('.file-link .btn-remove').forEach(function(button) {
                // Check if the event listener has already been attached
                if (!button.hasAttribute('data-event-attached')) {
                    button.addEventListener('click', function(event) {
                        event.preventDefault();
                        event.stopPropagation(); // Prevent the event from bubbling up

                        var fileId = this.getAttribute('data-file-id');
                        // Ensure CSRF token is included
                        const csrfToken = document.querySelector('meta[name="csrf_token"]').getAttribute('content');

                        // Make an AJAX request to delete the file
                        fetch(`/portal/systems/files/${fileId}`, {
                            method: 'DELETE',
                            headers: {
                                'Content-Type': 'application/json',
                                'X-CSRF-Token': csrfToken // CSRF protection
                            }
                        })
                        .then(response => {
                            if (response.ok) {
                                // Try to parse the JSON response
                                return response.json().catch(() => {
                                    // If parsing fails, assume the request was successful
                                    return { success: true };
                                });
                            }
                            throw new Error('Network response was not ok.');
                        })
                        .then(data => {
                            if (data.success) {
                                // Remove the link element
                                this.closest('div').remove(); // Adjust this selector as needed
                            } else {
                                alert('Failed to delete the file.');
                            }
                        })
                        .catch(error => {
                            console.error('Error:', error);
                            alert('An error occurred while deleting the file.');
                        });
                    });

                    // Mark the button as having an event listener attached
                    button.setAttribute('data-event-attached', 'true');
                }
            });
        });
    </script>

</div>
