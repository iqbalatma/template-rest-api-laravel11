<?php

namespace App\Services\V1\Management;

use App\Contracts\Abstracts\Services\BaseService;
use App\Exceptions\ForbiddenActionException;
use App\Models\Role;
use App\Repositories\RoleRepository;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\DB;
use Iqbalatma\LaravelServiceRepo\Exceptions\EmptyDataException;

/**
 * @method  Role getServiceEntity()
 */
class RoleService extends BaseService
{
    protected $repository;
    protected Role $role;
    protected array $roleBeforeUpdate;
    protected array $permissionBeforeUpdate;

    public function __construct()
    {
        parent::__construct();
        $this->repository = new RoleRepository();
    }

    /**
     * @return Collection
     */
    public function getAllData(): Collection
    {
        return RoleRepository::getAllData();
    }

    /**
     * @param string|int $id
     * @return Role
     * @throws EmptyDataException
     */
    public function getDataById(string|int $id): Role
    {
        $this->checkData($id);
        return $this->getServiceEntity();
    }

    /**
     * @param array $requestedData
     * @return Role
     */
    public function addNewData(array $requestedData): Role
    {
        $this->setRequestedData($requestedData);
        $requestedData["is_mutable"] = true;
        DB::beginTransaction();
        /** @var Role $role */
        $this->role = RoleRepository::addNewData($requestedData);
        $this->syncRolePermission($requestedData, $this->role)
            ->addNewDataAudit(); #process audit
        DB::commit();

        return $this->role;
    }


    /**
     * @param string|int $id
     * @param array $requestedData
     * @return Role
     * @throws EmptyDataException
     * @throws ForbiddenActionException
     */
    public function updateDataById(string|int $id, array $requestedData): Role
    {
        $this->setRequestedData($requestedData)->checkData($id);
        DB::beginTransaction();
        $this->role = $this->getServiceEntity();
        $this->roleBeforeUpdate = $this->role->toArray();
        $this->permissionBeforeUpdate = $this->role->permissions->toArray();

        if ($this->role->name === \App\Enums\Role::SUPERADMIN->value) {
            throw new ForbiddenActionException("Role {$this->role->name} cannot be updated");
        }

        if ($this->role->is_mutable) {
            $this->role->fill($requestedData)->save();
        }

        $this->syncRolePermission($requestedData, $this->role)
            ->updateDataByIdAudit();
        DB::commit();

        return $this->role;
    }

    /**
     * @param string|int $id
     * @return int
     * @throws EmptyDataException
     * @throws ForbiddenActionException
     */
    public function deleteDataById(string|int $id): int
    {
        $this->checkData($id);
        $role = $this->getServiceEntity();

        if ($role->name === \App\Enums\Role::SUPERADMIN->value || !$role->is_mutable) {
            throw new ForbiddenActionException("Role " . $role->name . " cannot be deleted");
        }

        return $role->delete();
    }


    /**
     * @param array $requestedData
     * @param Role $role
     * @return RoleService
     */
    private function syncRolePermission(array &$requestedData, Role $role): self
    {
        if (isset($requestedData["permission_ids"])) {
            $role->permissions()->sync($requestedData["permission_ids"]);
            $role->refresh();
        }

        return $this;
    }


    /**
     * @return void
     */
    private function addNewDataAudit(): void
    {
        $this->auditService->setAction("ADD_NEW_DATA_ROLE")
            ->setMessage("Add single data role")
            ->setObject($this->role)
            ->log(
                ["role" => null, "permission" => null],
                ["role" => $this->role->withoutRelations("permissions"), "permission" => $this->role->permissions],
            );
    }


    /**
     * @return void
     */
    private function updateDataByIdAudit(): void
    {
        $this->auditService->setAction("UPDATE_DATA_ROLE")
            ->setMessage("Update single data role")
            ->setObject($this->role)
            ->addBeforeAfter("role", $this->roleBeforeUpdate, $this->role)
            ->addBeforeAfter("permission", collect($this->permissionBeforeUpdate), $this->role->permissions)
            ->log();
    }
}
