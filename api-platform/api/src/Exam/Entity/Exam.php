<?php

declare(strict_types=1);

namespace App\Exam\Entity;

use App\Shared\Entity\AbstractUuidEntity;
use App\User\Entity\UserData;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Uid\Uuid;

#[ORM\Entity]
#[ORM\Table('exams')]
class Exam extends AbstractUuidEntity
{
    public function __construct(Uuid $uuid, string $externalId, UserData $owner, \DateTimeImmutable $examinedAt)
    {
        parent::__construct($uuid);
        $this->externalId = $externalId;
        $this->owner = $owner;
        $this->examinedAt = $examinedAt;
    }

    #[ORM\Column(name: 'external_id', type: Types::STRING, length: 32)]
    protected string $externalId;

    #[ORM\ManyToOne(targetEntity: UserData::class)]
    #[ORM\JoinColumn(name: 'owner', referencedColumnName: 'uuid', nullable: false)]
    protected UserData $owner;

    #[ORM\Column(name: 'examined_at', type: Types::DATETIMETZ_IMMUTABLE, nullable: false)]
    protected \DateTimeImmutable $examinedAt;

    public function getExternalId(): string
    {
        return $this->externalId;
    }

    public function getOwner(): UserData
    {
        return $this->owner;
    }

    public function getExaminedAt(): \DateTimeImmutable
    {
        return $this->examinedAt;
    }
}
