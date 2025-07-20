<div class="d-flex justify-content-center gap-2">
    @if($meeting->status === 'scheduled')
        <form method="POST" action="{{ route('platform.schedule.update-status') }}" style="display: inline;">
            @csrf
            <input type="hidden" name="id" value="{{ $meeting->id }}">
            <input type="hidden" name="status" value="completed">
            <button type="submit"
                class="btn btn-success rounded-circle d-flex align-items-center justify-content-center p-0"
                style="width:2.2rem;height:2.2rem"
                title="Mark as Completed">
                <i class="bi bi-check-circle" style="font-size:1rem"></i>
            </button>
        </form>
        <form method="POST" action="{{ route('platform.schedule.update-status') }}" style="display: inline;">
            @csrf
            <input type="hidden" name="id" value="{{ $meeting->id }}">
            <input type="hidden" name="status" value="cancelled">
            <button type="submit"
                class="btn btn-warning rounded-circle d-flex align-items-center justify-content-center p-0"
                style="width:2.2rem;height:2.2rem"
                title="Cancel Meeting">
                <i class="bi bi-x-circle" style="font-size:1rem"></i>
            </button>
        </form>
    @endif

    <button type="button"
        class="btn btn-info rounded-circle d-flex align-items-center justify-content-center p-0"
        style="width:2.2rem;height:2.2rem"
        onclick="editMeeting({{ $meeting->id }})"
        title="Edit Meeting">
        <i class="bi bi-pencil-square" style="font-size:1rem"></i>
    </button>

    <form method="POST" action="{{ route('platform.schedule.delete-meeting') }}" style="display: inline;">
        @csrf
        <input type="hidden" name="id" value="{{ $meeting->id }}">
        <button type="submit"
            class="btn btn-danger rounded-circle d-flex align-items-center justify-content-center p-0"
            style="width:2.2rem;height:2.2rem"
            title="Delete Meeting">
            <i class="bi bi-trash-fill" style="font-size:1rem"></i>
        </button>
    </form>
</div>

<script>
function editMeeting(meetingId) {
    // Load meeting data and open edit modal
    fetch(`{{ route('platform.schedule.get-meeting', '') }}/${meetingId}`)
        .then(response => {
            if (!response.ok) {
                throw new Error('Failed to load meeting data');
            }
            return response.json();
        })
        .then(meeting => {
            // Populate the edit modal with meeting data
            const modal = document.getElementById('editMeetingModal');
            if (!modal) {
                console.error('Edit modal not found');
                return;
            }
            
            // Set form values
            modal.querySelector('input[name="meeting[id]"]').value = meeting.id;
            modal.querySelector('input[name="meeting[title]"]').value = meeting.title;
            modal.querySelector('select[name="meeting[type]"]').value = meeting.type;
            modal.querySelector('select[name="meeting[duration_minutes]"]').value = meeting.duration_minutes;
            modal.querySelector('input[name="meeting[scheduled_at]"]').value = meeting.scheduled_at;
            modal.querySelector('select[name="meeting[candidate_id]"]').value = meeting.candidate_id;
            modal.querySelector('select[name="meeting[job_id]"]').value = meeting.job_id || '';
            modal.querySelector('input[name="meeting[meeting_link]"]').value = meeting.meeting_link || '';
            modal.querySelector('input[name="meeting[phone_number]"]').value = meeting.phone_number || '';
            modal.querySelector('textarea[name="meeting[description]"]').value = meeting.description || '';
            
            // Open the edit modal
            const editModal = new bootstrap.Modal(modal);
            editModal.show();
        })
        .catch(error => {
            console.error('Error loading meeting data:', error);
            // You could show a toast notification here instead of an alert
        });
}
</script> 