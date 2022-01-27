<?php

namespace App\Repository;

use App\Entity\Excursion;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\Query;
use Doctrine\Persistence\ManagerRegistry;
use Knp\Component\Pager\Pagination\PaginationInterface;
use Knp\Component\Pager\PaginatorInterface;

/**
 * @method Excursion|null find($id, $lockMode = null, $lockVersion = null)
 * @method Excursion|null findOneBy(array $criteria, array $orderBy = null)
 * @method Excursion[]    findAll()
 * @method Excursion[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ExcursionRepository extends ServiceEntityRepository
{
    /**
     * @var PaginatorInterface
     */
    private $paginator;

    public function __construct(
        ManagerRegistry $registry,
        PaginatorInterface $paginator
    ) {
        parent::__construct($registry, Excursion::class);

        $this->paginator = $paginator;
    }

    public function findAllWithPagination(int $page, int $limit): PaginationInterface
    {
        return
            $this->paginator->paginate(
                $this->createPaginationQuery(),
                $page,
                $limit,
                [
                    'defaultSortFieldName' => 'p.createdAt',
                    'defaultSortDirection' => 'asc'
                ]
            );
    }

    private function createPaginationQuery(): Query
    {
        return
            $this->createQueryBuilder('p')
                ->addOrderBy('p.id', 'ASC')
                ->getQuery()
            ;
    }

    // /**
    //  * @return Excursion[] Returns an array of Excursion objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('e')
            ->andWhere('e.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('e.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?Excursion
    {
        return $this->createQueryBuilder('e')
            ->andWhere('e.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
    public function findAllWithTrack()
    {
        $this
            ->createQueryBuilder('e')
//            ->leftJoin('e.sorts', 'es', Query\Expr\Join::WITH, 'es.locale = :locale')
            ->leftJoin('e.sorts', 'es')
//            ->where('translations.title = :title')
//            ->setParameter('locale', $locale)
//            ->setParameter('title', $title)
            ->getQuery()
            ->getResult()
            ;
    }

    public function findWithTracks(int $excursionId)
    {
        return
            $this
                ->createQueryBuilder('e')
    //            ->leftJoin('e.sorts', 'es', Query\Expr\Join::WITH, 'es.locale = :locale')
                ->leftJoin(
                    'e.sorts',
                    'es'//,
    //                Query\Expr\Join::WITH,
    //                'es.excursion = :excursionId'
                )
                ->leftJoin('es.chapter', 'ch')
                ->leftJoin('ch.playListSorts', 'pls')
                ->leftJoin('pls.playList', 'pl')
                ->leftJoin('pl.trackSorts', 'trs')
                ->leftJoin('trs.track', 'tr')
                ->where('e.id = :excursionId')
                ->setParameter('excursionId', $excursionId)
                ->getQuery()
                ->getResult()
            ;
    }
}
