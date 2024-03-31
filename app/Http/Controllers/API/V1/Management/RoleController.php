<?php

namespace App\Http\Controllers\API\V1\Management;

use App\Exceptions\ForbiddenActionException;
use App\Http\Controllers\Controller;
use App\Http\Requests\V1\Management\Roles\StoreRoleRequest;
use App\Http\Requests\V1\Management\Roles\UpdateRoleRequest;
use App\Http\Resources\V1\Management\Roles\RoleResource;
use App\Http\Resources\V1\Management\Roles\RoleResourceCollection;
use App\Services\V1\Management\RoleService;
use App\Services\V1\ResponseCode;
use Iqbalatma\LaravelServiceRepo\Exceptions\EmptyDataException;
use Iqbalatma\LaravelUtils\APIResponse;

class RoleController extends Controller
{
    protected array $responseMessages;

    public function __construct()
    {
        $this->responseMessages = [
            "index" => "Get all data role successfully",
            "show" => "Get data role by id successfully",
            "store" => "Add new data role successfully",
            "update" => "Update data role by id successfully",
            "destroy" => "Delete data role by id successfully",
        ];
    }

    /**
     * @param RoleService $service
     * @return APIResponse
     */
    public function index(RoleService $service): APIResponse
    {
        $response = $service->getAllData();

        return new APIResponse(
            new RoleResourceCollection($response),
            $this->getResponseMessage(__FUNCTION__),
        );
    }

    /**
     * @param RoleService $service
     * @param string $id
     * @return APIResponse
     * @throws EmptyDataException
     */
    public function show(RoleService $service, string $id): APIResponse
    {
        $response = $service->getDataById($id);

        return new APIResponse(
            new RoleResource($response),
            $this->getResponseMessage(__FUNCTION__)
        );
    }


    /**
     * @param RoleService $service
     * @param StoreRoleRequest $request
     * @return APIResponse
     */
    public function store(RoleService $service, StoreRoleRequest $request): APIResponse
    {
        $response = $service->addNewData($request->validated());

        return new APIResponse(
            new RoleResource($response),
            $this->getResponseMessage(__FUNCTION__),
            responseCode: ResponseCode::CREATED()
        );
    }

    /**
     * @param RoleService $service
     * @param UpdateRoleRequest $request
     * @param string $id
     * @return APIResponse
     * @throws EmptyDataException|ForbiddenActionException
     */
    public function update(RoleService $service, UpdateRoleRequest $request, string $id):APIResponse
    {
        $response = $service->updateDataById($id, $request->validated());

        return new APIResponse(
            new RoleResource($response),
            $this->getResponseMessage(__FUNCTION__)
        );
    }

    /**
     * @param RoleService $service
     * @param string $id
     * @return APIResponse
     * @throws EmptyDataException
     * @throws ForbiddenActionException
     */
    public function destroy(RoleService $service, string $id):APIResponse
    {
        $service->deleteDataById($id);

        return new APIResponse(
            null,
            $this->getResponseMessage(__FUNCTION__)
        );
    }
}
