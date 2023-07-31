<?php

declare(strict_types=1);

namespace App\Exam\ApiResource;

use ApiPlatform\Doctrine\Orm\State\Options;
use ApiPlatform\Metadata\ApiProperty;
use ApiPlatform\Metadata\ApiResource;
use ApiPlatform\Metadata\Get;
use ApiPlatform\Metadata\GetCollection;
use ApiPlatform\Metadata\Post;
use App\Exam\Entity\Exam;
use App\Exam\Service\State\ExamCreateProcessor;
use App\Exam\Service\State\ExamProvider;
use App\Exam\Service\Validator\Constraints\StethoMeId;
use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Component\Uid\Uuid;

#[ApiResource(
    operations: [
        new Post(processor: ExamCreateProcessor::class),
        new Get(uriVariables: ['uuid']),
        new GetCollection(),
    ],
    normalizationContext: ['groups' => ['read']],
    denormalizationContext: ['groups' => ['write']],
    provider: ExamProvider::class,
    stateOptions: new Options(Exam::class),
)]
class ExamModel
{
    #[ApiProperty(identifier: true)]
    #[Groups('read')]
    public Uuid $uuid;

    #[StethoMeId]
    #[Groups(['read', 'write'])]
    public string $externalId;

    public static function fromExam(Exam $exam): ExamModel
    {
        $self = new self();

        $self->uuid = $exam->getUuid();
        $self->externalId = $exam->getExternalId();

        return $self;
    }
}
