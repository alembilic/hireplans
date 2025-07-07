<div>
    <input type="file" wire:model="avatar">

    @error('avatar') <span class="error">{{ $message }}</span> @enderror

    @if (Auth::user()->avatar)
        <img src="{{ asset('storage/' . Auth::user()->avatar) }}" alt="Avatar" width="100">
    @endif
</div>
