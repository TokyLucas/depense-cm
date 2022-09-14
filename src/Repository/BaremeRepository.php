<?php

namespace App\Repository;

use App\Entity\Bareme;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Bareme>
 *
 * @method Bareme|null find($id, $lockMode = null, $lockVersion = null)
 * @method Bareme|null findOneBy(array $criteria, array $orderBy = null)
 * @method Bareme[]    findAll()
 * @method Bareme[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class BaremeRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Bareme::class);
    }

    public function add(Bareme $entity, bool $flush = false): void
    {
        $this->getEntityManager()->persist($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function adds(Bareme $entity): void
    {
        $conn = $this->getEntityManager()->getConnection();
        $sql = '
            INSERT INTO Bareme (datebareme, categorie, indice, v500, v501, v502, v503, v506, solde) 
            VALUES (:datebareme, :categorie, :indice, :v500, :v501, :v502, :v503, :v506, solde)
            ';
        $stmt = $conn->prepare($sql);
        $conn->executeUpdate($sql, [
            'datebareme' => $entity->getDatebareme()->format("Y-m-d"),
            'categorie' => $entity->getCategorie(),
            'indice' => $entity->getIndice(),
            'v500' => $entity->getV500(),
            'v501' => $entity->getV501(),
            'v502' => $entity->getV502(),
            'v503' => $entity->getV503(),
            'v506' => $entity->getV506(),
            'solde' => $entity->getSolde(),
        ]);
        $this->getEntityManager()->clear();
    }

    public function flush(): void
    {
        $this->getEntityManager()->flush();
    }

    public function clear(): void
    {
        $this->getEntityManager()->clear();
    }

    public function remove(Bareme $entity, bool $flush = false): void
    {
        $this->getEntityManager()->remove($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

   /**
    * @return BaremePersonnel[] Returns an array of BaremePersonnel objects
    */
    public function findByDatebareme($position): array
    {
        $conn = $this->getEntityManager()->getConnection();
        $sql = '';
        if(strtolower($position) == 'asc'){
            $sql .= '
            SELECT * FROM bareme WHERE datebareme = (select max(datebareme) from bareme) ORDER BY id asc LIMIT 1
            ';
        } else {
            $sql .= '
            SELECT * FROM bareme WHERE datebareme = (select max(datebareme) from bareme) ORDER BY id desc LIMIT 1
            ';
        }
        $stmt = $conn->prepare($sql);
        $resultSet = $stmt->executeQuery(['position' => $position]);

        // returns an array of arrays (i.e. a raw data set)
        $results = $resultSet->fetchAllAssociative();
        return $results;
    }
//    /**
//     * @return Bareme[] Returns an array of Bareme objects
//     */
//    public function findByExampleField($value): array
//    {
//        return $this->createQueryBuilder('b')
//            ->andWhere('b.exampleField = :val')
//            ->setParameter('val', $value)
//            ->orderBy('b.id', 'ASC')
//            ->setMaxResults(10)
//            ->getQuery()
//            ->getResult()
//        ;
//    }

//    public function findOneBySomeField($value): ?Bareme
//    {
//        return $this->createQueryBuilder('b')
//            ->andWhere('b.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
