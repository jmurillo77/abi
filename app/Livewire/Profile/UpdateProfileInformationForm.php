<?php

namespace App\Livewire\Profile;

use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Laravel\Fortify\Contracts\UpdatesUserProfileInformation;
use Livewire\WithFileUploads;

class UpdateProfileInformationForm extends \Laravel\Jetstream\Http\Livewire\UpdateProfileInformationForm
{
    use WithFileUploads;

    public function mount()
    {
        $user = User::with('persona')->findOrFail(Auth::id());

        $this->state = array_merge([
            'email' => $user->email,
            'Nombres' => $user->persona->Nombres ?? '',
            'Apellidos' => $user->persona->Apellidos ?? '',
        ], $user->attributesToArray());
    }

    public function updateProfileInformation(UpdatesUserProfileInformation $updater)
    {
        $this->resetErrorBag();

        $updater->update(
            Auth::user(),
            $this->photo
                ? array_merge($this->state, ['photo' => $this->photo])
                : $this->state
        );

        if (isset($this->photo)) {
            return redirect()->route('profile.show');
        }

        $this->dispatch('saved');
        $this->dispatch('refresh-navigation-menu');
    }

    public function render()
    {
        return view('profile.update-profile-information-form');
    }
}