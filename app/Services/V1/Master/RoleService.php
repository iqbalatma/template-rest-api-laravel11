<?php

namespace App\Services\V1\Master;
use App\Contracts\Abstracts\Services\BaseService;
use App\Repositories\RoleRepository;
use Illuminate\Database\Eloquent\Collection;

class RoleService extends BaseService
{
    /**
     * @return Collection
     */
    public function getAllData():Collection
    {
        return RoleRepository::getAllData();
    }
}
