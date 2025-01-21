<?php

namespace App\Repository;

use App\Entity\Consoles;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Consoles>
 *
 * @method Consoles|null find($id, $lockMode = null, $lockVersion = null)
 * @method Consoles|null findOneBy(array $criteria, array $orderBy = null)
 * @method Consoles[]    findAll()
 * @method Consoles[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ConsolesRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Consoles::class);
    }

//    /**
//     * @return Consoles[] Returns an array of Consoles objects
//     */
//    public function findByExampleField($value): array
//    {
//        return $this->createQueryBuilder('c')
//            ->andWhere('c.exampleField = :val')
//            ->setParameter('val', $value)
//            ->orderBy('c.id', 'ASC')
//            ->setMaxResults(10)
//            ->getQuery()
//            ->getResult()
//        ;
//    }

//    public function findOneBySomeField($value): ?Consoles
//    {
//        return $this->createQueryBuilder('c')
//            ->andWhere('c.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
