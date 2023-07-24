<?php

declare(strict_types=1);

namespace App\User\Entity;

use App\Shared\Entity\AbstractUuidEntity;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Uid\Uuid;

#[ORM\Entity]
#[ORM\Table(name: 'users')]
class UserData extends AbstractUuidEntity
{
    #[ORM\OneToOne(mappedBy: 'userData', targetEntity: SecurityUser::class)]
    protected SecurityUser $user;

    #[ORM\Column(name: 'name', type: Types::STRING)]
    protected string $name;

    #[ORM\Column(name: 'surname', type: Types::STRING)]
    protected string $surname;

    public function __construct(
        Uuid $uuid,
        string $name,
        string $surname,
    ) {
        parent::__construct($uuid);
        $this->name = $name;
        $this->surname = $surname;
    }


    public function getName(): string
    {
        return $this->name;
    }

    public function setName(string $name): void
    {
        $this->name = $name;
    }

    public function getSurname(): string
    {
        return $this->surname;
    }

    public function setSurname(string $surname): void
    {
        $this->surname = $surname;
    }

    public function getUser(): SecurityUser
    {
        return $this->user;
    }

    public function setSecurityUser(SecurityUser $user): void
    {
        if (isset($this->user)) {
            throw new \LogicException('UserData already assigned to security user');
        }

        $this->user = $user;
    }
}
