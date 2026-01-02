<div class="bg-white rounded shadow-sm p-4 mb-3">
    <h4 class="fw-bold mb-3">
        <i class="bi bi-journal-text me-2"></i>Meeting Summary & Notes
    </h4>
    
    @if($quilMeeting->getSummary())
        <div class="alert alert-info">
            <h5 class="fw-bold mb-2">Summary</h5>
            <p class="mb-0" style="white-space: pre-wrap;">{{ $quilMeeting->getSummary() }}</p>
        </div>
    @endif

    @if($quilMeeting->database_notes && count($quilMeeting->database_notes) > 0)
        <div class="mt-3">
            <h5 class="fw-bold mb-3">All Notes</h5>
            @foreach($quilMeeting->database_notes as $note)
                <div class="card mb-2">
                    <div class="card-body">
                        <h6 class="fw-bold text-primary mb-2">{{ $note['name'] ?? 'Note' }}</h6>
                        <p class="mb-0" style="white-space: pre-wrap;">{{ $note['note'] ?? '' }}</p>
                    </div>
                </div>
            @endforeach
        </div>
    @else
        <div class="text-center text-muted py-4">
            <i class="bi bi-journal-x" style="font-size: 3rem;"></i>
            <p class="mt-2">No notes available for this meeting</p>
        </div>
    @endif
</div>

