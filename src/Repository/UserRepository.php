<?php

namespace App\Repository;

use App\Entity\User;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method User|null find($id, $lockMode = null, $lockVersion = null)
 * @method User|null findOneBy(array $criteria, array $orderBy = null)
 * @method User[]    findAll()
 * @method User[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class UserRepository extends ServiceEntityRepository 
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, User::class);
    }

    /**
     * Used to upgrade (rehash) the user's password automatically over time.
     */
    // public function upgradePassword(PasswordAuthenticatedUserInterface $user, string $newHashedPassword): void
    // {
    //     if (!$user instanceof User) {
    //         throw new UnsupportedUserException(sprintf('Instances of "%s" are not supported.', \get_class($user)));
    //     }

    //     $user->setPassword($newHashedPassword);
    //     $this->_em->persist($user);
    //     $this->_em->flush();
    // }

     /**
     * @returns all tasks per page
     * @retrun void
     */
    public function pagination($page, $limit)
    {
        return $this->createQueryBuilder('u')
            ->setFirstResult(($page * $limit) - $limit)
            ->setMaxResults($limit)
            ->getQuery()
            ->getResult();
    }

    /**
     * @returns all tasks per page
     * @retrun void
     */
    public function getTotalUser()
    {
        return $this->createQueryBuilder('u')
            ->select('COUNT(u)')
            ->getQuery()
            ->getSingleScalarResult();
    }
}
