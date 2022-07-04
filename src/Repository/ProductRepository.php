<?php

namespace App\Repository;

use App\Entity\Product;
use App\Service\SearchService;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;
use Knp\Component\Pager\Pagination\PaginationInterface;
use Knp\Component\Pager\PaginatorInterface;

/**
 * @extends ServiceEntityRepository<Product>
 *
 * @method Product|null find($id, $lockMode = null, $lockVersion = null)
 * @method Product|null findOneBy(array $criteria, array $orderBy = null)
 * @method Product[]    findAll()
 * @method Product[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ProductRepository extends ServiceEntityRepository
{

    private $paginator;

    public function __construct(ManagerRegistry $registry, PaginatorInterface $paginator)
    {
        parent::__construct($registry, Product::class);
        $this->paginator = $paginator;
    }

    public function add(Product $entity, bool $flush = false): void
    {
        $this->getEntityManager()->persist($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function remove(Product $entity, bool $flush = false): void
    {
        $this->getEntityManager()->remove($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    /**
     * @return PaginationInterface
     */
    public function findSearch(SearchService $search): PaginationInterface
    {
        $query = $this->getSearchQuery($search)->getQuery();

        return $this->paginator->paginate(
            $query,
            $search->page,
            12
        );
    }


    private function getSearchQuery(SearchService $search)
    {
        $query = $this
            ->createQueryBuilder('p')
            ->select('p', 'c')
            ->join('p.categories', 'c')
        ;

        if (!empty($search->q)) {
            $query = $query
                ->andWhere('p.title LIKE :q')
                ->setParameter('q', "%{$search->q}%")
            ;
        }

        //if (!empty($search->q)) {
        //    $query = $query
        //        ->andWhere('p.title LIKE :q')
        //        ->setParameter('q', "%{$search->q}%")
        //    ;
        //}

        if (!empty($search->category)) {
            $query = $query
                ->andWhere('c.id IN (:categories)')
                ->setParameter('categories', $search->categories)
            ;
        }

        return $query;
    }

//    /**
//     * @return Product[] Returns an array of Product objects
//     */
//    public function findByExampleField($value): array
//    {
//        return $this->createQueryBuilder('p')
//            ->andWhere('p.exampleField = :val')
//            ->setParameter('val', $value)
//            ->orderBy('p.id', 'ASC')
//            ->setMaxResults(10)
//            ->getQuery()
//            ->getResult()
//        ;
//    }

//    public function findOneBySomeField($value): ?Product
//    {
//        return $this->createQueryBuilder('p')
//            ->andWhere('p.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
