<style>
    .note-content h1, .note-content h2, .note-content h3, 
    .note-content h4, .note-content h5, .note-content h6 {
        margin-top: 1rem;
        margin-bottom: 0.75rem;
        font-weight: 600;
        color: #0A0A0A;
    }
    .note-content h3 { font-size: 1.25rem; }
    .note-content h4 { font-size: 1.1rem; }
    .note-content h5 { font-size: 1rem; }
    
    .note-content ul, .note-content ol {
        margin-left: 1.5rem;
        margin-bottom: 1rem;
    }
    .note-content li {
        margin-bottom: 0.5rem;
        line-height: 1.6;
    }
    .note-content p {
        margin-bottom: 0.75rem;
        line-height: 1.7;
    }
    .note-content strong {
        font-weight: 600;
        color: #0A0A0A;
    }
    .note-content br {
        margin-bottom: 0.5rem;
    }
    
    .summary-content h1, .summary-content h2, .summary-content h3, 
    .summary-content h4, .summary-content h5, .summary-content h6 {
        margin-top: 1rem;
        margin-bottom: 0.75rem;
        font-weight: 600;
    }
    .summary-content ul, .summary-content ol {
        margin-left: 1.5rem;
    }
    .summary-content li {
        margin-bottom: 0.5rem;
    }
    .summary-content p {
        line-height: 1.7;
    }
</style>

<div class="bg-white rounded shadow-sm p-4 mb-3">
    <h4 class="fw-bold mb-3">
        <i class="bi bi-journal-text me-2"></i>Meeting Summary & Notes
    </h4>
    
    @if($quilMeeting->getSummary())
        <div class="alert alert-info mb-4">
            <h5 class="fw-bold mb-3">
                <i class="bi bi-file-text me-2"></i>Quick Summary
            </h5>
            <div class="summary-content">
                {!! $quilMeeting->getSummary() !!}
            </div>
        </div>
    @endif

    @if($quilMeeting->database_notes && is_array($quilMeeting->database_notes) && count($quilMeeting->database_notes) > 0)
        <div class="mt-3">
            <h5 class="fw-bold mb-3">All Notes</h5>
            @foreach($quilMeeting->database_notes as $note)
                <div class="card mb-3">
                    <div class="card-body">
                        <h6 class="fw-bold text-primary mb-3">
                            <i class="bi bi-journal-bookmark me-2"></i>{{ $note['name'] ?? 'Note' }}
                        </h6>
                        <div class="note-content">
                            {!! $note['note'] ?? '<p class="text-muted">No content</p>' !!}
                        </div>
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


