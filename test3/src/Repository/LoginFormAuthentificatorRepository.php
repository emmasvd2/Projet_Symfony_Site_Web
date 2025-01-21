<?php

namespace App\Repository;

use App\Entity\LoginFormAuthentificator;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\Security\Core\Exception\UnsupportedUserException;
use Symfony\Component\Security\Core\User\PasswordAuthenticatedUserInterface;
use Symfony\Component\Security\Core\User\PasswordUpgraderInterface;

/**
 * @extends ServiceEntityRepository<LoginFormAuthentificator>
* @implements PasswordUpgraderInterface<LoginFormAuthentificator>
 *
 * @method LoginFormAuthentificator|null find($id, $lockMode = null, $lockVersion = null)
 * @method LoginFormAuthentificator|null findOneBy(array $criteria, array $orderBy = null)
 * @method LoginFormAuthentificator[]    findAll()
 * @method LoginFormAuthentificator[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class LoginFormAuthentificatorRepository extends ServiceEntityRepository implements PasswordUpgraderInterface
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, LoginFormAuthentificator::class);
    }

    /**
     * Used to upgrade (rehash) the user's password automatically over time.
     */
    public function upgradePassword(PasswordAuthenticatedUserInterface $user, string $newHashedPassword): void
    {
        if (!$user instanceof LoginFormAuthentificator) {
            throw new UnsupportedUserException(sprintf('Instances of "%s" are not supported.', $user::class));
        }

        $user->setPassword($newHashedPassword);
        $this->getEntityManager()->persist($user);
        $this->getEntityManager()->flush();
    }

//    /**
//     * @return LoginFormAuthentificator[] Returns an array of LoginFormAuthentificator objects
//     */
//    public function findByExampleField($value): array
//    {
//        return $this->createQueryBuilder('l')
//            ->andWhere('l.exampleField = :val')
//            ->setParameter('val', $value)
//            ->orderBy('l.id', 'ASC')
//            ->setMaxResults(10)
//            ->getQuery()
//            ->getResult()
//        ;
//    }

//    public function findOneBySomeField($value): ?LoginFormAuthentificator
//    {
//        return $this->createQueryBuilder('l')
//            ->andWhere('l.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
