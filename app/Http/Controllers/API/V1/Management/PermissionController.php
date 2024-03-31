<?php

namespace App\Http\Controllers\API\V1\Management;

use App\Http\Controllers\Controller;
use App\Http\Resources\V1\Management\Permissions\PermissionResourceCollection;
use App\Services\V1\Management\PermissionService;
use Iqbalatma\LaravelUtils\APIResponse;

class PermissionController extends Controller
{
    protected array $responseMessages;

    public function __construct()
    {
        $this->responseMessages = [
            "index" => "Get all data permission successfully"
        ];
    }

    /**
     * @param PermissionService $service
     * @return APIResponse
     */
    public function index(PermissionService $service):APIResponse
    {
        $response = $service->getAllData();

        return new APIResponse(
            new PermissionResourceCollection($response),
            $this->getResponseMessage(__FUNCTION__)
        );
    }
}
