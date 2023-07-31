<?php

namespace App\User\Service\State;

use ApiPlatform\Metadata\Operation;
use ApiPlatform\State\ProcessorInterface;
use App\User\ApiResource\UserModel;
use App\User\Dto\UserRegisterDto;
use App\User\Entity\SecurityUser;
use App\User\Entity\UserData;
use App\User\Repository\SecurityUserRepository;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Uid\Factory\UuidFactory;

final readonly class UserRegisterProcessor implements ProcessorInterface
{
    public function __construct(
        private UuidFactory $uuidFactory,
        private UserPasswordHasherInterface $passwordHasher,
        private SecurityUserRepository $repository,
    ) {
    }

    /**
     * @param UserRegisterDto $data
     */
    public function process(mixed $data, Operation $operation, array $uriVariables = [], array $context = []): UserModel
    {
        $userData = new UserData(
            $this->uuidFactory->create(),
            $data->name,
            $data->surname,
        );

        $securityUser = new SecurityUser(
            $this->uuidFactory->create(),
            $userData,
            $data->email,
        );

        $securityUser->setPassword($this->passwordHasher->hashPassword(
            $securityUser,
            $data->password,
        ));

        $this->repository->save($securityUser, true);

        return UserModel::fromSecurityUser($securityUser);
    }
}
