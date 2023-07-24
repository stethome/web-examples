<?php

declare(strict_types=1);

namespace App\User\State;

use ApiPlatform\Metadata\CollectionOperationInterface;
use ApiPlatform\Metadata\Operation;
use ApiPlatform\State\ProviderInterface;
use App\User\ApiResource\UserModel;
use App\User\Repository\SecurityUserRepository;

final readonly class UserProvider implements ProviderInterface
{
    public function __construct(
        private SecurityUserRepository $repository,
    ) {
    }

    public function provide(Operation $operation, array $uriVariables = [], array $context = []): ?UserModel
    {
        if ($operation instanceof CollectionOperationInterface) {
            throw new \Exception('Not implemented');
        }

        $user = $this->repository->find($uriVariables['id']);

        return $user ? UserModel::fromSecurityUser($user) : null;
    }
}
