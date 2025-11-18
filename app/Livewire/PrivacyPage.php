<?php

namespace App\Livewire;

use Livewire\Component;

class PrivacyPage extends Component
{
    public function render()
    {
        return view('livewire.privacy-page')->layout('layouts.home-layout');
    }
}
