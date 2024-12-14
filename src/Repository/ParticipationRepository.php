<?php

namespace App\Repository;

use App\Entity\Participation;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Participation>
 */
class ParticipationRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Participation::class);
    }
    public function findParticipationDetails(): array
    {
        $conn = $this->getEntityManager()->getConnection();
    
        $sql = "
            SELECT 
    MIN(p.id) AS participationId, -- Exemple : choisir un ID arbitraire (le plus petit dans chaque groupe)
    e.titre_even AS titreEvenement, 
    p.date_participation AS dateParticipation,
    COUNT(p.id) AS nbParticipants
    FROM participation p
    INNER JOIN evenement e ON p.evenement_id = e.id
    GROUP BY e.titre_even, p.date_participation, e.id
        ";
    
        $stmt = $conn->prepare($sql);
        $resultSet = $stmt->executeQuery();
    
        return $resultSet->fetchAllAssociative();
    }
    public function findAllWithEventDetails()
{
    return $this->createQueryBuilder('p')
        ->join('p.evenement', 'e') // Relier à l'entité Evenement
        ->addSelect('e')          // Inclure les détails de l'événement
        ->getQuery()
        ->getResult();
}
public function getParticipationCounts(): array
{
    $conn = $this->getEntityManager()->getConnection();

    $sql = "
        SELECT 
            e.id AS evenementId, 
            COUNT(p.id) AS nbParticipants
        FROM participation p
        INNER JOIN evenement e ON p.evenement_id = e.id
        GROUP BY e.id
    ";

    $stmt = $conn->prepare($sql);
    $resultSet = $stmt->executeQuery();

    // Retourne un tableau associatif avec l'ID de l'événement et le nombre de participants
    return $resultSet->fetchAllAssociative();
}


}

//    /**
//     * @return Participation[] Returns an array of Participation objects
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

//    public function findOneBySomeField($value): ?Participation
//    {
//        return $this->createQueryBuilder('p')
//            ->andWhere('p.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }

