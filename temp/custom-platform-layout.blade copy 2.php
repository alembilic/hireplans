@extends('platform::app')

@section('title', (string) __($name))
@section('description', (string) __($description))
@section('controller', 'base')

@section('body')
    <div class="container-xl custom-workspace-container p-0 h-100">
        <div class="workspace workspace-limit pt-0 pt-md-0 mb-4 mb-md-0 d-flex flex-column h-100">
            <livewire:layout.navigation />

            <div class="container-fluid max-w-7xl">
                @if(Breadcrumbs::has())
                    <nav aria-label="breadcrumb">
                        <ol class="breadcrumb px-4 mb-2">
                            <x-tabuna-breadcrumbs
                                class="breadcrumb-item"
                                active="active"
                            />
                        </ol>
                    </nav>
                @endif

                <div class="order-last order-md-0 command-bar-wrapper">
                    <div class="@hasSection('navbar') @else d-none d-md-block @endif layout d-md-flex align-items-center">
                        <header class="d-none d-md-block col-xs-12 col-md p-0 me-3">
                            <h1 class="m-0 fw-light h3 text-black">@yield('title')</h1>
                            <small class="text-muted" title="@yield('description')">@yield('description')</small>
                        </header>
                        <nav class="col-xs-12 col-md-auto ms-md-auto p-0">
                            <ul class="nav command-bar justify-content-sm-end justify-content-start d-flex align-items-center">
                                @yield('navbar')
                            </ul>
                        </nav>
                    </div>
                </div>

                @include('platform::partials.alert')

                <div id="modals-container">
                    @stack('modals-container')
                </div>

                <form id="post-form"
                    class="mb-md-4 h-100"
                    method="post"
                    enctype="multipart/form-data"
                    data-controller="form"
                    data-form-need-prevents-form-abandonment-value="{{ var_export($needPreventsAbandonment) }}"
                    data-form-failed-validation-message-value="{{ $formValidateMessage }}"
                    data-action="keypress->form#disableKey
                                turbo:before-fetch-request@document->form#confirmCancel
                                beforeunload@window->form#confirmCancel
                                change->form#changed
                                form#submit"
                    novalidate
                >
                    {!! $layouts !!}
                    @csrf
                    @include('platform::partials.confirm')
                </form>

                <div data-controller="filter">
                    <form id="filters" autocomplete="off"
                        data-action="filter#submit"
                        data-form-need-prevents-form-abandonment-value="false"
                    ></form>
                </div>

                @includeWhen(isset($state), 'platform::partials.state')
            </div>

            @includeFirst([config('platform.template.footer'), 'platform::footer'])
        </div>
    </div>
@endsection
