<?php

namespace App\Repository;

use App\Entity\LinkUser;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\OptimisticLockException;
use Doctrine\ORM\ORMException;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<LinkUser>
 *
 * @method LinkUser|null find($id, $lockMode = null, $lockVersion = null)
 * @method LinkUser|null findOneBy(array $criteria, array $orderBy = null)
 * @method LinkUser[]    findAll()
 * @method LinkUser[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class LinkUserRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, LinkUser::class);
    }

    /**
     * @throws ORMException
     * @throws OptimisticLockException
     */
    public function add(LinkUser $entity, bool $flush = true): void
    {
        $this->_em->persist($entity);
        if ($flush) {
            $this->_em->flush();
        }
    }

    /**
     * @throws ORMException
     * @throws OptimisticLockException
     */
    public function remove(LinkUser $entity, bool $flush = true): void
    {
        $this->_em->remove($entity);
        if ($flush) {
            $this->_em->flush();
        }
    }

    public function findAllForOneUSer($user)
    {
        return $this->createQueryBuilder('l')
            ->andWhere('l.user = :name')
            ->setParameter('name', $user)
            ->getQuery()
            ->getResult()
        ;
    }

    // /**
    //  * @return LinkUser[] Returns an array of LinkUser objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('l')
            ->andWhere('l.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('l.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?LinkUser
    {
        return $this->createQueryBuilder('l')
            ->andWhere('l.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
