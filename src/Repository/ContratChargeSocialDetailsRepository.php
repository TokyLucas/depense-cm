<?php

namespace App\Repository;

use App\Entity\ContratChargeSocialDetails;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<ContratChargeSocialDetails>
 *
 * @method ContratChargeSocialDetails|null find($id, $lockMode = null, $lockVersion = null)
 * @method ContratChargeSocialDetails|null findOneBy(array $criteria, array $orderBy = null)
 * @method ContratChargeSocialDetails[]    findAll()
 * @method ContratChargeSocialDetails[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ContratChargeSocialDetailsRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, ContratChargeSocialDetails::class);
    }

    public function add(ContratChargeSocialDetails $entity, bool $flush = false): void
    {
        $this->getEntityManager()->persist($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function remove(ContratChargeSocialDetails $entity, bool $flush = false): void
    {
        $this->getEntityManager()->remove($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

//    /**
//     * @return ContratChargeSocialDetails[] Returns an array of ContratChargeSocialDetails objects
//     */
//    public function findByExampleField($value): array
//    {
//        return $this->createQueryBuilder('c')
//            ->andWhere('c.exampleField = :val')
//            ->setParameter('val', $value)
//            ->orderBy('c.id', 'ASC')
//            ->setMaxResults(10)
//            ->getQuery()
//            ->getResult()
//        ;
//    }

//    public function findOneBySomeField($value): ?ContratChargeSocialDetails
//    {
//        return $this->createQueryBuilder('c')
//            ->andWhere('c.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
