<?php

namespace App\Models;

use App\Enums\Table;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;


/**
 * @property string id
 * @property string address
 * @property string gender
 * @property string avatar
 * @property string birth_date
 * @property string birth_place
 * @property Carbon created_at
 * @property Carbon updated_at
 * @property User user
 */
class Profile extends Model
{
    use HasFactory, HasUuids;

    protected $table = Table::PROFILES->value;
    protected $fillable = ["address", "gender", "avatar", "birth_date", "birth_place"];


    /**
     * @return BelongsTo
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, "id", "id");
    }
}
