<?php

namespace App\Repository;

use App\Entity\SavedDeal;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<SavedDeal>
 *
 * @method SavedDeal|null find($id, $lockMode = null, $lockVersion = null)
 * @method SavedDeal|null findOneBy(array $criteria, array $orderBy = null)
 * @method SavedDeal[]    findAll()
 * @method SavedDeal[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class SavedDealRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, SavedDeal::class);
    }

    public function save(SavedDeal $entity, bool $flush = false): void
    {
        $this->getEntityManager()->persist($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function remove(SavedDeal $entity, bool $flush = false): void
    {
        $this->getEntityManager()->remove($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function findSavedDealsByUser(int $user_id): array
    {
        return $this->createQueryBuilder('s')
            ->andWhere('s.user = :user_id')
            ->setParameter('user_id', $user_id)
            ->orderBy('s.createdAt', 'DESC') // Tri par date décroissante
            ->getQuery()
            ->getResult();
    }

    public function findNumberOfSavedDealsByUser(int $user_id): int
    {
        return $this->createQueryBuilder('s')
            ->select('count(s.id)')
            ->andWhere('s.user = :user_id')
            ->setParameter('user_id', $user_id)
            ->getQuery()
            ->getSingleScalarResult();
    }
}
