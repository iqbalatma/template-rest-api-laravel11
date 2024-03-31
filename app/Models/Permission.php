<?php

namespace App\Models;

use App\Enums\Table;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Concerns\HasUuids;


/**
 * @property string id
 * @property string name
 * @property string guard_name
 * @property string feature_group
 * @property string description
 * @property Carbon created_at
 * @property Carbon updated_at
 */
class Permission extends \Spatie\Permission\Models\Permission
{
    use HasUuids;

    protected $fillable = ["name", "guard_name", "feature_group", "description"];

    protected $table = Table::PERMISSIONS->value;
}
