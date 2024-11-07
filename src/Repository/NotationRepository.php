<?php

namespace App\Repository;

use App\Entity\Notation;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Notation>
 *
 * @method Notation|null find($id, $lockMode = null, $lockVersion = null)
 * @method Notation|null findOneBy(array $criteria, array $orderBy = null)
 * @method Notation[]    findAll()
 * @method Notation[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class NotationRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Notation::class);
    }

    public function removeByFormation($formation)
    {
        $qb = $this->createQueryBuilder('n')
            ->delete()
            ->where('n.formation = :formation')
            ->setParameter('formation', $formation);

        $qb->getQuery()->execute();
    }

    public function save(Notation $entity, bool $flush = false): void
    {
        $this->getEntityManager()->persist($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function remove(Notation $entity, bool $flush = false): void
    {
        // dump("Suppression de la note avec l'ID : {$entity->getId()}");
        $this->getEntityManager()->remove($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

//    /**
//     * @return Notation[] Returns an array of Notation objects
//     */
//    public function findByExampleField($value): array
//    {
//        return $this->createQueryBuilder('n')
//            ->andWhere('n.exampleField = :val')
//            ->setParameter('val', $value)
//            ->orderBy('n.id', 'ASC')
//            ->setMaxResults(10)
//            ->getQuery()
//            ->getResult()
//        ;
//    }

//    public function findOneBySomeField($value): ?Notation
//    {
//        return $this->createQueryBuilder('n')
//            ->andWhere('n.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
