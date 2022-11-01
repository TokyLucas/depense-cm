<?php

namespace App\Repository;

use App\Entity\BaremeIrsa;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

use Doctrine\Common\Collections\Criteria;
/**
 * @extends ServiceEntityRepository<BaremeIrsa>
 *
 * @method BaremeIrsa|null find($id, $lockMode = null, $lockVersion = null)
 * @method BaremeIrsa|null findOneBy(array $criteria, array $orderBy = null)
 * @method BaremeIrsa[]    findAll()
 * @method BaremeIrsa[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class BaremeIrsaRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, BaremeIrsa::class);
    }

    public function add(BaremeIrsa $entity, bool $flush = false): void
    {
        $this->getEntityManager()->persist($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function remove(BaremeIrsa $entity, bool $flush = false): void
    {
        $this->getEntityManager()->remove($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

   /**
    * @return BaremeIrsa[] Returns an array of BaremeIrsa objects
    */
   public function findByDate($date): array
   {
       return $this->createQueryBuilder('b')
           ->andWhere('b.date <= :date')
           ->setParameter('date', $date)
           ->orderBy('b.id', 'DESC')
           ->orderBy('b.date', 'DESC')
           ->setMaxResults(1)
           ->getQuery()
           ->getResult()
       ;
   }

//    public function findOneBySomeField($value): ?BaremeIrsa
//    {
//        return $this->createQueryBuilder('b')
//            ->andWhere('b.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
