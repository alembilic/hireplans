@push('head')
    <meta name="robots" content="noindex"/>
    <meta name="google" content="notranslate">
    <!-- <link
          href="{{ asset('/vendor/orchid/favicon.svg') }}"
          sizes="any"
          type="image/svg+xml"
          id="favicon"
          rel="icon"
    > -->
    <link
        href="{{ asset('images/favicon.ico') }}"
        sizes="any"
        type="image/x-icon"
        id="favicon"
        rel="icon"
    />

    <!-- For Safari on iOS -->
    <meta name="theme-color" content="#21252a">
@endpush

<div class="h2 d-flex align-items-center">
    @auth
        <x-orchid-icon path="bs.house" class="d-inline d-xl-none"/>
    @endauth


    <p class="my-0 {{ auth()->check() ? 'd-none d-xl-block' : '' }}">
        {{-- {{ config('app.name') }} --}}
        {{-- <small class="align-top opacity">Orchid</small> --}}
        {{-- Add image here --}}
        {{-- <img class="w-10" src="{{ asset('images/HirePlansLogo1.png') }}" alt="HirePlans Logo"> {{ config('app.name') }} --}}
        <img class="w-100" src="{{ asset('images/HirePlansLogoText3.png') }}" alt="HirePlans Logo">
    </p>
</div>
