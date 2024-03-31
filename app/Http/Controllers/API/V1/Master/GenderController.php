<?php

namespace App\Http\Controllers\API\V1\Master;

use App\Enums\Gender;
use App\Http\Controllers\Controller;
use App\Services\V1\Master\PermissionService;
use Iqbalatma\LaravelUtils\APIResponse;

class GenderController extends Controller
{
    protected array $responseMessages;

    public function __construct()
    {
        $this->responseMessages = [
            "__invoke" => "Get all data permission successfully",
        ];
    }

    /**
     * @param PermissionService $service
     * @return APIResponse
     */
    public function __invoke(PermissionService $service):APIResponse
    {
        return new APIResponse(
            Gender::values(),
            $this->getResponseMessage(__FUNCTION__)
        );
    }
}
