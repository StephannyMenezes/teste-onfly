<?php

namespace App\Http\Controllers;

use App\Http\Requests\LoginAuthRequest;
use App\Http\Requests\RegisterAuthRequest;
use App\Http\Resources\UserResource;
use App\Services\AuthService;
use Illuminate\Http\JsonResponse;
use OpenApi\Annotations as OA;

/**
 * @OA\Tag(
 *      name="Auth",
 *      description="Endpoints relacionados a autenticação"
 *  )
 */
class AuthController extends Controller
{
    private AuthService $service;

    public function __construct(AuthService $service)
    {
        $this->service = $service;
    }

    /**
     * @OA\Post(
     *     path="/api/auth",
     *     tags={"Auth"},
     *     summary="Registra um novo usuário",
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(ref="#/components/schemas/RegisterAuthRequest")
     *     ),
     *     @OA\Response(
     *         response=201,
     *         description="Usuário registrado com sucesso",
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(property="token", type="string", example="eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJzdWIiOjEsInJvbGUiOiJ1c2VyIiwiaWF0IjoxNTE2MjM5MDIyfQ.YY9y3HqZBkxqGv4wZi5D2i0VX2Vt9H4JXyZV3eVlI4k")
     *         )
     *     ),
     *     @OA\Response(
     *         response=400,
     *         description="Dados inválidos fornecidos"
     *     )
     * )
     */
    public function register(RegisterAuthRequest $request): JsonResponse
    {
        $response = $this->service->register($request->validated());

        return response()->json($response, 201);
    }

    /**
     * @OA\Post(
     *     path="/api/auth/login",
     *     tags={"Auth"},
     *     summary="Loga um usuário",
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(ref="#/components/schemas/LoginAuthRequest")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Usuário logado com sucesso",
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(property="token", type="string", example="eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJzdWIiOjEsInJvbGUiOiJ1c2VyIiwiaWF0IjoxNTE2MjM5MDIyfQ.YY9y3HqZBkxqGv4wZi5D2i0VX2Vt9H4JXyZV3eVlI4k")
     *         )
     *     ),
     *     @OA\Response(
     *         response=400,
     *         description="Dados inválidos fornecidos"
     *     ),
     * )
     *
     * Get a JWT via given credentials.
     *
     * @param LoginAuthRequest $request
     * @return JsonResponse
     */
    public function login(LoginAuthRequest $request): JsonResponse
    {
        $response = $this->service->login($request->validated());

        return response()->json($response);
    }

    /**
     * @OA\Get(
     *     path="/api/auth/me",
     *     tags={"Auth"},
     *     summary="Retorna o usuário autenticado",
     *     security={{"bearerAuth": {}}},
     *     @OA\Response(
     *         response=200,
     *         description="Usuário autenticado",
     *         @OA\JsonContent(ref="#/components/schemas/User")
     *     )
     * )
     *
     * Get the authenticated User.
     *
     * @return JsonResponse
     */
    public function me(): JsonResponse
    {
        $user = auth()->user();
        return response()->json(UserResource::make($user));
    }

    /**
     * @OA\Post(
     *     path="/api/auth/logout",
     *     tags={"Auth"},
     *     summary="Desloga um usuário",
     *     security={{"bearerAuth": {}}},
     *     @OA\Response(
     *         response=200,
     *         description="Usuário deslogado com sucesso",
     *     )
     * )
     *
     * Log the user out (Invalidate the token).
     *
     * @return JsonResponse
     */
    public function logout(): JsonResponse
    {
        auth()->logout();
        return response()->json(['message' => 'Successfully logged out']);
    }
}
