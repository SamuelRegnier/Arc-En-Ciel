<?php

namespace App\Repository;

use App\Entity\User;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\Security\Core\Exception\UnsupportedUserException;
use Symfony\Component\Security\Core\User\PasswordAuthenticatedUserInterface;
use Symfony\Component\Security\Core\User\PasswordUpgraderInterface;

/**
 * @extends ServiceEntityRepository<User>
 *
 * @method User|null find($id, $lockMode = null, $lockVersion = null)
 * @method User|null findOneBy(array $criteria, array $orderBy = null)
 * @method User[]    findAll()
 * @method User[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class UserRepository extends ServiceEntityRepository implements PasswordUpgraderInterface
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, User::class);
    }

    public function save(User $entity, bool $flush = false): void
    {
        $this->getEntityManager()->persist($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function remove(User $entity, bool $flush = false): void
    {
        $this->getEntityManager()->remove($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    /**
     * Used to upgrade (rehash) the user's password automatically over time.
     */
    public function upgradePassword(PasswordAuthenticatedUserInterface $user, string $newHashedPassword): void
    {
        if (!$user instanceof User) {
            throw new UnsupportedUserException(sprintf('Instances of "%s" are not supported.', \get_class($user)));
        }

        $user->setPassword($newHashedPassword);

        $this->save($user, true);
    }

    public function findAllOrderBy($admin): array
    {
        if ($admin[0] != "ROLE_ADMIN"){
            return $this->createQueryBuilder('user')
                ->andWhere('user.isActive = true')
                ->addOrderBy('user.roles', 'ASC')
                ->getQuery()
                ->getResult()
            ;
        }
        return $this->createQueryBuilder('user')
            ->addOrderBy('user.roles', 'ASC')
            ->getQuery()
            ->getResult()
        ;
    }

    public function findByRole($role, $admin): array
    {
        if ($admin[0] != "ROLE_ADMIN"){
            return $this->createQueryBuilder('user')
                ->andWhere('user.isActive = true')
                ->andWhere('user.roles LIKE :value')
                ->setParameter('value', '%'.$role.'%')
                ->getQuery()
                ->getResult()
            ;
        }
        return $this->createQueryBuilder('user')
            ->andWhere('user.roles LIKE :value')
            ->setParameter('value', '%'.$role.'%')
            ->getQuery()
            ->getResult()
        ;
    }

    public function findByName($name, $admin): array
    {
        if ($admin[0] != "ROLE_ADMIN"){
            return $this->createQueryBuilder('user')
                ->andWhere('user.isActive = true')
                ->andWhere('user.lastName LIKE :value')
            ->setParameter('value', '%'.$name.'%')
            ->getQuery()
            ->getResult()
            ;
        }
        return $this->createQueryBuilder('user')
            ->andWhere('user.lastName LIKE :value')
            ->setParameter('value', '%'.$name.'%')
            ->getQuery()
            ->getResult()
        ;
    }


//    /**
//     * @return User[] Returns an array of User objects
//     */
//    public function findByExampleField($value): array
//    {
//        return $this->createQueryBuilder('u')
//            ->andWhere('u.exampleField = :val')
//            ->setParameter('val', $value)
//            ->orderBy('u.id', 'ASC')
//            ->setMaxResults(10)
//            ->getQuery()
//            ->getResult()
//        ;
//    }

//    public function findOneBySomeField($value): ?User
//    {
//        return $this->createQueryBuilder('u')
//            ->andWhere('u.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
