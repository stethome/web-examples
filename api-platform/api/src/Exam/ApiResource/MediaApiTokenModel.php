<?php

declare(strict_types=1);

namespace App\Exam\ApiResource;

use ApiPlatform\Doctrine\Orm\State\Options;
use ApiPlatform\Metadata\ApiResource;
use ApiPlatform\Metadata\Get;
use ApiPlatform\Metadata\GetCollection;
use App\Exam\Entity\Exam;
use App\Exam\Service\State\ExamMediaApiTokenProvider;

#[ApiResource(
    operations: [
        new Get(
            uriTemplate: '/exam_models/{uuid}/token',
            uriVariables: ['uuid'],
            output: MediaApiTokenModel::class,
            provider: ExamMediaApiTokenProvider::class,
        ),
    ],
    stateOptions: new Options(Exam::class),
)]
final readonly class MediaApiTokenModel
{
    public function __construct(
        public string $token,
    ) {
    }
}
