<?php

namespace App\Repository;

use App\Entity\Task;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Task|null find($id, $lockMode = null, $lockVersion = null)
 * @method Task|null findOneBy(array $criteria, array $orderBy = null)
 * @method Task[]    findAll()
 * @method Task[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class TaskRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Task::class);
    }

     /**
     * @returns all tasks per page
     * @retrun void
     */
    public function pagination($page, $limit)
    {
        return $this->createQueryBuilder('t')
            ->setFirstResult(($page * $limit) - $limit)
            ->setMaxResults($limit)
            ->getQuery()
            ->getResult();
    }

    /**
     * @returns all tasks per page
     * @retrun void
     */
    public function getTotalTask()
    {
        return $this->createQueryBuilder('t')
            ->select('COUNT(t)')
            ->getQuery()
            ->getSingleScalarResult();
    }

    // /**
    //  * @return Task[] Returns an array of Task objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('t')
            ->andWhere('t.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('t.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    // public function findTask($idTask)
    // {
    //     return $this->createQueryBuilder('t')
    //     ->andWhere('t.id = :val')
    //     ->setParameter('val', $idTask)
    //         ->getQuery()
    //         ->getResult()
    //     ;
    // }

    /*
    public function findOneBySomeField($value): ?Task
    {
        return $this->createQueryBuilder('t')
            ->andWhere('t.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
