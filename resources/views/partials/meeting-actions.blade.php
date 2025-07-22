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

    @php
        $editButton = \Orchid\Screen\Actions\ModalToggle::make('')
            ->icon('pencil-square')
            ->modal('editMeetingModal')
            ->method('updateMeeting')
            ->asyncParameters(['meeting' => $meeting->id])
            ->class('btn btn-info rounded-circle d-flex align-items-center justify-content-center p-0')
            ->style('width:2.2rem;height:2.2rem')
            ->title('');
        echo $editButton;
    @endphp

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

 