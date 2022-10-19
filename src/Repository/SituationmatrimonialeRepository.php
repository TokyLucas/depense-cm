<?php

namespace App\Repository;

use App\Entity\Situationmatrimoniale;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Situationmatrimoniale>
 *
 * @method Situationmatrimoniale|null find($id, $lockMode = null, $lockVersion = null)
 * @method Situationmatrimoniale|null findOneBy(array $criteria, array $orderBy = null)
 * @method Situationmatrimoniale[]    findAll()
 * @method Situationmatrimoniale[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class SituationmatrimonialeRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Situationmatrimoniale::class);
    }

    public function add(Situationmatrimoniale $entity, bool $flush = false): void
    {
        $this->getEntityManager()->persist($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function remove(Situationmatrimoniale $entity, bool $flush = false): void
    {
        $this->getEntityManager()->remove($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

//    /**
//     * @return Situationmatrimoniale[] Returns an array of Situationmatrimoniale objects
//     */
//    public function findByExampleField($value): array
//    {
//        return $this->createQueryBuilder('s')
//            ->andWhere('s.exampleField = :val')
//            ->setParameter('val', $value)
//            ->orderBy('s.id', 'ASC')
//            ->setMaxResults(10)
//            ->getQuery()
//            ->getResult()
//        ;
//    }

//    public function findOneBySomeField($value): ?Situationmatrimoniale
//    {
//        return $this->createQueryBuilder('s')
//            ->andWhere('s.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
