<?php

namespace App\Repository;

use App\Entity\GrumpyPizza;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<GrumpyPizza>
 */
class GrumpyPizzaRepository extends ServiceEntityRepository
{
    const MAX_RESULT_PER_PAGE = 10;

    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, GrumpyPizza::class);
    }

    public function findByNameStartingWith(
        string $prefix = null,
        int $offset = 0,
        int $limit = self::MAX_RESULT_PER_PAGE,
    ) {
        $queryBuilder = $this->createQueryBuilder('grumpy_pizza')
            ->setMaxResults($limit)
            ->setFirstResult($offset)
        ;

        if ($prefix) {
            $queryBuilder
                ->andWhere('grumpy_pizza.name LIKE :pattern')
                ->setParameter('pattern', $prefix.'%');
        }

        return $queryBuilder
            ->getQuery()
            ->getResult()
        ;
    }

    //    /**
    //     * @return GrumpyPizza[] Returns an array of GrumpyPizza objects
    //     */
    //    public function findByExampleField($value): array
    //    {
    //        return $this->createQueryBuilder('g')
    //            ->andWhere('g.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->orderBy('g.id', 'ASC')
    //            ->setMaxResults(10)
    //            ->getQuery()
    //            ->getResult()
    //        ;
    //    }

    //    public function findOneBySomeField($value): ?GrumpyPizza
    //    {
    //        return $this->createQueryBuilder('g')
    //            ->andWhere('g.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->getQuery()
    //            ->getOneOrNullResult()
    //        ;
    //    }
}
