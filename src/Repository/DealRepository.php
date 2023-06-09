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
    * Find featured deals
    * @return Deal[] Returns an array of featured deals
    * (sorted by number of comments descending and only deals created in the last week)
    */
   public function findFeaturedDeals(): array
   {
        return $this->createQueryBuilder('d')
            ->andWhere('d.createdAt > :lastWeek')
            ->setParameter('lastWeek', new \DateTime('-1 week'))
            ->andWhere('d.is_expired = :is_expired')
            ->setParameter('is_expired', false)
            ->leftJoin('d.comments', 'c')
            ->orderBy('SIZE(d.comments)', 'DESC')
            ->getQuery()
            ->getResult();
   }

    /**
    * Find hot deals
    * @return Deal[] Returns an array of hot deals
    * (more than 100° and sorted by publication date descending)
    */
    public function findHotDeals(): array
    {
        return $this->createQueryBuilder('d')
            ->andWhere('d.notation > :notation')
            ->setParameter('notation', 100)
            ->andWhere('d.is_expired = :is_expired')
            ->setParameter('is_expired', false)
            ->orderBy('d.createdAt', 'DESC')
            ->getQuery()
            ->getResult();
    }

    /**
    * Find hottest deals
    * @return Deal[] Returns an array of the sixteen hottest deals
    * (sorted by notation descending)
    */
    public function findHottestDeals(): array
    {
        return $this->createQueryBuilder('d')
            ->andWhere('d.category = :category')    
            ->setParameter('category', 'tips')
            ->andWhere('d.is_expired = :is_expired')
            ->setParameter('is_expired', false)
            ->orderBy('d.notation', 'DESC')
            ->setMaxResults(16)
            ->getQuery()
            ->getResult();
    }

    /**
     * Find deals of coupon type
     * @return Deal[] Returns an array of deals
     * (of category Coupon)
     */
    public function findCoupons(): array
    {
        return $this->createQueryBuilder('d')
            ->andWhere('d.category = :category')
            ->setParameter('category', 'Coupon')
            ->andWhere('d.is_expired = :is_expired')
            ->setParameter('is_expired', false)
            ->orderBy('d.createdAt', 'DESC')
            ->getQuery()
            ->getResult();
    }

    /**
     * Find deals by group
     * @return Deal[] Returns an array of deals by group
     * @param int $group_id The id of the deal group
     */
    public function findDealsByGroup(int $group_id): array
    {
        return $this->createQueryBuilder('d')
            ->andWhere('d.dealGroup = :group_id')
            ->setParameter('group_id', $group_id)
            ->andWhere('d.is_expired = :is_expired')
            ->setParameter('is_expired', false)
            ->orderBy('d.createdAt', 'DESC')
            ->getQuery()
            ->getResult();
    }

    /**
     * Find deal by searching value on title or content (research bar) or group name (JOIN)
     * @return Deal[] Returns an array of deals by searching value
     * @param string $search_value The value of the research
     */
    public function searchedDeals(string $search_value): array
    {
        return $this->createQueryBuilder('d')
        ->leftJoin('d.dealGroup', 'g')
        ->andWhere('d.title LIKE :search_value')
        ->orWhere('d.description LIKE :search_value')
        ->orWhere('g.name LIKE :search_value')
        ->andWhere('d.is_expired = :is_expired')
        ->setParameter('search_value', '%'.$search_value.'%')
        ->setParameter('is_expired', false)
        ->orderBy('d.createdAt', 'DESC')
        ->getQuery()
        ->getResult();
    }

    /**
     * Find number of deals posted by user
     * @return int Returns the number of deals posted by user
     * @param int $id_user The id of the user
     */
    public function findNumberOfDealsByUser(int $id_user): ?int
    {
        return $this->createQueryBuilder('d')
            ->select('count(d.id)')
            ->andWhere('d.userCreated = :id_user')
            ->setParameter('id_user', $id_user)
            ->getQuery()
            ->getSingleScalarResult();
    }

    /**
     * Find deals posted by user
     * @return Deal[] Returns deals posted by user
     * @param int $id_user The id of the user
     */
    public function findDealsByUser(int $id_user): array
    {
        return $this->createQueryBuilder('d')
            ->andWhere('d.userCreated = :id_user')
            ->setParameter('id_user', $id_user)
            ->orderBy('d.createdAt', 'DESC')
            ->getQuery()
            ->getResult();
    }

    /**
     * Find rating of the hottest deal posted by user
     * @return int Returns the rate of the hottest deal posted by user
     * @param int $id_user The id of the user
     */
    public function findRateHottestDealByUser(int $id_user): ?int
    {
        $result = $this->createQueryBuilder('d')
        ->select('d.notation')
        ->andWhere('d.userCreated = :id_user')
        ->setParameter('id_user', $id_user)
        ->orderBy('d.notation', 'DESC')
        ->setMaxResults(1)
        ->getQuery()
        ->getOneOrNullResult();

        return $result !== null ? $result['notation'] : null;
    }

    /**
     * Find average rating of deals posted over 1 rolling year by user
     * @return int Returns the average rating of deals posted over 1 rolling year by user
     * @param int $id_user The id of the user
     */
    public function findAverageRateDealsByUser(int $id_user): ?int
    {
        return $this->createQueryBuilder('d')
            ->select('AVG(d.notation)')
            ->andWhere('d.userCreated = :id_user')
            ->andWhere('d.createdAt > :lastYear')
            ->setParameter('id_user', $id_user)
            ->setParameter('lastYear', new \DateTime('-1 year'))
            ->getQuery()
            ->getSingleScalarResult();
    }

    /**
     * Percentage of posted deals that became hot by user
     * @return float Returns the rate of posted deals that became hot by user
     * @param int $id_user The id of the user
     */
    public function percentageHotPostedDealByUser(int $id_user): ?float
    {
        $totalDeals = $this->findNumberOfDealsByUser($id_user);
        $hotDeals = $this->createQueryBuilder('d')
            ->select('count(d.id)')
            ->andWhere('d.userCreated = :id_user')
            ->andWhere('d.notation > :notation')
            ->setParameter('id_user', $id_user)
            ->setParameter('notation', 100)
            ->getQuery()
            ->getSingleScalarResult();

        if ($totalDeals > 0) {
            return round($hotDeals / $totalDeals, 2);
        } else {
            return null;
        }
    }

    /**
     * Find weekly deals
     * @return float Returns a list of weekly deals
     */
    public function findWeeklyDeals(): array
    {
        return $this->createQueryBuilder('d')
            ->andWhere('d.createdAt > :lastWeek')
            ->setParameter('lastWeek', new \DateTime('-1 week'))
            ->orderBy('d.createdAt', 'DESC')
            ->getQuery()
            ->getResult();
    }
}
