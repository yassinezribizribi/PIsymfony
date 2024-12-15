<?php
namespace App\Repository;

use App\Entity\Utilisateur;
use Symfony\Component\Security\Core\User\UserProviderInterface;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Security\Core\Exception\UserNotFoundException;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Utilisateur>
 */
class UtilisateurRepository extends ServiceEntityRepository implements UserProviderInterface
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Utilisateur::class);
    }

    /**
     * Charge un utilisateur par son identifiant (ici, l'email).
     */
    public function loadUserByIdentifier(string $email): UserInterface
    {
        $user = $this->findOneBy(['email' => $email]);

        if (!$user) {
            throw new UserNotFoundException("L'utilisateur avec l'email $email n'a pas été trouvé.");
        }

        return $user;
    }

    /**
     * Rafraîchit un utilisateur.
     */
    public function refreshUser(UserInterface $user): UserInterface
    {
        // Vous pouvez ajouter une logique pour vérifier si l'utilisateur est valide et le rafraîchir si nécessaire.
        // Dans ce cas, on retourne simplement l'utilisateur, mais vous pouvez ajouter des vérifications ici.
        return $user;
    }

    /**
     * Vérifie si la classe donnée est une instance de l'utilisateur.
     */
    public function supportsClass(string $class): bool
    {
        return Utilisateur::class === $class;
    }

     /**
     * Find users by role
     *
     * @param string $role
     * @return Utilisateur[]
     */
    public function findByRole(string $role): array
{
    return $this->createQueryBuilder('u')
        ->where('u.roles LIKE :role')
        ->setParameter('role', '%' . $role . '%')
        ->getQuery()
        ->getResult();
}
}
