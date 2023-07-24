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

    public function provide(Operation $operation, array $uriVariables = [], array $context = []): UserModel|\Generator|null
    {
        if ($operation instanceof CollectionOperationInterface) {
            // @todo filters, pagination
            return $this->provideCollection();
        }

        $user = $this->repository->find($uriVariables['id']);

        return $user ? UserModel::fromSecurityUser($user) : null;
    }

    private function provideCollection(): \Generator
    {
        foreach ($this->repository->findAll() as $user) {
            yield UserModel::fromSecurityUser($user);
        }
    }
}
