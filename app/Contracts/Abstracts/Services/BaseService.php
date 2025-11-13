<?php

namespace App\Contracts\Abstracts\Services;

use Iqbalatma\LaravelAudit\AuditService;

abstract class BaseService extends \Iqbalatma\LaravelServiceRepo\BaseService {
    public AuditService $auditService;
}
