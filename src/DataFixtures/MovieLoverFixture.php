<?php

namespace App\DataFixtures;

use App\Entity\MovieLover;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class MovieLoverFixture extends Fixture
{
    // Propriété de la fixture pour le service de hachage de mot de passe
    private UserPasswordHasherInterface $passwordHasher;

    /**
     * Constructeur de la fixture pour injecter le service de hachage de mot de passe
     * Paramètre : UserPasswordHasherInterface pour hacher les mots de passe des utilisateurs créés
     * 
     * @param UserPasswordHasherInterface $passwordHasher
     * @return void
     */
    public function __construct(UserPasswordHasherInterface $passwordHasher)
    {
        // Injecter le service de hachage de mot de passe dans la fixture
        $this->passwordHasher = $passwordHasher;
    }

    /**
     * Méthode pour charger les données de test dans la base de données
     * Paramètre : ObjectManager pour gérer les entités et les persister dans la base de données
     * 
     * @param ObjectManager $manager
     * @return void
     */
    public function load(ObjectManager $manager): void
    {
        echo "---------------------------------------------------\n";
        echo "Début du chargement des fixtures pour MovieLover...\n";
        echo "---------------------------------------------------\n\n\n";



        // Créer une nouvelle entité MovieLover (admin)
        echo "---------------------------------------------------\n";
        echo "Création de l'utilisateur admin...\n";
        echo "---------------------------------------------------\n";
        $admin = new MovieLover();
        $admin->setEmail('admin@example.com');
        $admin->setFirstname('Admin');
        $admin->setLastname('User');
        $admin->setRoles(['ROLE_ADMIN']);
        // Hacher le mot de passe de l'admin avant de le persister
        $admin->setPassword($this->passwordHasher->hashPassword($admin, 'admin123'));
        $admin->setCreatedAt(new \DateTimeImmutable());
        $admin ->setUpdateAt(new \DateTimeImmutable());
        // Persister l'admin dans la base de données
        $manager->persist($admin);
        echo "Admin créé : admin@example.com (mot de passe: admin123)\n";
        echo "---------------------------------------------------\n\n\n";


        // Créer n nouvelle entités MovieLover avec le rôle ROLE_USER
        $numberOfUsers = 10; // Changez cette valeur pour créer plus ou moins d'utilisateurs
        echo "---------------------------------------------------\n";
        echo "Création de $numberOfUsers utilisateurs avec le rôle ROLE_USER...\n";
        echo "---------------------------------------------------\n";
        for ($i = 0; $i < $numberOfUsers; $i++)
        {
            $user = new MovieLover();
            $user->setEmail(sprintf('user%d@example.com', $i));
            $user->setFirstname(sprintf('User%d', $i));
            $user->setLastname(sprintf('Lastname%d', $i));
            $user->setRoles(['ROLE_USER']);
            // Hacher le mot de passe de l'utilisateur avant de le persister
            $user->setPassword($this->passwordHasher->hashPassword($user, 'user123'));
            $user->setCreatedAt(new \DateTimeImmutable());
            $user ->setUpdateAt(new \DateTimeImmutable());
            $manager->persist($user);
            echo "Utilisateur créé : " . $user->getEmail() . " (mot de passe: user123)\n";
        }
        echo "---------------------------------------------------\n\n\n";

        // Flusher les données dans la base de données
        $manager->flush();
        echo "Toutes les fixtures ont été chargées avec succès !\n";
    }
}