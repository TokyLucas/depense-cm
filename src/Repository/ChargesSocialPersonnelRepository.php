<?php

namespace App\Repository;

use App\Entity\ChargesSocialPersonnel;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<ChargesSocialPersonnel>
 *
 * @method ChargesSocialPersonnel|null find($id, $lockMode = null, $lockVersion = null)
 * @method ChargesSocialPersonnel|null findOneBy(array $criteria, array $orderBy = null)
 * @method ChargesSocialPersonnel[]    findAll()
 * @method ChargesSocialPersonnel[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ChargesSocialPersonnelRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, ChargesSocialPersonnel::class);
    }

    public function add(ChargesSocialPersonnel $entity, bool $flush = false): void
    {
        $this->getEntityManager()->persist($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function remove(ChargesSocialPersonnel $entity, bool $flush = false): void
    {
        $this->getEntityManager()->remove($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

//    /**
//     * @return ChargesSocialPersonnel[] Returns an array of ChargesSocialPersonnel objects
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

//    public function findOneBySomeField($value): ?ChargesSocialPersonnel
//    {
//        return $this->createQueryBuilder('c')
//            ->andWhere('c.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
