<?php

namespace App\Livewire\User;

use Livewire\Component;
use Livewire\WithFileUploads;
use Illuminate\Support\Facades\Auth;

class ProfileAvatar extends Component
{
    use WithFileUploads;

    public $avatar;

    public function updatedAvatar()
    {
        $this->validate([
            'avatar' => 'image|max:1024', // 1MB Max
        ]);

        $path = $this->avatar->store('avatars', 'public');
        Auth::user()->update(['avatar' => $path]);
    }

    public function render()
    {
        return view('livewire.user.profile-avatar');
    }
}
