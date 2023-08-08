<?php

declare(strict_types=1);

namespace App\Exam\Service\State;

use ApiPlatform\Doctrine\Orm\State\ItemProvider;
use ApiPlatform\Metadata\Operation;
use ApiPlatform\State\ProviderInterface;
use App\Exam\ApiResource\ExamMediaApiModel;
use App\Exam\Entity\Exam;
use App\Exam\Service\Client\StethoMeApiClient;
use Symfony\Component\DependencyInjection\Attribute\Autowire;

/**
 * @implements ProviderInterface<ExamMediaApiModel>
 */
final readonly class ExamMediaApiTokenProvider implements ProviderInterface
{
    public function __construct(
        #[Autowire('%stethome.api.media_url%')] private string $urlMedia,
        private ItemProvider $itemProvider,
        private StethoMeApiClient $client,
    ) {
    }

    public function provide(Operation $operation, array $uriVariables = [], array $context = []): ExamMediaApiModel|null
    {
        /** @var Exam|null $exam */
        $exam = $this->itemProvider->provide($operation, $uriVariables, $context);
        if (!$exam) {
            return null;
        }

        $response = $this->client->getClientMediaToken($exam->getExternalId());

        return new ExamMediaApiModel(
            $this->urlMedia,
            $response->toArray()['token'],
            $exam->getExternalId(),
        );
    }
}
