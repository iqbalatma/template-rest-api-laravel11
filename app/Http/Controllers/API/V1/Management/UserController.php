<?php

namespace App\Http\Controllers\API\V1\Management;

use App\Http\Controllers\Controller;
use App\Http\Resources\V1\Management\Users\UserResourceCollection;
use App\Services\V1\Management\UserService;
use Iqbalatma\LaravelUtils\APIResponse;

class UserController extends Controller
{
    protected array $responseMessages;

    public function __construct()
    {
        $this->responseMessages = [
            "index" => "Get all data paginated successfully"
        ];
    }

    /**
     * @param UserService $service
     * @return APIResponse
     */
    public function index(UserService $service): APIResponse
    {
        $response = $service->getAllDataPaginated();
        return new APIResponse(
            new UserResourceCollection($response),
            $this->getResponseMessage(__FUNCTION__)
        );
    }
}
