<div class="row">
    <div class="col-md-6">
        <div class="card">
            <div class="card-header">
                <h5 class="card-title mb-0">Campaign Information</h5>
            </div>
            <div class="card-body">
                <div class="mb-3">
                    <strong>Name:</strong> {{ $campaign->name }}
                </div>
                <div class="mb-3">
                    <strong>Subject:</strong> {{ $campaign->title }}
                </div>
                <div class="mb-3">
                    <strong>Status:</strong> 
                    @if($campaign->status === 'draft')
                        <span class="badge bg-warning text-dark">Draft</span>
                    @elseif($campaign->status === 'sent')
                        <span class="badge bg-success">Sent</span>
                    @elseif($campaign->status === 'scheduled')
                        <span class="badge bg-info">Scheduled</span>
                    @endif
                </div>
                <div class="mb-3">
                    <strong>Created:</strong> {{ $campaign->created_at->format('M d, Y H:i') }}
                </div>
                @if($campaign->sent_at)
                    <div class="mb-3">
                        <strong>Sent:</strong> {{ $campaign->sent_at->format('M d, Y H:i') }}
                    </div>
                @endif
                <div class="mb-3">
                    <strong>Created By:</strong> {{ $campaign->creator->name }}
                </div>
            </div>
        </div>
    </div>
    
    <div class="col-md-6">
        <div class="card">
            <div class="card-header">
                <h5 class="card-title mb-0">Campaign Statistics</h5>
            </div>
            <div class="card-body">
                <div class="mb-3">
                    <strong>Total Users:</strong> 
                    <span class="badge bg-secondary fs-6">{{ $campaign->user_count }}</span>
                </div>
                @if($campaign->status === 'sent')
                    <div class="mb-3">
                        <strong>Successfully Sent:</strong> 
                        <span class="badge bg-success fs-6">{{ $campaign->sent_count }}</span>
                    </div>
                    <div class="mb-3">
                        <strong>Failed:</strong> 
                        <span class="badge bg-danger fs-6">{{ $campaign->failed_count }}</span>
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>

<div class="row mt-4">
    <div class="col-12">
        <div class="card">
            <div class="card-header">
                <h5 class="card-title mb-0">Email Content</h5>
            </div>
            <div class="card-body">
                <div class="border rounded p-3 bg-light">
                    {!! nl2br(e($campaign->email_content)) !!}
                </div>
            </div>
        </div>
    </div>
</div> 