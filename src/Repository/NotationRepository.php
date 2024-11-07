<?php

namespace App\Repository;

use App\Entity\Notation;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;
use Doctrine\ORM\EntityManagerInterface;

class NotationRepository extends ServiceEntityRepository
{
    private EntityManagerInterface $entityManager;

    public function __construct(ManagerRegistry $registry, EntityManagerInterface $entityManager)
    {
        parent::__construct($registry, Notation::class);
        $this->entityManager = $entityManager;
    }

    public function save(Notation $entity, bool $flush = false): void
    {
        dump('1');
        $this->entityManager->persist($entity);
        dump('2');

        if ($flush) {
            dump('3');
            $this->entityManager->flush();
            dump('4');
        }
    }

    public function remove(Notation $entity, bool $flush = false): void
    {
        $this->entityManager->remove($entity);

        if ($flush) {
            $this->entityManager->flush();
        }
    }

    public function removeByFormation($formation)
    {
        $qb = $this->createQueryBuilder('n')
            ->delete()
            ->where('n.formation = :formation')
            ->setParameter('formation', $formation);

        $qb->getQuery()->execute();
    }
}
