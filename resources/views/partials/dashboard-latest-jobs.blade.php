{{-- <div class="row bg-white shadow-sm m-0 p-md-4 p-3 border-top rounded xxxg-md-5 mb-5">
    <h1>Latest Jobs</h1>
</div> --}}
<div class="card row bg-white shadow-sm m-0 p-md-4 p-3 border-top rounded xxxg-md-5 mb-5 h-100">
    <div class="card-body">
        <h2 class="card-title h5 mb-2">{{ $title }}</h2>
        <ul class="list-group list-group-flush">
            @foreach($jobs as $job)
                <li class="list-group-item">
                    <a href="{{ route('jobs.details', $job->id) }}">{{ $job->title }}</a>
                    <small class="text-muted block">{{ $job->created_at->diffForHumans() }}</small>
                </li>
            @endforeach
        </ul>
        <div class="card-footer">
            <a href="{{ route('jobs.listings') }}" class="btn btn-default">View All Jobs</a>
        </div>
    </div>
</div>
