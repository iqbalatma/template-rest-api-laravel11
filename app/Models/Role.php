<?php

namespace App\Models;

use App\Enums\Table;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Concerns\HasUuids;


/**
 * @property string id
 * @property string name
 * @property string guard_name
 * @property boolean is_mutable
 * @property Carbon created_at
 * @property Carbon updated_at
 */
class Role extends \Spatie\Permission\Models\Role
{
    use HasUuids;

    protected $table = Table::ROLES->value;

    protected $fillable = ["name", "guard_name", "is_mutable"];
}
