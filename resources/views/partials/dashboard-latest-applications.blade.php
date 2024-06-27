{{-- <div class="row bg-white shadow-sm m-0 p-md-4 p-3 border-top rounded xxxg-md-5 mb-5">
    <h1>Latest Applications</h1>
</div> --}}
<div class="card row bg-white shadow-sm m-0 p-md-4 p-3 border-top rounded xxxg-md-5 mb-5 h-100">
    <div class="card-body">
        <h2 class="card-title h5 mb-2">{{ $title }}</h2>
        <ul class="list-group list-group-flush">
            @foreach($applications as $application)
                <li class="list-group-item">
                    <a href="{{ route('platform.job_application.view', $application->id) }}">
                        {{ $application->job->title }}
                    </a> by {{ $application->candidate->user->name }}
                    <small class="text-muted block">{{ $application->created_at->diffForHumans() }}</small>
                </li>
            @endforeach
        </ul>
        <div class="card-footer">
            <a href="{{ $role == 'admin' ? route('platform.job_applications.list') : route('platform.job_applications.my') }}" class="btn btn-default w-fit">View All Applications</a>
        </div>
    </div>
</div>
