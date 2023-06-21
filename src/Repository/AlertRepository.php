<?php

namespace App\Repository;

use App\Entity\Alert;
use App\Entity\Deal;
use App\Entity\User;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Alert>
 *
 * @method Alert|null find($id, $lockMode = null, $lockVersion = null)
 * @method Alert|null findOneBy(array $criteria, array $orderBy = null)
 * @method Alert[]    findAll()
 * @method Alert[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class AlertRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Alert::class);
    }

    public function save(Alert $entity, bool $flush = false): void
    {
        $this->getEntityManager()->persist($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function remove(Alert $entity, bool $flush = false): void
    {
        $this->getEntityManager()->remove($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

     /**
     * Find alerts by user
     * @return Alert[] Returns an array of alerts by user
     * @param int $id_user The id of the user
     */
    public function findAlertsByUser(int $id_user): array
    {
        return $this->createQueryBuilder('a')
            ->andWhere('a.user = :id_user')
            ->setParameter('id_user', $id_user)
            ->getQuery()
            ->getResult();
    }

    /**
     * Find number of alerts by user
     * @return int Returns the number of alerts by user
     * @param int $id_user The id of the user
     */
    public function findNumberOfAlertsByUser(int $id_user): ?int
    {
        return $this->createQueryBuilder('a')
            ->select('count(a.id)')
            ->andWhere('a.user = :id_user')
            ->setParameter('id_user', $id_user)
            ->getQuery()
            ->getSingleScalarResult();
    }

    /**
     * Find alerted deals by user, check new deal if correpond to alert
     * @return array Returns an array of alerted deals by user
     * @param int $id_user The id of the user
     */
    public function findAlertedDealsByUser(int $id_user): array
    {
        $user = $this->getEntityManager()->getReference(User::class, $id_user);

        $alerts = $this->findBy(['user' => $user]);

        $deals = [];

        foreach ($alerts as $alert) {
            $matchingDeals = $this->getEntityManager()->createQueryBuilder()
                ->select('d')
                ->from(Deal::class, 'd')
                ->where('d.title LIKE :keyword')
                ->andWhere('d.notation >= :minTemperature')
                ->setParameter('keyword', '%' . $alert->getKeyword() . '%')
                ->setParameter('minTemperature', $alert->getMinTemperature())
                ->getQuery()
                ->getResult();

            $deals = array_merge($deals, $matchingDeals);

            $uniqueDeals = array_unique($deals, SORT_REGULAR);
        }

        return $uniqueDeals;
    }
}
