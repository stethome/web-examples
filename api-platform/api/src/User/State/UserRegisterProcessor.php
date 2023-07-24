<?php

namespace App\User\State;

use ApiPlatform\Metadata\Operation;
use ApiPlatform\State\ProcessorInterface;
use App\Shared\Service\OrmProcessor;
use App\User\ApiResource\UserModel;
use App\User\Dto\UserRegisterDto;
use App\User\Entity\SecurityUser;
use App\User\Entity\UserData;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\PasswordHasher\PasswordHasherInterface;
use Symfony\Component\Uid\Factory\UuidFactory;

final class UserRegisterProcessor extends OrmProcessor
{
    public function __construct(
        ProcessorInterface $persistProcessor,
        ProcessorInterface $removeProcessor,
        UuidFactory $uuidFactory,
        private readonly UserPasswordHasherInterface $passwordHasher,
    ) {
        parent::__construct($persistProcessor, $removeProcessor, $uuidFactory);
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

        $this->persistProcessor->process($securityUser, $operation, $uriVariables, $context);

        return UserModel::fromSecurityUser($securityUser);
    }
}
