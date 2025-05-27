<?php

namespace App\Enums\Permission;

enum PermissionGuardName: string
{
    case Api = 'api';
    case Web = 'web';
}
