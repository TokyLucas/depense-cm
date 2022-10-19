<?php

namespace App\Repository;

use App\Entity\PersonnelDetails;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<PersonnelDetails>
 *
 * @method PersonnelDetails|null find($id, $lockMode = null, $lockVersion = null)
 * @method PersonnelDetails|null findOneBy(array $criteria, array $orderBy = null)
 * @method PersonnelDetails[]    findAll()
 * @method PersonnelDetails[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class PersonnelDetailsRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, PersonnelDetails::class);
    }

    public function add(PersonnelDetails $entity, bool $flush = false): void
    {
        $this->getEntityManager()->persist($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function remove(PersonnelDetails $entity, bool $flush = false): void
    {
        $this->getEntityManager()->remove($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    /**
    * @return PersonnelDetails[] Returns an array of BaremePersonnel objects
    */
   public function search($nom = '', $prenom = '', $dir = 'Tous', $contrat = 'Tous', $indice = null): array
   {   
        $query = $this->createQueryBuilder('p');
        $query->andWhere('p.nom like :nom');
        $query->setParameter('nom', "%".$nom."%");
        $query->andWhere('p.prenom like :prenom');
        $query->setParameter('prenom', "%".$prenom."%");

        if($dir != 'Tous'){
            $query->andWhere('p.direction_id = :direction_id');
            $query->setParameter('direction_id', $dir);
        }
        if($contrat != 'Tous'){
            $query->andWhere('p.contrat_id = :contrat_id');
            $query->setParameter('contrat_id', $contrat);
        }
        if($indice != null){
            $query->andWhere('p.indice like :indice');
            $query->setParameter('indice', "%".$indice."%");
        }
        
        return $query->getQuery()->getResult();
   }

   public function countById(){
        return $this->createQueryBuilder('a')
        ->select('count(a.id)')
        ->getQuery()
        ->getSingleScalarResult();
   }

//    /**
//     * @return PersonnelDetails[] Returns an array of PersonnelDetails objects
//     */
//    public function findByExampleField($value): array
//    {
//        return $this->createQueryBuilder('p')
//            ->andWhere('p.exampleField = :val')
//            ->setParameter('val', $value)
//            ->orderBy('p.id', 'ASC')
//            ->setMaxResults(10)
//            ->getQuery()
//            ->getResult()
//        ;
//    }

//    public function findOneBySomeField($value): ?PersonnelDetails
//    {
//        return $this->createQueryBuilder('p')
//            ->andWhere('p.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
