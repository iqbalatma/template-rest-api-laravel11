<?php

namespace App\Http\Requests\V1\Management\Roles;

use App\Enums\Table;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreRoleRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            "name" => ["required", "max:128", Rule::unique(Table::ROLES->value)],
            "guard_name" => "max:128",
            "permission_ids" => "nullable|array",
            "permission_ids.*" => "exists:".Table::PERMISSIONS->value.",id",
        ];
    }
}
