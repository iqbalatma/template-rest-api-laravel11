<?php

namespace App\Repositories;
use App\Contracts\Abstracts\BaseRepository;
use Illuminate\Database\Eloquent\Builder;
use App\Models\User;

class UserRepository extends BaseRepository
{

     /**
     * use to set base query builder
     * @return Builder
     */
    public function getBaseQuery(): Builder
    {
        return User::query();
    }

    /**
     * use this to add custom query on filterColumn method
     * @return void
     */
    public function applyAdditionalFilterParams(): void
    {
    }


    /**
     * @param string $email
     * @return User|Builder|null
     */
    public static function getSingleByEmail(string $email): User|Builder|null
    {
        return User::query()->where("email", $email)->first();
    }
}
