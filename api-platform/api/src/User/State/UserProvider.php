<?php

declare(strict_types=1);

namespace App\User\State;

use ApiPlatform\Doctrine\Orm\Paginator;
use ApiPlatform\Doctrine\Orm\State\CollectionProvider;
use ApiPlatform\Doctrine\Orm\State\ItemProvider;
use ApiPlatform\Metadata\CollectionOperationInterface;
use ApiPlatform\Metadata\Operation;
use ApiPlatform\State\Pagination\PaginatorInterface;
use ApiPlatform\State\Pagination\TraversablePaginator;
use ApiPlatform\State\ProviderInterface;
use App\User\ApiResource\UserModel;

final readonly class UserProvider implements ProviderInterface
{
    public function __construct(
        private ItemProvider $itemProvider,
        private CollectionProvider $collectionProvider,
    ) {
    }

    public function provide(Operation $operation, array $uriVariables = [], array $context = []): UserModel|PaginatorInterface|null
    {
        if ($operation instanceof CollectionOperationInterface) {
            return $this->provideCollection($operation, $uriVariables, $context);
        }

        return $this->provideItem($operation, $uriVariables, $context);
    }

    private function provideCollection(Operation $operation, array $uriVariables, array $context): PaginatorInterface
    {
        /** @var Paginator $paginator */
        $paginator = $this->collectionProvider->provide($operation, $uriVariables, $context);

        return new TraversablePaginator(
            $this->mapPaginator($paginator),
            $paginator->getCurrentPage(),
            $paginator->getItemsPerPage(),
            $paginator->getTotalItems(),
        );
    }

    private function provideItem(Operation $operation, array $uriVariables, array $context): ?UserModel
    {
        $user = $this->itemProvider->provide($operation, $uriVariables, $context);

        return $user ? UserModel::fromSecurityUser($user) : null;
    }

    private function mapPaginator(Paginator $paginator): \Generator
    {
        foreach ($paginator as $item) {
            yield UserModel::fromSecurityUser($item);
        }
    }
}
