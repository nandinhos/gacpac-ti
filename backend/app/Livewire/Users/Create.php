<?php

namespace App\Livewire\Users;

use App\Models\MilitaryUser;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules;
use Livewire\Component;

class Create extends Component
{
    public string $name = '';
    public string $email = '';
    public string $rank = '';
    public string $military_id = '';
    public string $password = '';
    public string $password_confirmation = '';
    public $sector_id;

    public function save()
    {
        $this->validate([
            'name' => ['required', 'string', 'max:255'],
            'rank' => ['required', 'string'],
            'military_id' => ['required', 'string', 'unique:military_users,military_id'],
            'email' => ['required', 'string', 'lowercase', 'email', 'max:255', 'unique:military_users,email'],
            'password' => ['required', 'confirmed', Rules\Password::defaults()],
            'sector_id' => ['required', 'exists:sectors,id'],
        ]);

        MilitaryUser::create([
            'name' => $this->name,
            'rank' => $this->rank,
            'military_id' => $this->military_id,
            'email' => $this->email,
            'sector_id' => $this->sector_id,
            'password' => Hash::make($this->password),
            'is_active' => true,
        ]);

        return redirect()->route('users.index');
    }

    public function render()
    {
        return view('livewire.users.create', [
            'sectors' => \App\Models\Sector::orderBy('name')->get(),
        ])->layout('layouts.sgaiti');
    }
}
