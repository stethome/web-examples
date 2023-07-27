<?php

declare(strict_types=1);

namespace App\User\ApiResource;

use ApiPlatform\Doctrine\Orm\Filter\SearchFilter;
use ApiPlatform\Doctrine\Orm\State\Options;
use ApiPlatform\Metadata\ApiFilter;
use ApiPlatform\Metadata\Get;
use ApiPlatform\Metadata\GetCollection;
use ApiPlatform\Metadata\Post;
use App\Shared\Security\Enum\IsGranted;
use App\User\Controller\GetCurrentUser;
use App\User\Dto\UserRegisterDto;
use App\User\Entity\SecurityUser;
use App\User\State\CurrentUserProvider;
use App\User\State\UserProvider;
use App\User\State\UserRegisterProcessor;
use Symfony\Component\Uid\Uuid;

#[Post(
    uriTemplate: '/register',
    input: UserRegisterDto::class,
    processor: UserRegisterProcessor::class,
)]
#[Get(
    uriVariables: ['uuid'],
    security: IsGranted::Admin->value.' or object.uuid == user.getUuid()',
    provider: UserProvider::class,
    stateOptions: new Options(SecurityUser::class),
)]
#[GetCollection(
    security: IsGranted::Admin->value,
    provider: UserProvider::class,
    stateOptions: new Options(SecurityUser::class)
)]
#[ApiFilter(
    SearchFilter::class,
    properties: ['email' => 'partial'],
)]
class UserModel
{
    public Uuid $uuid;
    public string $email;
    public string $name;
    public string $surname;

    private function __construct(
        Uuid $uuid,
    ) {
        $this->uuid = $uuid;
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
