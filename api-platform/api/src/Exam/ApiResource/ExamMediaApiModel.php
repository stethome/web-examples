<?php

declare(strict_types=1);

namespace App\Exam\ApiResource;

use ApiPlatform\Doctrine\Orm\State\Options;
use ApiPlatform\Metadata\ApiResource;
use ApiPlatform\Metadata\Get;
use App\Exam\Entity\Exam;
use App\Exam\Service\State\ExamMediaApiTokenProvider;

#[ApiResource(
    operations: [
        new Get(
            uriTemplate: '/exam_models/{uuid}/token',
            uriVariables: ['uuid'],
            output: ExamMediaApiModel::class,
            provider: ExamMediaApiTokenProvider::class,
        ),
    ],
    stateOptions: new Options(Exam::class),
)]
final readonly class ExamMediaApiModel
{
    public function __construct(
        public string $mediaUrl,
        public string $token,
        public string $externalId,
    ) {
    }
}
