<?php

declare(strict_types=1);

namespace App\Exam\Service\State;

use ApiPlatform\Metadata\Operation;
use ApiPlatform\State\ProcessorInterface;
use App\Exam\ApiResource\ExamModel;
use App\Exam\Entity\Exam;
use App\Exam\Repository\ExamRepository;
use App\User\Entity\SecurityUser;
use Symfony\Bundle\SecurityBundle\Security;
use Symfony\Component\Uid\Factory\UuidFactory;

final readonly class ExamCreateProcessor implements ProcessorInterface
{
    public function __construct(
        private UuidFactory $uuidFactory,
        private Security $security,
        private ExamRepository $examRepository,
    ) {
    }

    /**
     * @param ExamModel $data
     */
    public function process(mixed $data, Operation $operation, array $uriVariables = [], array $context = []): ExamModel
    {
        /** @var SecurityUser $user */
        $user = $this->security->getUser();

        $entity = new Exam(
            $this->uuidFactory->create(),
            $data->externalId,
            $user->getUserData(),
        );

        $this->examRepository->save($entity, true);

        return ExamModel::fromExam($entity);
    }
}
