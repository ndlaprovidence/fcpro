<?php

namespace App\Repository;

use App\Entity\DatePDF;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<DatePDF>
 *
 * @method DatePDF|null find($id, $lockMode = null, $lockVersion = null)
 * @method DatePDF|null findOneBy(array $criteria, array $orderBy = null)
 * @method DatePDF[]    findAll()
 * @method DatePDF[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class DatePDFRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, DatePDF::class);
    }

//    /**
//     * @return DatePDF[] Returns an array of DatePDF objects
//     */
//    public function findByExampleField($value): array
//    {
//        return $this->createQueryBuilder('d')
//            ->andWhere('d.exampleField = :val')
//            ->setParameter('val', $value)
//            ->orderBy('d.id', 'ASC')
//            ->setMaxResults(10)
//            ->getQuery()
//            ->getResult()
//        ;
//    }

//    public function findOneBySomeField($value): ?DatePDF
//    {
//        return $this->createQueryBuilder('d')
//            ->andWhere('d.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
