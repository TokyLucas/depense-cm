<?php

namespace App\Repository;

use App\Entity\BaremePersonnelTemporaire;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<BaremePersonnelTemporaire>
 *
 * @method BaremePersonnelTemporaire|null find($id, $lockMode = null, $lockVersion = null)
 * @method BaremePersonnelTemporaire|null findOneBy(array $criteria, array $orderBy = null)
 * @method BaremePersonnelTemporaire[]    findAll()
 * @method BaremePersonnelTemporaire[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class BaremePersonnelTemporaireRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, BaremePersonnelTemporaire::class);
    }

    public function add(BaremePersonnelTemporaire $entity, bool $flush = false): void
    {
        $this->getEntityManager()->persist($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function remove(BaremePersonnelTemporaire $entity, bool $flush = false): void
    {
        $this->getEntityManager()->remove($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

        /**
    * @return BaremePersonnelTemporaire[] Returns an array of BaremePersonnelTemporaire objects
    */
    public function findByPersonnelId($personnel_id, $date): array
    {
        $conn = $this->getEntityManager()->getConnection();

        $sql = 
        '
            select * from   ( 
            select 
                baremepersonneltemporaire.*, 
                v.id as personnel_id,
                v.nom,
                v.prenom,
                v.direction_id,
                v.direction,
                v.date_debut_contrat,
                v.date_fin_contrat_temporaire,
                v.duree_contrat_temporaire,
                v.heure_par_jour_temporaire
            from v_personnel_details as v
            join baremepersonneltemporaire on baremepersonneltemporaire.indice = v.indice
            where v.id = :personnel_id and date <= :date order by date desc
            ) as t group by personnel_id
        ';
        // $sql = '
        //     SELECT * FROM v_bareme_par_personnel v
        //     WHERE v.direction_id = :direction_id
        //     AND date = (select date from baremepersonneltemporaire order by date desc limit 1)
        //     ';
        $stmt = $conn->prepare($sql);
        $resultSet = $stmt->executeQuery([
            'personnel_id' => $personnel_id,
            'date' => $date
        ]);

        // returns an array of arrays (i.e. a raw data set)
        $results = $resultSet->fetchAllAssociative();
        return $results;
    }

   /**
    * @return BaremePersonnelTemporaire[] Returns an array of BaremePersonnelTemporaire objects
    */
   public function findByDirection($direction_id, $date): array
   {
        $conn = $this->getEntityManager()->getConnection();

        $sql = 
        '
            select * from (
                select 
                    baremepersonneltemporaire.*, 
                    v.id as personnel_id,
                    v.nom,
                    v.prenom,
                    v.direction_id,
                    v.direction,
                    v.contrat_id,
                    v.contrat
                from v_personnel_details as v
                join baremepersonneltemporaire on baremepersonneltemporaire.indice = v.indice
                where date <= :date and direction_id = :direction_id order by date desc 
            ) as v  group by personnel_id;
        ';
        // $sql = '
        //     SELECT * FROM v_bareme_par_personnel v
        //     WHERE v.direction_id = :direction_id
        //     AND date = (select date from baremepersonneltemporaire order by date desc limit 1)
        //     ';
        $stmt = $conn->prepare($sql);
        $resultSet = $stmt->executeQuery([
            'direction_id' => $direction_id,
            'date' => $date
        ]);

        // returns an array of arrays (i.e. a raw data set)
        $results = $resultSet->fetchAllAssociative();
        return $results;
   }

   /**
    * @return BaremePersonnelTemporaire[] Returns an array of BaremePersonnelTemporaire objects
    */
    public function findByDatebareme($date): array
    {
         $conn = $this->getEntityManager()->getConnection();
 
         $sql = '
            select * from (
                select 
                    baremepersonneltemporaire.*, 
                    v_personnel_details.id as personnel_id,
                    v_personnel_details.nom,
                    v_personnel_details.prenom,
                    v_personnel_details.direction_id,
                    v_personnel_details.direction,
                    v_personnel_details.contrat_id,
                    v_personnel_details.contrat
                from v_personnel_details
                join baremepersonneltemporaire on baremepersonneltemporaire.indice = v_personnel_details.indice and baremepersonneltemporaire.categorie = v_personnel_details.categorie
                and date <= :date order by date desc
            ) as v group by personnel_id      
            ';
        //  $sql = '
        //      SELECT * FROM v_bareme_par_personnel v
        //      WHERE date = (select date from baremepersonneltemporaire order by date desc limit 1)
        //      ';
         $stmt = $conn->prepare($sql);
         $resultSet = $stmt->executeQuery([
            'date' => $date
         ]);
 
         // returns an array of arrays (i.e. a raw data set)
         $results = $resultSet->fetchAllAssociative();
         return $results;
    }

//    /**
//     * @return BaremePersonnelTemporaire[] Returns an array of BaremePersonnelTemporaire objects
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

//    public function findOneBySomeField($value): ?BaremePersonnelTemporaire
//    {
//        return $this->createQueryBuilder('b')
//            ->andWhere('b.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
