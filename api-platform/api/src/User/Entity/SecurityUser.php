<?php

declare(strict_types=1);

namespace App\User\Entity;

use App\Shared\Entity\AbstractUuidEntity;
use App\Shared\Security\Enum\Role;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Security\Core\User\PasswordAuthenticatedUserInterface;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Uid\Uuid;

#[ORM\Entity]
#[ORM\Table(name: 'security_users')]
class SecurityUser extends AbstractUuidEntity implements UserInterface, PasswordAuthenticatedUserInterface
{
    #[ORM\Column(name: 'email', type: Types::STRING, unique: true, nullable: false)]
    protected string $email;

    #[ORM\Column(name: 'password', type: Types::STRING)]
    protected ?string $password = null;

    #[ORM\OneToOne(inversedBy: 'user', targetEntity: UserData::class, cascade: ['persist'], fetch: 'EAGER')]
    #[ORM\JoinColumn(name: 'user_data_uuid', referencedColumnName: 'uuid', nullable: false, onDelete: 'CASCADE')]
    protected UserData $userData;

    public function __construct(
        Uuid $uuid,
        UserData $userData,
        string $email,
    ) {
        parent::__construct($uuid);
        $this->userData = $userData;
        $userData->setSecurityUser($this);
        $this->email = $email;
    }

    public function getEmail(): string
    {
        return $this->email;
    }

    public function setEmail(string $email): void
    {
        $this->email = $email;
    }

    public function getPassword(): ?string
    {
        return $this->password;
    }

    public function setPassword(string $password): void
    {
        $this->password = $password;
    }

    public function getUserData(): UserData
    {
        return $this->userData;
    }

    public function setUserData(UserData $userData): void
    {
        $this->userData = $userData;
    }

    public function getRoles(): array
    {
        return [Role::User->value];
    }

    public function eraseCredentials(): void
    {
        // intentionally empty, nothing to erase
    }

    public function getUserIdentifier(): string
    {
        return $this->getEmail();
    }
}
