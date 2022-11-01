<?php

namespace App\Repository;

use App\Entity\IntervalIrsa;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<IntervalIrsa>
 *
 * @method IntervalIrsa|null find($id, $lockMode = null, $lockVersion = null)
 * @method IntervalIrsa|null findOneBy(array $criteria, array $orderBy = null)
 * @method IntervalIrsa[]    findAll()
 * @method IntervalIrsa[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class IntervalIrsaRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, IntervalIrsa::class);
    }

    public function add(IntervalIrsa $entity, bool $flush = false): void
    {
        $this->getEntityManager()->persist($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function remove(IntervalIrsa $entity, bool $flush = false): void
    {
        $this->getEntityManager()->remove($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

//    /**
//     * @return IntervalIrsa[] Returns an array of IntervalIrsa objects
//     */
//    public function findByExampleField($value): array
//    {
//        return $this->createQueryBuilder('i')
//            ->andWhere('i.exampleField = :val')
//            ->setParameter('val', $value)
//            ->orderBy('i.id', 'ASC')
//            ->setMaxResults(10)
//            ->getQuery()
//            ->getResult()
//        ;
//    }

//    public function findOneBySomeField($value): ?IntervalIrsa
//    {
//        return $this->createQueryBuilder('i')
//            ->andWhere('i.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
