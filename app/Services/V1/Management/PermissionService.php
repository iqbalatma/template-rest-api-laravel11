<?php

namespace App\Services\V1\Management;
use App\Contracts\Abstracts\Services\BaseService;
use App\Repositories\PermissionRepository;
use Illuminate\Support\Collection;

class PermissionService extends BaseService
{
    /**
     * @return Collection
     */
    public function getAllData():Collection
    {
        return PermissionRepository::getAllData();
    }
}
