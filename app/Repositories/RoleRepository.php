<?php

namespace App\Repositories;
use App\Contracts\Abstracts\BaseRepository;
use Illuminate\Database\Eloquent\Builder;
use App\Models\Role;

class RoleRepository extends BaseRepository
{

     /**
     * use to set base query builder
     * @return Builder
     */
    public function getBaseQuery(): Builder
    {
        return Role::query();
    }

    /**
     * use this to add custom query on filterColumn method
     * @return void
     */
    public function applyAdditionalFilterParams(): void
    {
    }
}
