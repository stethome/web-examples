<?php

declare(strict_types=1);

namespace App\User\ApiResource;

use ApiPlatform\Doctrine\Orm\Filter\SearchFilter;
use ApiPlatform\Doctrine\Orm\State\Options;
use ApiPlatform\Metadata\ApiFilter;
use ApiPlatform\Metadata\ApiProperty;
use ApiPlatform\Metadata\ApiResource;
use ApiPlatform\Metadata\Get;
use ApiPlatform\Metadata\GetCollection;
use ApiPlatform\Metadata\Post;
use App\Shared\Security\Enum\IsGranted;
use App\User\Dto\UserRegisterDto;
use App\User\Entity\SecurityUser;
use App\User\Service\State\UserProvider;
use App\User\Service\State\UserRegisterProcessor;
use Symfony\Component\Uid\Uuid;

#[ApiResource(stateOptions: new Options(SecurityUser::class))]
#[ApiFilter(SearchFilter::class, properties: ['email' => 'partial'])]
#[Post(
    uriTemplate: '/register',
    input: UserRegisterDto::class,
    processor: UserRegisterProcessor::class,
)]
#[Get(
    uriVariables: ['uuid'],
    security: IsGranted::Admin->value.' or object.uuid == user.getUuid()',
    provider: UserProvider::class,
)]
#[GetCollection(
    security: IsGranted::Admin->value,
    provider: UserProvider::class,
    stateOptions: new Options(SecurityUser::class)
)]
class UserModel
{
    #[ApiProperty(identifier: true)]
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
