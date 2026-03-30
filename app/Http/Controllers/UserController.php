<?php

namespace App\Http\Controllers;

use App\Http\Resources\UserResource;
use App\Models\User;
use App\Services\UserService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;

class UserController extends Controller
{
    public function __construct(private readonly UserService $service) {}

    public function index(Request $request): AnonymousResourceCollection
    {
        $this->authorize('users.manage');
        
        $users = $this->service->list($request->only(['search', 'sector_id', 'per_page']));
        return UserResource::collection($users);
    }

    public function store(Request $request): UserResource
    {
        $this->authorize('users.manage');

        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'email', 'unique:users,email'],
            'password' => ['required', 'string', 'min:8'],
            'sector_id' => ['nullable', 'exists:sectors,id'],
        ]);

        return new UserResource($this->service->create($validated));
    }

    public function show(User $user): UserResource
    {
        $this->authorize('users.manage');
        
        return new UserResource($user->load('sector'));
    }

    public function update(Request $request, User $user): UserResource
    {
        $this->authorize('users.manage');

        $validated = $request->validate([
            'name' => ['sometimes', 'string', 'max:255'],
            'email' => ['sometimes', 'email', 'unique:users,email,' . $user->id],
            'password' => ['sometimes', 'string', 'min:8'],
            'sector_id' => ['nullable', 'exists:sectors,id'],
        ]);

        return new UserResource($this->service->update($user, $validated));
    }

    public function destroy(User $user): JsonResponse
    {
        $this->authorize('users.manage');
        
        $this->service->delete($user);
        return response()->json(['message' => 'Usuário removido com sucesso.']);
    }

    public function active(): AnonymousResourceCollection
    {
        $this->authorize('users.manage');
        
        return UserResource::collection($this->service->getActive());
    }

    public function bySector(int $sectorId): AnonymousResourceCollection
    {
        $this->authorize('users.manage');
        
        return UserResource::collection($this->service->getBySector($sectorId));
    }
}
