<div class="d-flex flex-column">
    <strong>{{ $meeting->title }}</strong>
    @if($meeting->description)
        <small class="text-muted">{{ Str::limit($meeting->description, 50) }}</small>
    @endif
    @if($meeting->type === 'video' && $meeting->meeting_link)
        <small>
            <a href="{{ $meeting->meeting_link }}" target="_blank" class="text-primary">
                <i class="icon-link"></i> Join Meeting
            </a>
        </small>
    @endif
</div> 