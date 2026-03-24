<?php

namespace App\Traits;

use OwenIt\Auditing\Contracts\Auditable;

trait AuditableModel
{
    use \OwenIt\Auditing\Auditable;

    protected $auditInclude = ['*'];

    protected $auditExclude = [
        'password',
        'remember_token'
    ];

    protected $auditStrict = true;

    protected $auditTimestamps = true;

    public function transformAudit(array $data): array
    {
        $data['ip_address'] = request()->ip();
        $data['user_agent'] = request()->userAgent();
        $data['url'] = request()->fullUrl();

        return $data;
    }
}