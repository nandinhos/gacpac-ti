<?php

namespace App\Livewire\Users;

use App\Models\User;
use App\Models\Sector;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;
use Illuminate\Validation\Rules;
use Livewire\Component;

class Edit extends Component
{
    public User $user;
    public $name;
    public $rank;
    public $military_id;
    public $email;
    public string $password = '';
    public string $password_confirmation = '';
    public $sector_id;
    public $is_active = true;

    public function mount(User $user)
    {
        $this->user = $user;
        $this->name = $user->name;
        $this->email = $user->email;
        $this->rank = $user->rank;
        $this->military_id = $user->military_id;
        $this->sector_id = $user->sector_id;
        $this->is_active = $user->is_active; // Initialize is_active from user model
    }

    public function update()
    {
        $this->validate([
            'name' => ['required', 'string', 'max:255'],
            'rank' => ['required', 'string', 'max:20'],
            'military_id' => ['required', 'string', Rule::unique('users')->ignore($this->user->id)],
            'email' => ['required', 'string', 'lowercase', 'email', 'max:255', Rule::unique('users')->ignore($this->user->id)],
            'sector_id' => ['nullable', 'exists:sectors,id'],
            'is_active' => ['boolean'],
        ]);

        $this->user->update([
            'name' => $this->name,
            'rank' => $this->rank,
            'military_id' => $this->military_id,
            'email' => $this->email,
            'sector_id' => $this->sector_id,
            'is_active' => $this->is_active,
        ]);

        // Password update logic is removed from here as per instruction,
        // but if a password change is intended, it would need its own validation and update.
        // For now, only the fields specified in the update array are changed.

        return redirect()->route('users.index');
    }

    public function render()
    {
        return view('livewire.users.edit', [
            'sectors' => \App\Models\Sector::orderBy('name')->get(),
        ])->layout('layouts.sgaiti');
    }
}
