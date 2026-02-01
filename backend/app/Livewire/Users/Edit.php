<?php

namespace App\Livewire\Users;

use App\Models\MilitaryUser;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;
use Illuminate\Validation\Rules;
use Livewire\Component;

class Edit extends Component
{
    public MilitaryUser $user;
    public string $name = '';
    public string $email = '';
    public string $rank = '';
    public string $military_id = '';
    public string $password = '';
    public string $password_confirmation = '';
    public $sector_id;

    public function mount(MilitaryUser $user)
    {
        $this->user = $user;
        $this->name = $user->name;
        $this->email = $user->email;
        $this->rank = $user->rank;
        $this->military_id = $user->military_id;
        $this->sector_id = $user->sector_id;
    }

    public function save()
    {
        $this->validate([
            'name' => ['required', 'string', 'max:255'],
            'rank' => ['required', 'string'],
            'military_id' => ['required', 'string', Rule::unique('military_users')->ignore($this->user->id)],
            'email' => ['required', 'string', 'lowercase', 'email', 'max:255', Rule::unique('military_users')->ignore($this->user->id)],
            'password' => ['nullable', 'confirmed', Rules\Password::defaults()],
            'sector_id' => ['required', 'exists:sectors,id'],
        ]);

        $this->user->fill([
            'name' => $this->name,
            'email' => $this->email,
            'rank' => $this->rank,
            'military_id' => $this->military_id,
            'sector_id' => $this->sector_id,
        ]);

        if ($this->password) {
            $this->user->password = Hash::make($this->password);
        }

        $this->user->save();

        return redirect()->route('users.index');
    }

    public function render()
    {
        return view('livewire.users.edit', [
            'sectors' => \App\Models\Sector::orderBy('name')->get(),
        ])->layout('layouts.sgaiti');
    }
}
