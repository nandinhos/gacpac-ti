<?php

namespace App\Livewire\Admin\Users;

use App\Models\Sector;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;
use Illuminate\Validation\Rules;
use Livewire\Component;
use Spatie\Permission\Models\Role;

class Edit extends Component
{
    public User $user;

    public $name = '';

    public $email = '';

    public $is_military = true;

    public $force = 'FAB';

    public $rank = '';

    public $military_id = '';

    public $organization = 'GAC-PAC';

    public $sector_id = null;

    public $is_active = true;

    public $selectedRoles = [];

    public $resetPassword = false;

    public $newPassword = '';

    public $newPasswordConfirmation = '';

    public function mount(User $user)
    {
        $this->user = $user;
        $this->name = $user->name;
        $this->email = $user->email;
        $this->is_military = $user->is_military;
        $this->force = $user->force;
        $this->rank = $user->rank;
        $this->military_id = $user->military_id;
        $this->organization = $user->organization;
        $this->sector_id = $user->sector_id;
        $this->is_active = $user->is_active;
        $this->selectedRoles = $user->roles->pluck('name')->toArray();
    }

    protected function rules(): array
    {
        return [
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'lowercase', 'email', 'max:255', Rule::unique('users')->ignore($this->user->id)],
            'is_military' => ['boolean'],
            'force' => ['required', 'string', 'in:FAB,EB,MB,SC'],
            'rank' => ['required', 'string', 'max:50'],
            'military_id' => ['required', 'string', 'max:50', Rule::unique('users')->ignore($this->user->id)],
            'organization' => ['required', 'string', 'in:GAC-PAC,ECP-GPX,ECP-IJA,ECP-POA'],
            'sector_id' => ['nullable', 'exists:sectors,id'],
            'is_active' => ['boolean'],
            'selectedRoles' => ['required', 'array', 'min:1'],
            'selectedRoles.*' => ['exists:roles,name'],
            'resetPassword' => ['boolean'],
            'newPassword' => ['required_if:resetPassword,true', 'nullable', Rules\Password::defaults()],
            'newPasswordConfirmation' => ['required_if:resetPassword,true', 'nullable', 'same:newPassword'],
        ];
    }

    protected function messages(): array
    {
        return [
            'selectedRoles.required' => 'Selecione pelo menos uma função (role) para o usuário.',
            'selectedRoles.min' => 'Selecione pelo menos uma função (role) para o usuário.',
            'newPassword.required_if' => 'A nova senha é obrigatória quando a opção de reset está ativa.',
            'newPasswordConfirmation.same' => 'A confirmação da senha não corresponde.',
        ];
    }

    public function updatedIsMilitary($value)
    {
        if (! $value) {
            $this->force = 'SC';
            $this->rank = 'Civil';
        }
    }

    public function update()
    {
        $this->validate();

        if ($this->user->id === Auth::id() && ! $this->is_active) {
            session()->flash('error', 'Você não pode desativar seu próprio usuário.');

            return;
        }

        $this->user->update([
            'name' => $this->name,
            'email' => $this->email,
            'is_military' => $this->is_military,
            'force' => $this->force,
            'rank' => $this->rank,
            'military_id' => $this->military_id,
            'organization' => $this->organization,
            'sector_id' => $this->sector_id,
            'is_active' => $this->is_active,
        ]);

        $this->user->syncRoles($this->selectedRoles);

        if ($this->resetPassword && $this->newPassword) {
            $this->user->update(['password' => Hash::make($this->newPassword)]);
        }

        session()->flash('message', 'Usuário atualizado com sucesso.');

        return redirect()->route('admin.users.index');
    }

    public function render()
    {
        return view('livewire.admin.users.edit', [
            'sectors' => Sector::orderBy('name')->get(),
            'roles' => Role::where('guard_name', 'web')->get(),
            'forces' => [
                'FAB' => 'Força Aérea Brasileira (FAB)',
                'EB' => 'Exército Brasileiro (EB)',
                'MB' => 'Marinha do Brasil (MB)',
                'SC' => 'Servidor Civil (SC)',
            ],
            'organizations' => [
                'GAC-PAC' => 'GAC-PAC (Sede)',
                'ECP-GPX' => 'ECP-GPX (Guarulhos)',
                'ECP-IJA' => 'ECP-IJA (Imperatriz)',
                'ECP-POA' => 'ECP-POA (Porto Alegre)',
            ],
        ])->layout('layouts.sgaiti');
    }
}
