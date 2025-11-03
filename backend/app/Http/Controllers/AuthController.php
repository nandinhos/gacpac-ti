<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;
use App\Models\MilitaryUser;
use App\Http\Requests\LoginRequest;

class AuthController extends Controller
{
    /**
     * Handle user login
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function login(LoginRequest $request): JsonResponse
    {
        try {

            // For now, we'll use a simple authentication system
            // TODO: Implement proper password hashing and authentication
            $user = MilitaryUser::where('military_id', $request->military_id)
                                ->where('is_active', true)
                                ->first();

            if (!$user) {
                return response()->json([
                    'message' => 'Credenciais inválidas'
                ], 401);
            }

            // For demo purposes, accept any password for active users
            // TODO: Implement proper password verification
            $token = $this->generateSimpleToken($user);

            return response()->json([
                'message' => 'Login realizado com sucesso',
                'user' => [
                    'id' => $user->id,
                    'name' => $user->name,
                    'rank' => $user->rank,
                    'military_id' => $user->military_id,
                    'sector_id' => $user->sector_id,
                ],
                'token' => $token
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
        // TODO: Implement token invalidation
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
        // TODO: Implement based on token validation
        return response()->json([
            'message' => 'Endpoint não implementado ainda'
        ], 501);
    }

    /**
     * Generate a simple token for demo purposes
     * TODO: Replace with Laravel Sanctum or Passport
     *
     * @param MilitaryUser $user
     * @return string
     */
    private function generateSimpleToken(MilitaryUser $user): string
    {
        return base64_encode($user->id . ':' . $user->military_id . ':' . time());
    }
}
