<?php

namespace App\Repository;

use App\Entity\Student;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Student>
 *
 * @method Student|null find($id, $lockMode = null, $lockVersion = null)
 * @method Student|null findOneBy(array $criteria, array $orderBy = null)
 * @method Student[]    findAll()
 * @method Student[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class StudentRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Student::class);
    }

    public function save(Student $entity, bool $flush = false): void
    {
        $this->getEntityManager()->persist($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function remove(Student $entity, bool $flush = false): void
    {
        $this->getEntityManager()->remove($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function findByClassroom($classe): array
    {
        return $this->createQueryBuilder('student')
            ->join('student.classroom', 'classroom')
            ->andWhere('classroom.id = :value')
            ->setParameter('value', $classe)
            ->getQuery()
            ->getResult()
        ;
    }

    public function findByLevel($level): array
    {
        return $this->createQueryBuilder('student')
            ->join('student.level', 'level')
            ->andWhere('level.id = :value')
            ->setParameter('value', $level)
            ->getQuery()
            ->getResult()
        ;
    }

    public function findByClassLevel($classe, $level): array
    {
        return $this->createQueryBuilder('student')
            ->join('student.level', 'level')
            ->join('student.classroom', 'classroom')
            ->andWhere('classroom.id = :valueClass')
            ->andWhere('level.id = :valueLevel')
            ->setParameter('valueClass', $classe)
            ->setParameter('valueLevel', $level)
            ->getQuery()
            ->getResult()
        ;
    }

//    public function findOneBySomeField($value): ?Student
//    {
//        return $this->createQueryBuilder('s')
//            ->andWhere('s.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
