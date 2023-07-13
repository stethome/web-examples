<?php

declare(strict_types=1);

namespace App\User\Entity;

use ApiPlatform\Metadata\ApiResource;
use App\Shared\Entity\AbstractUuidEntity;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity]
#[ORM\Table(name: 'users')]
class User extends AbstractUuidEntity
{
    #[ORM\Column(name: 'name', type: Types::STRING)]
    protected string $name;

    #[ORM\Column(name: 'surname', type: Types::STRING)]
    protected string $surname;
}
