<div class="employer-name-with-logo">
    <a href="{{ $employerUrl }}">
        <div class="flex">
            @if($logoUrl)
                <img src="{{ $logoUrl }}" alt="Logo" style="height: 20px; width: 20px; object-fit: cover; margin-right: 5px;">
            @endif
            {{ $name }}
        </div>
    </a>
</div>
