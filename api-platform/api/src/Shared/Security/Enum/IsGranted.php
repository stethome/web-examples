<?php

declare(strict_types=1);

namespace App\Shared\Security\Enum;

enum IsGranted: string
{
    case Admin = "is_granted('ROLE_ADMIN')";
    case User = "is_granted('ROLE_USER')";
}
