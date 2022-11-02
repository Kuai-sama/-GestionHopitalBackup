<?php

namespace App\Repository;

use App\Entity\Lit;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Lit>
 *
 * @method Lit|null find($id, $lockMode = null, $lockVersion = null)
 * @method Lit|null findOneBy(array $criteria, array $orderBy = null)
 * @method Lit[]    findAll()
 * @method Lit[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class LitRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Lit::class);
    }

    public function save(Lit $entity, bool $flush = false): void
    {
        $this->getEntityManager()->persist($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function remove(Lit $entity, bool $flush = false): void
    {
        $this->getEntityManager()->remove($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

//    /**
//     * @return Lit[] Returns an array of Lit objects
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

//    public function findOneBySomeField($value): ?Lit
//    {
//        return $this->createQueryBuilder('l')
//            ->andWhere('l.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }

    public function findSalle()
    {
        $qb = $this->createQueryBuilder('l')
        ->where('l.etre = salle.id')
        ->orderBy('l.LitOccupe')
        ;
        return $qb->getQuery()->getResult();
    }
}
