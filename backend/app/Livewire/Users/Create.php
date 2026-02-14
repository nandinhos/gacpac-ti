<?php

namespace App\Livewire\Users;

use App\Models\MilitaryUser;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules;
use Livewire\Component;

class Create extends Component
{
    public $name;
    public $rank;
    public $military_id;
    public $email;
    public $sector_id;
    public $is_active = true; // Default true

    protected $rules = [
        'name' => ['required', 'string', 'max:255'],
        'rank' => ['required', 'string', 'max:20'],
        'military_id' => ['required', 'string', 'unique:users,military_id'],
        'email' => ['required', 'string', 'lowercase', 'email', 'max:255', 'unique:users,email'],
        'sector_id' => ['nullable', 'exists:sectors,id'],
        'is_active' => ['boolean'],
    ];

    public function save()
    {
        $this->validate();

        User::create([
            'name' => $this->name,
            'rank' => $this->rank,
            'military_id' => $this->military_id,
            'email' => $this->email,
            'sector_id' => $this->sector_id,
            'is_active' => $this->is_active,
            'password' => Hash::make(Str::random(12)), // Generate random password for now or use default
            'is_military' => true, // Force military for this legacy creation flow?
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
