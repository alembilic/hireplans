@if($has_searched)
<div class="bg-light p-3 rounded mb-3">
    <div class="row align-items-center">
        <div class="col-md-6">
            @if($total_count > 0)
                <h5 class="text-success mb-0">
                    <i class="bi bi-check-circle me-2"></i>
                    Found {{ $total_count }} candidate{{ $total_count > 1 ? 's' : '' }}
                </h5>
            @else
                <h5 class="text-warning mb-0">
                    <i class="bi bi-exclamation-triangle me-2"></i>
                    No candidates found
                </h5>
            @endif
        </div>
        <div class="col-md-6 text-md-end">
            @if($search_time)
                <small class="text-muted">
                    <i class="bi bi-clock me-1"></i>
                    Search completed in {{ $search_time }}
                </small>
            @endif
        </div>
    </div>
    
    @if($total_count === 0)
        <div class="mt-3">
            <p class="text-muted mb-2">Try these suggestions:</p>
            <ul class="text-muted small mb-0">
                <li>Use more general search terms</li>
                <li>Check your spelling</li>
                <li>Try searching without location filters</li>
                <li>Use skills-based keywords instead of specific job titles</li>
            </ul>
        </div>
    @endif
</div>
@else
<div class="bg-white p-4 rounded mb-3 text-center">
    <i class="bi bi-linkedin text-primary" style="font-size: 3rem;"></i>
    <h4 class="mt-3 mb-2">Search LinkedIn Candidates</h4>
    <p class="text-muted mb-0">
        Enter search criteria above and click "Search LinkedIn" to find potential candidates for your positions.
    </p>
    <div class="mt-3">
        <small class="text-muted">
            <strong>Search Tips:</strong> Use job titles, skills, or company names. Combine multiple criteria for better results.
        </small>
    </div>
</div>
@endif
