<?php

namespace App\Services\V1\Management;

use App\Contracts\Abstracts\Services\BaseService;
use App\Enums\AuditAction;
use App\Exceptions\ForbiddenActionException;
use App\Models\Role;
use App\Repositories\RoleRepository;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\DB;
use Iqbalatma\LaravelAudit\AuditService;
use Iqbalatma\LaravelServiceRepo\Attributes\ServiceRepository;
use Iqbalatma\LaravelServiceRepo\Exceptions\EmptyDataException;
use JsonException;
use Throwable;

/**
 * @method  Role getServiceEntity()
 */
#[ServiceRepository(RoleRepository::class)]
class RoleService extends BaseService
{
    /**
     * @return Collection
     */
    public function getAllData(): Collection
    {
        return RoleRepository::getAllData();
    }


    /**
     * @param array $requestedData
     * @return Role
     * @throws JsonException
     * @throws Throwable
     */
    public function addNewData(array $requestedData): Role
    {
        $this->setRequestedData($requestedData);
        $requestedData["is_mutable"] = true;
        DB::beginTransaction();
        /** @var Role $role */
        $role = RoleRepository::addNewData($requestedData);
        $this->syncRolePermission($requestedData, $role);
        AuditService::init(AuditAction::ROLE_CREATE, __METHOD__, $role)
            ->addSingleTrail($role, null, $role->toArray())
            ->execute();
        DB::commit();

        return $role;
    }


    /**
     * @param string|int $id
     * @param array $requestedData
     * @return Role
     * @throws EmptyDataException
     * @throws ForbiddenActionException
     * @throws Throwable
     */
    public function updateDataById(string|int $id, array $requestedData): Role
    {
        $this->setRequestedData($requestedData)->checkData($id);
        DB::beginTransaction();
        $role = $this->getServiceEntity();
        $roleBefore = $role->toArray();

        if ($role->name === \App\Enums\Role::SUPERADMIN->value) {
            throw new ForbiddenActionException("Role {$role->name} cannot be updated");
        }

        if ($role->is_mutable) {
            $role->fill($requestedData)->save();
        }

        $this->syncRolePermission($requestedData, $role);

        AuditService::init(AuditAction::ROLE_EDIT, __METHOD__, $role)
            ->addSingleTrail($role, $roleBefore, $role->toArray())
            ->execute();
        DB::commit();

        return $role;
    }

    /**
     * @param string|int $id
     * @return int
     * @throws EmptyDataException
     * @throws ForbiddenActionException
     * @throws JsonException
     */
    public function deleteDataById(string|int $id): int
    {
        $this->checkData($id);
        $role = $this->getServiceEntity();

        if ($role->name === \App\Enums\Role::SUPERADMIN->value || !$role->is_mutable) {
            throw new ForbiddenActionException("Role " . $role->name . " cannot be deleted");
        }

        AuditService::init(AuditAction::ROLE_EDIT, __METHOD__, $role)
            ->addSingleTrail($role, $role->toArray(), null)
            ->execute();

        return $role->delete();
    }


    /**
     * @param array $requestedData
     * @param Role $role
     * @return void
     */
    private function syncRolePermission(array &$requestedData, Role $role): void
    {
        if (isset($requestedData["permission_ids"])) {
            $role->permissions()->sync($requestedData["permission_ids"]);
            $role->refresh();
        }
    }
}
