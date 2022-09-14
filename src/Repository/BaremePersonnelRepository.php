<?php

namespace App\Repository;

use App\Entity\BaremePersonnel;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<BaremePersonnel>
 *
 * @method BaremePersonnel|null find($id, $lockMode = null, $lockVersion = null)
 * @method BaremePersonnel|null findOneBy(array $criteria, array $orderBy = null)
 * @method BaremePersonnel[]    findAll()
 * @method BaremePersonnel[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class BaremePersonnelRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, BaremePersonnel::class);
    }

    public function add(BaremePersonnel $entity, bool $flush = false): void
    {
        $this->getEntityManager()->persist($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function remove(BaremePersonnel $entity, bool $flush = false): void
    {
        $this->getEntityManager()->remove($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

   /**
    * @return BaremePersonnel[] Returns an array of BaremePersonnel objects
    */
   public function findByDirection($direction_id): array
   {
        $conn = $this->getEntityManager()->getConnection();

        $sql = '
            SELECT * FROM v_bareme_par_personnel v
            WHERE v.direction_id = :direction_id
            AND datebareme = (select datebareme from bareme order by datebareme desc limit 1)
            ';
        $stmt = $conn->prepare($sql);
        $resultSet = $stmt->executeQuery(['direction_id' => $direction_id]);

        // returns an array of arrays (i.e. a raw data set)
        $results = $resultSet->fetchAllAssociative();
        return $results;
   }


//    public function findOneBySomeField($value): ?BaremePersonnel
//    {
//        return $this->createQueryBuilder('b')
//            ->andWhere('b.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
