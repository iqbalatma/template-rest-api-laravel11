<?php

namespace App\Http\Controllers\API\V1\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\V1\Auth\AuthenticateRequest;
use App\Http\Resources\V1\Auth\AuthenticateResource;
use App\Models\User;
use App\Services\V1\Auth\AuthenticationService;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Auth;
use Iqbalatma\LaravelUtils\APIResponse;
use Symfony\Component\HttpKernel\Exception\UnauthorizedHttpException;
use Throwable;

class AuthenticationController extends Controller
{
    protected array $responseMessages;

    public function __construct()
    {
        $this->responseMessages = [
            "authenticate" => "Authenticate user successfully",
            "refresh" => "Refresh user token successfully",
            "logout" => "Logout user successfully",
        ];
    }

    /**
     * @param AuthenticationService $service
     * @param AuthenticateRequest $request
     * @return JsonResponse|APIResponse
     */
    public function authenticate(AuthenticationService $service, AuthenticateRequest $request): JsonResponse|APIResponse
    {
        $credentials = request(['email', 'password']);
        if (!$token = Auth::attempt($credentials)) {
            throw new UnauthorizedHttpException("Invalid credentials");
        }

        /** @var User $user */
        $user = Auth::user();
        return response()->json(
            [
                "code" => "SUCCESS",
                "message" => "Logged in successfully.",
                "timestamp" => now(),
                "payload" => [
                    "data" => [
                        "id" => $user->id,
                        "first_name" => $user->first_name,
                        "last_name" => $user->last_name,
                        "email" => $user->email,
                        "access_token" => Auth::getAccessToken(),
                    ],
                ],
            ]
        )
            ->withCookie(getCreatedCookieAccessTokenVerifier(Auth::getAccessTokenVerifier()))
            ->withCookie(getCreatedCookieRefreshToken(Auth::getRefreshToken()));
    }

    /**
     * @return APIResponse
     */
    public function logout(): APIResponse
    {
        Auth::logout(true);

        return new APIResponse(message: $this->getResponseMessage(__FUNCTION__));
    }


    /**
     * @param AuthenticationService $service
     * @return JsonResponse|APIResponse
     */
    public function refresh(AuthenticationService $service): JsonResponse|APIResponse
    {
        Auth::refreshToken(Auth::user());

        /** @var User $user */
        $user = Auth::user();
        return response()->json(
            [
                "code" => "SUCCESS",
                "message" => "Logged in successfully.",
                "timestamp" => now(),
                "payload" => [
                    "data" => [
                        "id" => $user->id,
                        "first_name" => $user->first_name,
                        "last_name" => $user->last_name,
                        "email" => $user->email,
                        "access_token" => Auth::getAccessToken()
                    ],
                ],
            ]
        )
            ->withCookie(getCreatedCookieAccessTokenVerifier(Auth::getAccessTokenVerifier()))
            ->withCookie(getCreatedCookieRefreshToken(Auth::getRefreshToken()));
    }
}
