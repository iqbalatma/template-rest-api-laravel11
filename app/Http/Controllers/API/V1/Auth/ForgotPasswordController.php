<?php

namespace App\Http\Controllers\API\V1\Auth;

use App\Exceptions\ForbiddenActionException;
use App\Http\Controllers\Controller;
use App\Http\Requests\V1\Auth\ForgotPasswordRequest;
use App\Http\Requests\V1\Auth\ResetPasswordRequest;
use App\Services\V1\Auth\ForgotPasswordService;
use Iqbalatma\LaravelServiceRepo\Exceptions\EmptyDataException;
use Iqbalatma\LaravelUtils\APIResponse;

class ForgotPasswordController extends Controller
{
    protected array $responseMessages;

    public function __construct()
    {
        $this->responseMessages = [
            "request" => "Request forgot password sent successfully",
            "reset" => "Reset password successfully",
        ];
    }


    /**
     * @param ForgotPasswordService $service
     * @param ForgotPasswordRequest $request
     * @return APIResponse
     * @throws ForbiddenActionException|EmptyDataException
     */
    public function request(ForgotPasswordService $service, ForgotPasswordRequest $request): APIResponse
    {
        $service->request($request->validated());

        return new APIResponse(
            null,
            $this->getResponseMessage(__FUNCTION__)
        );
    }


    /**
     * @param ForgotPasswordService $service
     * @param ResetPasswordRequest $request
     * @return APIResponse
     * @throws ForbiddenActionException
     */
    public function reset(ForgotPasswordService $service, ResetPasswordRequest $request):APIResponse
    {
        $service->reset($request->validated());

        return new APIResponse(
            null,
            $this->getResponseMessage(__FUNCTION__)
        );
    }
}
