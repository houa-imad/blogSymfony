<?php

namespace App\Repository;

use App\Entity\Tag;
use App\Entity\Post;
use App\Model\SearchData;
use Doctrine\Persistence\ManagerRegistry;
use Knp\Component\Pager\PaginatorInterface;
use Knp\Component\Pager\Pagination\SlidingPagination;
use Knp\Component\Pager\Pagination\PaginationInterface;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;

/**
 * @extends ServiceEntityRepository<Post>
 *
 * @method Post|null find($id, $lockMode = null, $lockVersion = null)
 * @method Post|null findOneBy(array $criteria, array $orderBy = null)
 * @method Post[]    findAll()
 * @method Post[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class PostRepository extends ServiceEntityRepository
{
    private PaginatorInterface $paginatorInterface;

    public function __construct(ManagerRegistry $registry, PaginatorInterface $paginatorInterface)
    {
        parent::__construct($registry, Post::class);
        $this->paginatorInterface = $paginatorInterface;
    
    }

    public function save(Post $entity, bool $flush = false): void
    {
        $this->getEntityManager()->persist($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function remove(Post $entity, bool $flush = false): void
    {
        $this->getEntityManager()->remove($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

 /**
     * Get published posts
     *
     * @param int $page
     * @param ?Category $category
     * @param ?Tag $tag
     * 
     * @return PaginationInterface \Knp\Bundle\PaginatorBundle\Pagination\SlidingPagination
     */
    public function findPublished(
        int $page,
        $category = null,
        ?Tag $tag = null
    ): PaginationInterface {
        $queryBuilder = $this->createQueryBuilder('p')
            ->where('p.state LIKE :state')
            ->setParameter('state', '%STATE_PUBLISHED%')
            ->addOrderBy('p.createdAt', 'DESC');
    
        if ($category !== null) {
            $queryBuilder
                ->join('p.category', 'c')
                ->andWhere('c = :category')
                ->setParameter('category', $category);
        }
    
        if ($tag !== null) {
            $queryBuilder
                ->join('p.tags', 't')
                ->andWhere('t = :tag')
                ->setParameter('tag', $tag);
        }
    ;
        $query = $queryBuilder
        ->getQuery()
        ->getResult();
        $posts = $this->paginatorInterface->paginate($query, $page, 9);

    
        return $posts;
    }
    


 /**
     * Get published posts thanks to Search Data value
     *
     * @param SearchData $searchData
     * @return PaginationInterface
     */
    public function findBySearch(SearchData $searchData): PaginationInterface
    {
        $data = $this->createQueryBuilder('p')
            ->where('p.state LIKE :state')
            ->setParameter('state', '%STATE_PUBLISHED%')
            ->addOrderBy('p.createdAt', 'DESC');

        if (!empty($searchData->q)) {
            $data = $data
                ->join('p.tags', 't')
                ->andWhere('p.title LIKE :q')
                ->orWhere('t.name LIKE :q')
                // Correspondance partielle
                ->setParameter('q', "%{$searchData->q}%");

                // Correspondance exacte
                // ->setParameter('q', "$searchData->q");
        }
                if (!empty($searchData->categories)) {
                    $data = $data
                        ->join('p.category', 'c')
                        ->andWhere('c.id IN (:categories)')
                        ->setParameter('categories', $searchData->categories);
                }

        $data = $data
            ->getQuery()
            ->getResult();

        $posts = $this->paginatorInterface->paginate($data, $searchData->page, 9);

        return $posts;
    }
}
   

