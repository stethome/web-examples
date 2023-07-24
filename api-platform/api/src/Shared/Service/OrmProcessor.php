<?php

declare(strict_types=1);

namespace App\Shared\Service;

use ApiPlatform\State\ProcessorInterface;
use Symfony\Component\Uid\Factory\UuidFactory;

abstract class OrmProcessor implements ProcessorInterface
{
    public function __construct(
        protected readonly ProcessorInterface $persistProcessor,
        protected readonly ProcessorInterface $removeProcessor,
        protected readonly UuidFactory $uuidFactory,
    ) {
    }
}
