<?php

namespace App\Repository;

use App\Entity\IndemnitePersonnel;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<IndemnitePersonnel>
 *
 * @method IndemnitePersonnel|null find($id, $lockMode = null, $lockVersion = null)
 * @method IndemnitePersonnel|null findOneBy(array $criteria, array $orderBy = null)
 * @method IndemnitePersonnel[]    findAll()
 * @method IndemnitePersonnel[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class IndemnitePersonnelRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, IndemnitePersonnel::class);
    }

    public function add(IndemnitePersonnel $entity, bool $flush = false): void
    {
        $this->getEntityManager()->persist($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function remove(IndemnitePersonnel $entity, bool $flush = false): void
    {
        $this->getEntityManager()->remove($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

//    /**
//     * @return IndemnitePersonnel[] Returns an array of IndemnitePersonnel objects
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

//    public function findOneBySomeField($value): ?IndemnitePersonnel
//    {
//        return $this->createQueryBuilder('i')
//            ->andWhere('i.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
