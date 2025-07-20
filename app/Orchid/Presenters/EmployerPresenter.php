<?php

namespace App\Orchid\Presenters;

use Orchid\Support\Presenter;

class EmployerPresenter extends Presenter
{

    /**
     * Returns the label for this presenter, which is used in the UI to identify it.
     */
    public function label(): string
    {
        return 'Employers';
    }

    /**
     * Returns the title for this presenter, which is displayed in the UI as the main heading.
     */
    public function title(): string
    {
        return $this->entity->name;
    }

    /**
     * Returns the URL for this presenter, which is used to link to the entity's edit page.
     */
    public function url(): string
    {
        $route = $this->entity->id ? route('platform.employers.edit', $this->entity->id) : '#';
        return $route;
    }

    /**
     * Returns the URL for the user's Gravatar image, or a default image if one is not found.
     */
    public function image(): ?string
    {
        // $hash = md5(strtolower(trim($this->entity->email)));
        // $default = urlencode('https://raw.githubusercontent.com/orchidsoftware/.github/main/web/avatars/gravatar.png');
        // $defaultImage = "https://www.gravatar.com/avatar/$hash?d=$default";

        $defaultImage = url('/images/hp-logo-avatar.png');

        return $this->entity->logo ? url($this->entity->logo) : $defaultImage;
    }

    public function nameWithLogo(): string
    {
        $logoUrl = $this->image(); // Assuming this method returns the URL of the logo
        $name = $this->entity->name;
        $employerUrl = $this->url();
        // return "<img src='{$logoUrl}' alt='logo' style='height: 20px; width: 20px; object-fit: cover; margin-right: 5px;'>{$name}";
        return view('components.employer-name-logo', compact('logoUrl', 'name', 'employerUrl'))->render();
    }


}
