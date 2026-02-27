<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;
use App\Models\User;
use App\Http\Requests\LoginRequest;

class AuthController extends Controller
{
    /**
     * Handle user login
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function login(Request $request)
    {
        $request->validate([
            'military_id' => 'required',
            'password' => 'required',
        ]);

        try {
            $user = User::where('military_id', $request->military_id)->first();

            if (! $user || ! Hash::check($request->password, $user->password)) {
                throw ValidationException::withMessages([
                    'military_id' => ['As credenciais fornecidas estão incorretas.'],
                ]);
            }

            // Revoke existing tokens
            $user->tokens()->delete();

            // Create new token with abilities based on role
            $abilities = $this->getAbilitiesForRole($user->user_role);
            $token = $user->createToken('auth-token', $abilities)->plainTextToken;

            return response()->json([
                'message' => 'Login realizado com sucesso',
                'user' => [
                    'id' => $user->id,
                    'name' => $user->name,
                    'rank' => $user->rank,
                    'military_id' => $user->military_id,
                    'sector_id' => $user->sector_id,
                    'email' => $user->email,
                    'user_role' => $user->user_role,
                    'commission_inventories' => $user->commission_inventories,
                ],
                'token' => $token,
                'abilities' => $abilities
            ]);

        } catch (ValidationException $e) {
            return response()->json([
                'message' => 'Dados inválidos',
                'errors' => $e->errors()
            ], 422);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Erro interno do servidor',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Handle user logout
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function logout(Request $request): JsonResponse
    {
        $request->user()->currentAccessToken()->delete();
        
        return response()->json([
            'message' => 'Logout realizado com sucesso'
        ]);
    }

    /**
     * Get authenticated user info
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function me(Request $request): JsonResponse
    {
        $user = $request->user();
        
        return response()->json([
            'user' => [
                'id' => $user->id,
                'name' => $user->name,
                'rank' => $user->rank,
                'military_id' => $user->military_id,
                'sector_id' => $user->sector_id,
                'email' => $user->email,
                'user_role' => $user->user_role,
                'commission_inventories' => $user->commission_inventories,
            ],
            'abilities' => $this->getAbilitiesForRole($user->user_role)
        ]);
    }

    /**
     * Get abilities based on user role for Sanctum tokens
     *
     * @param string $role
     * @return array
     */
    private function getAbilitiesForRole(string $role): array
    {
        return match($role) {
            'admin' => [
                'view:all',
                'create:all', 
                'edit:all',
                'delete:all'
            ],
            'commission' => [
                'view:custody',
                'view:inventory',
                'edit:inventory',
                'edit:profile'
            ],
            'user' => [
                'view:custody',
                'view:profile',
                'edit:profile'
            ],
            default => ['view:profile']
        };
    }
}
