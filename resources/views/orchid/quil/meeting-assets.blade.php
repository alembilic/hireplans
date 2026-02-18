<div class="bg-white rounded shadow-sm p-4 mb-3">
    <h4 class="fw-bold mb-3">
        <i class="bi bi-collection me-2"></i>Meeting Assets
    </h4>
    
    <div class="row g-3">
        <!-- Transcription -->
        <div class="col-md-4">
            <div class="card h-100 @if($quilMeeting->transcription_url) border-success @else border-secondary @endif">
                <div class="card-body text-center">
                    <i class="bi bi-file-text text-@if($quilMeeting->transcription_url)success @else muted @endif" style="font-size: 3rem;"></i>
                    <h5 class="fw-bold mt-3">Transcription</h5>
                    @if($quilMeeting->transcription_url)
                        <p class="text-muted small mb-3">Full text transcript available</p>
                        <a href="{{ $quilMeeting->transcription_url }}" 
                           target="_blank" 
                           class="btn btn-success btn-sm">
                            <i class="bi bi-download me-1"></i>View Transcript
                        </a>
                    @else
                        <p class="text-muted small mb-0">Not available</p>
                    @endif
                </div>
            </div>
        </div>

        <!-- Recording -->
        <div class="col-md-4">
            <div class="card h-100 @if($quilMeeting->recording_url) border-primary @else border-secondary @endif">
                <div class="card-body text-center">
                    <i class="bi bi-camera-video text-@if($quilMeeting->recording_url)primary @else muted @endif" style="font-size: 3rem;"></i>
                    <h5 class="fw-bold mt-3">Recording</h5>
                    @if($quilMeeting->recording_url)
                        <p class="text-muted small mb-3">Video/audio recording available</p>
                        <a href="{{ $quilMeeting->recording_url }}" 
                           target="_blank" 
                           class="btn btn-primary btn-sm">
                            <i class="bi bi-play-circle me-1"></i>Play Recording
                        </a>
                    @else
                        <p class="text-muted small mb-0">Not available</p>
                    @endif
                </div>
            </div>
        </div>

        <!-- Action Items -->
        <div class="col-md-4">
            <div class="card h-100 @if($quilMeeting->action_items_url) border-warning @else border-secondary @endif">
                <div class="card-body text-center">
                    <i class="bi bi-check2-square text-@if($quilMeeting->action_items_url)warning @else muted @endif" style="font-size: 3rem;"></i>
                    <h5 class="fw-bold mt-3">Action Items</h5>
                    @if($quilMeeting->action_items_url)
                        <p class="text-muted small mb-3">Action items list available</p>
                        <a href="{{ $quilMeeting->action_items_url }}" 
                           target="_blank" 
                           class="btn btn-warning btn-sm">
                            <i class="bi bi-list-check me-1"></i>View Actions
                        </a>
                    @else
                        <p class="text-muted small mb-0">Not available</p>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <!-- Follow-up Materials -->
    @if($quilMeeting->follow_up_materials && is_array($quilMeeting->follow_up_materials) && count($quilMeeting->follow_up_materials) > 0)
        <div class="mt-4">
            <h5 class="fw-bold mb-3">
                <i class="bi bi-file-earmark-arrow-down me-2"></i>Follow-up Materials
            </h5>
            <div class="list-group">
                @foreach($quilMeeting->follow_up_materials as $material)
                    <div class="list-group-item">
                        <div class="d-flex justify-content-between align-items-center">
                            <div>
                                <h6 class="fw-bold mb-1">{{ $material['name'] ?? 'Document' }}</h6>
                            </div>
                            <div class="btn-group btn-group-sm" role="group">
                                @if(isset($material['txtUrl']))
                                    <a href="{{ $material['txtUrl'] }}" 
                                       target="_blank" 
                                       class="btn btn-outline-primary">
                                        <i class="bi bi-file-text me-1"></i>TXT
                                    </a>
                                @endif
                                @if(isset($material['pdfUrl']))
                                    <a href="{{ $material['pdfUrl'] }}" 
                                       target="_blank" 
                                       class="btn btn-outline-danger">
                                        <i class="bi bi-file-pdf me-1"></i>PDF
                                    </a>
                                @endif
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    @endif
</div>


