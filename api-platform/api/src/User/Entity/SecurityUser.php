<?php

declare(strict_types=1);

namespace App\User\Entity;

use App\Shared\Entity\AbstractUuidEntity;
use Doctrine\ORM\Mapping as ORM;
use Doctrine\ORM\Mapping\Entity;

#[ORM\Entity]
#[ORM\Table(name: 'security_users')]
class SecurityUser extends AbstractUuidEntity
{
    protected string $email;
}
