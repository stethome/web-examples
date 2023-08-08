<?php

declare(strict_types=1);

namespace App\User\Service;

use Symfony\Component\Uid\Uuid;

interface AppUserInterface
{
    public function getUuid(): Uuid;
}
