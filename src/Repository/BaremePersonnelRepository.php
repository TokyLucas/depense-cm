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
    public function findByPersonnelId($personnel_id, $datebareme): array
    {
        $conn = $this->getEntityManager()->getConnection();

        $sql = 
        '
            select * from (
                select 
                    bareme.*, 
                    v.id as personnel_id,
                    v.nom,
                    v.prenom,
                    v.direction_id,
                    v.direction,
                    v.contrat_id,
                    v.contrat
                from v_personnel_details as v
                left join bareme on bareme.indice = v.indice and bareme.categorie = v.categorie
                where v.id = :personnel_id and ifnull(datebareme,:datebareme) <= :datebareme order by datebareme desc
            )  as v  group by personnel_id
        ';
        // $sql = '
        //     SELECT * FROM v_bareme_par_personnel v
        //     WHERE v.direction_id = :direction_id
        //     AND datebareme = (select datebareme from bareme order by datebareme desc limit 1)
        //     ';
        $stmt = $conn->prepare($sql);
        $resultSet = $stmt->executeQuery([
            'personnel_id' => $personnel_id,
            'datebareme' => $datebareme
        ]);

        // returns an array of arrays (i.e. a raw data set)
        $results = $resultSet->fetchAllAssociative();
        return $results;
    }

   /**
    * @return BaremePersonnel[] Returns an array of BaremePersonnel objects
    */
   public function findByDirection($direction_id, $datebareme, $contrat_id = -1): array
   {
        $conn = $this->getEntityManager()->getConnection();
        if($direction_id != "-1" && $contrat_id == "-1"){
            $sql = 
            '
                select * from (
                    select 
                        bareme.*, 
                        v.id as personnel_id,
                        v.nom,
                        v.prenom,
                        v.direction_id,
                        v.direction,
                        v.contrat_id,
                        v.contrat
                    from v_personnel_details as v
                    left join bareme on bareme.indice = v.indice and bareme.categorie = v.categorie
                    where ifnull(datebareme,:datebareme) <= :datebareme and direction_id = :direction_id order by datebareme desc 
                ) as v  group by personnel_id;
            ';
            $stmt = $conn->prepare($sql);
            $resultSet = $stmt->executeQuery([
                'direction_id' => $direction_id,
                'datebareme' => $datebareme
            ]);
        } else if($direction_id == "-1" && $contrat_id != "-1"){
            $sql = 
            '
                select * from (
                    select 
                        bareme.*, 
                        v.id as personnel_id,
                        v.nom,
                        v.prenom,
                        v.direction_id,
                        v.direction,
                        v.contrat_id,
                        v.contrat
                    from v_personnel_details as v
                    left join bareme on bareme.indice = v.indice and bareme.categorie = v.categorie
                    where ifnull(datebareme,:datebareme) <= :datebareme and contrat_id = :contrat_id order by datebareme desc 
                ) as v  group by personnel_id;
            ';
            $stmt = $conn->prepare($sql);
            $resultSet = $stmt->executeQuery([
                'contrat_id' => $contrat_id,
                'datebareme' => $datebareme
            ]);
        } else if($direction_id != "-1" && $contrat_id != "-1") {
            $sql = 
            '
                select * from (
                    select 
                        bareme.*, 
                        v.id as personnel_id,
                        v.nom,
                        v.prenom,
                        v.direction_id,
                        v.direction,
                        v.contrat_id,
                        v.contrat
                    from v_personnel_details as v
                    left join bareme on bareme.indice = v.indice and bareme.categorie = v.categorie
                    where ifnull(datebareme,:datebareme) <= :datebareme and direction_id = :direction_id and contrat_id = :contrat_id order by datebareme desc 
                ) as v  group by personnel_id;
            ';
            $stmt = $conn->prepare($sql);
            $resultSet = $stmt->executeQuery([
                'direction_id' => $direction_id,
                'contrat_id' => $contrat_id,
                'datebareme' => $datebareme
            ]);
        }


        // $sql = '
        //     SELECT * FROM v_bareme_par_personnel v
        //     WHERE v.direction_id = :direction_id
        //     AND datebareme = (select datebareme from bareme order by datebareme desc limit 1)
        //     ';
        

        // returns an array of arrays (i.e. a raw data set)
        $results = $resultSet->fetchAllAssociative();
        return $results;
   }

   /**
    * @return BaremePersonnel[] Returns an array of BaremePersonnel objects
    */
    public function findByDatebareme($datebareme): array
    {
         $conn = $this->getEntityManager()->getConnection();
 
         $sql = '
            select * from (
                select 
                    bareme.*, 
                    v_personnel_details.id as personnel_id,
                    v_personnel_details.nom,
                    v_personnel_details.prenom,
                    v_personnel_details.direction_id,
                    v_personnel_details.direction,
                    v_personnel_details.contrat_id,
                    v_personnel_details.contrat
                from v_personnel_details
                left join bareme on bareme.indice = v_personnel_details.indice and bareme.categorie = v_personnel_details.categorie
                and datebareme <= :datebareme order by datebareme desc
            ) as v group by personnel_id      
            ';
        //  $sql = '
        //      SELECT * FROM v_bareme_par_personnel v
        //      WHERE datebareme = (select datebareme from bareme order by datebareme desc limit 1)
        //      ';
         $stmt = $conn->prepare($sql);
         $resultSet = $stmt->executeQuery([
            'datebareme' => $datebareme
         ]);
 
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
