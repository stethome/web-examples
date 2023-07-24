<?php

declare(strict_types=1);

namespace App\User\Repository;

use App\User\Entity\SecurityUser;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<SecurityUser>
 *
 * @method SecurityUser|null find($id, $lockMode = null, $lockVersion = null)
 * @method SecurityUser|null findOneBy(array $criteria, array $orderBy = null)
 * @method SecurityUser[]    findAll()
 * @method SecurityUser[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class SecurityUserRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, SecurityUser::class);
    }

    public function save(SecurityUser $entity, bool $flush = false): void
    {
        $this->getEntityManager()->persist($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function remove(SecurityUser $entity, bool $flush = false): void
    {
        $this->getEntityManager()->remove($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }
}
