<?php

namespace App\Repository;

use App\Entity\Deal;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Deal>
 *
 * @method Deal|null find($id, $lockMode = null, $lockVersion = null)
 * @method Deal|null findOneBy(array $criteria, array $orderBy = null)
 * @method Deal[]    findAll()
 * @method Deal[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class DealRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Deal::class);
    }

    public function save(Deal $entity, bool $flush = false): void
    {
        $this->getEntityManager()->persist($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function remove(Deal $entity, bool $flush = false): void
    {
        $this->getEntityManager()->remove($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

   /**
    * @return Deal[] Returns an array of featured deals
    * (sorted by number of comments descending and only deals created in the last week)
    */
   public function findFeaturedDeals(): array
   {
        return $this->createQueryBuilder('d')
            ->andWhere('d.createdAt > :lastWeek')
            ->setParameter('lastWeek', new \DateTime('-1 week'))
            ->leftJoin('d.comments', 'c')
            ->orderBy('SIZE(d.comments)', 'DESC')
            ->getQuery()
            ->getResult();
   }

    /**
    * @return Deal[] Returns an array of hot deals
    * (more than 100° and sorted by publication date descending)
    */
    public function findHotDeals(): array
    {
        return $this->createQueryBuilder('d')
            ->andWhere('d.notation > :hotNotation')
            ->setParameter('hotNotation', 100)
            ->orderBy('d.createdAt', 'DESC')
            ->getQuery()
            ->getResult();
    }

//    public function findOneBySomeField($value): ?Deal
//    {
//        return $this->createQueryBuilder('d')
//            ->andWhere('d.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
