<?php


namespace App\Repository;

use App\Entity\EntityChanges;
use App\Entity\Figure;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * @method EntityChanges|null find($id, $lockMode = null, $lockVersion = null)
 * @method EntityChanges|null findOneBy(array $criteria, array $orderBy = null)
 * @method EntityChanges[]    findAll()
 * @method EntityChanges[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class FigureChangeListRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, EntityChanges::class);
    }

    public function findChangesByBatchId(string $batchId): array
    {
        $qb = $this->createQueryBuilder('c');
        $query = $qb
            ->leftJoin(Figure::class, 'f', 'WITH', 'f.id = c.entityId')
            ->where('f.batchId = :batchId')
            ->setParameter('batchId', $batchId)
            ->getQuery()
        ;

        return $query->getResult();
    }
}
