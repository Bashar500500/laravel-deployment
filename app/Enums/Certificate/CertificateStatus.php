<?php

namespace App\Enums\Certificate;

enum CertificateStatus: string
{
    case Active = 'Active';
    case Inactive = 'Inactive';
}
