<?php

namespace App\Enums;

enum AuditAction: string
{
    case ROLE_CREATE = "Add new role";
    case ROLE_EDIT = "Edit role";
    case ROLE_DELETE = "Delete role";
}
