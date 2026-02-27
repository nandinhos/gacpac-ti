<?php

namespace App\Livewire\Admin\Users;

use App\Models\Sector;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Illuminate\Validation\Rules;
use Livewire\Component;
use Spatie\Permission\Models\Role;

class Create extends Component
{
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

    public $generatePassword = true;

    public $customPassword = '';

    public $customPasswordConfirmation = '';

    protected function rules(): array
    {
        return [
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'lowercase', 'email', 'max:255', 'unique:users,email'],
            'is_military' => ['boolean'],
            'force' => ['required', 'string', 'in:FAB,EB,MB,SC'],
            'rank' => ['required', 'string', 'max:50'],
            'military_id' => ['required', 'string', 'max:50', 'unique:users,military_id'],
            'organization' => ['required', 'string', 'in:GAC-PAC,ECP-GPX,ECP-IJA,ECP-POA'],
            'sector_id' => ['nullable', 'exists:sectors,id'],
            'is_active' => ['boolean'],
            'selectedRoles' => ['required', 'array', 'min:1'],
            'selectedRoles.*' => ['exists:roles,name'],
            'generatePassword' => ['boolean'],
            'customPassword' => ['required_if:generatePassword,false', 'nullable', Rules\Password::defaults()],
            'customPasswordConfirmation' => ['required_if:generatePassword,false', 'nullable', 'same:customPassword'],
        ];
    }

    protected function messages(): array
    {
        return [
            'selectedRoles.required' => 'Selecione pelo menos uma função (role) para o usuário.',
            'selectedRoles.min' => 'Selecione pelo menos uma função (role) para o usuário.',
            'customPassword.required_if' => 'A senha é obrigatória quando não gerada automaticamente.',
            'customPasswordConfirmation.same' => 'A confirmação da senha não corresponde.',
        ];
    }

    public function updatedIsMilitary($value)
    {
        if (! $value) {
            $this->force = 'SC';
            $this->rank = 'Civil';
        } else {
            $this->force = 'FAB';
            $this->rank = '';
        }
    }

    public function save()
    {
        $validated = $this->validate();

        $password = $this->generatePassword
            ? Str::random(12)
            : $this->customPassword;

        $user = User::create([
            'name' => $validated['name'],
            'email' => $validated['email'],
            'password' => Hash::make($password),
            'is_military' => $validated['is_military'],
            'force' => $validated['force'],
            'rank' => $validated['rank'],
            'military_id' => $validated['military_id'],
            'organization' => $validated['organization'],
            'sector_id' => $validated['sector_id'],
            'is_active' => $validated['is_active'],
        ]);

        $user->syncRoles($validated['selectedRoles']);

        session()->flash('message', 'Usuário criado com sucesso.'.($this->generatePassword ? " Senha gerada: {$password}" : ''));

        return redirect()->route('admin.users.index');
    }

    public function render()
    {
        return view('livewire.admin.users.create', [
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
