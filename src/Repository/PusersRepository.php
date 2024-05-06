<?php

namespace App\Repository;

use App\Entity\Puser;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Pusers>
 */
class PusersRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Puser::class);
    }

    //    /**
    //     * @return Pusers[] Returns an array of Pusers objects
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

        public function findflowIds($value): array
        {
            
            $in = ("('" . join("','", $value) ."')");
            return $this->createQueryBuilder('p')
            ->andWhere('CONCAT(p.first_name, p.last_name) in ' . $in)
                ->getQuery()
                ->getResult()
            ;
        }


        public function deleteAll()
        {
            $this->createQueryBuilder('p')
                ->delete()
                ->getQuery()
            ->execute();
        }
}
