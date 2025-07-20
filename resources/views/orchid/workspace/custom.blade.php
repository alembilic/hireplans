@extends('platform::app')

@section('body')

    <div class="container-xl custom-workspace-container p-0 h-100">
        <div class="workspace workspace-limit pt-0 pt-md-0 mb-4 mb-md-0 d-flex flex-column h-100">

            @yield('workspace')

            @includeFirst([config('platform.template.footer'), 'platform::footer'])
        </div>
    </div>

@endsection
