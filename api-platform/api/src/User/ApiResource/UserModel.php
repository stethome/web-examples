<?php

declare(strict_types=1);

namespace App\User\ApiResource;

use ApiPlatform\Metadata\Post;
use App\User\Dto\UserRegisterDto;
use App\User\Entity\SecurityUser;
use App\User\State\UserRegisterProcessor;
use Symfony\Component\Uid\Uuid;

#[Post(input: UserRegisterDto::class, processor: UserRegisterProcessor::class)]
class UserModel
{
    public Uuid $id;
    public string $email;
    public string $name;
    public string $surname;

    private function __construct(
        Uuid $id,
    ) {
        $this->id = $id;
    }

    public static function fromSecurityUser(SecurityUser $securityUser): self
    {
        $self = new self($securityUser->getUuid());

        $self->email = $securityUser->getEmail();

        $userData = $securityUser->getUserData();
        $self->name = $userData->getName();
        $self->surname = $userData->getSurname();

        return $self;
    }
}
