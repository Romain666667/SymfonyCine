<?php

namespace App\DataFixtures;

use App\Entity\Rating;
use App\Entity\Movies;
use App\Entity\MovieLover;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Persistence\ObjectManager;

class RatingFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        // Récupère les films et users existants en base
        $moviesRepo = $manager->getRepository(Movies::class);
        $movieLoverRepo = $manager->getRepository(MovieLover::class);

        $movies = $moviesRepo->findAll();
        $movieLovers = $movieLoverRepo->findAll();

        if (empty($movies) || empty($movieLovers)) {
            throw new \Exception('Aucun film ou MovieLover trouvé en base. Assurez-vous que les tables movies et movie_lover sont remplies.');
        }

        // Données de ratings : [movie_title, score, comment]
        $ratingsData = [
            ['Inception',                 5, 'Un chef-d\'œuvre de Christopher Nolan, mind-blowing !'],
            ['Inception',                 4, 'Très bon film, un peu complexe mais fascinant.'],
            ['Interstellar',              5, 'Magnifique, la bande son de Hans Zimmer est incroyable.'],
            ['Interstellar',              4, 'Très émouvant, quelques longueurs mais globalement excellent.'],
            ['The Dark Knight',           5, 'Heath Ledger en Joker est tout simplement parfait.'],
            ['The Dark Knight',           5, 'Le meilleur film de super-héros jamais réalisé.'],
            ['Pulp Fiction',              4, 'Tarantino au sommet de son art, dialogues cultes.'],
            ['Pulp Fiction',              3, 'Bien mais un peu trop décousu à mon goût.'],
            ['The Matrix',                5, 'Révolutionnaire pour l\'époque, toujours aussi bon.'],
            ['The Matrix',                4, 'Une claque visuelle, l\'histoire est passionnante.'],
            ['Forrest Gump',              5, 'Un film touchant et drôle à la fois, un classique.'],
            ['Forrest Gump',              4, 'Très beau film, Tom Hanks est exceptionnel.'],
            ['The Shawshank Redemption',  5, 'Le meilleur film de tous les temps selon moi.'],
            ['The Shawshank Redemption',  5, 'Impossible de ne pas être ému par ce film.'],
            ['Goodfellas',                4, 'Scorsese au meilleur de sa forme, très immersif.'],
            ['Goodfellas',                3, 'Bon film mais très long, certaines scènes sont dures.'],
            ['Fight Club',                5, 'Le twist final m\'a laissé sans voix.'],
            ['Fight Club',                4, 'Un film culte, message fort sur la société de consommation.'],
            ['Parasite',                  5, 'Bong Joon-ho est un génie, Oscar amplement mérité.'],
            ['Parasite',                  4, 'Brillant, le film change complètement de ton à mi-parcours.'],
        ];

        foreach ($ratingsData as $index => [$title, $score, $comment]) {
            // Trouve le film par titre
            $movie = $moviesRepo->findOneBy(['Title' => $title]);
            if (!$movie) {
                continue;
            }

            // Distribue les ratings entre les MovieLovers disponibles
            $movieLover = $movieLovers[$index % count($movieLovers)];

            // Vérifie qu'il n'y a pas déjà un rating pour ce couple film/user
            $existing = $manager->getRepository(Rating::class)->findOneBy([
                'movie'       => $movie,
                'movieLover'  => $movieLover,
            ]);
            if ($existing) {
                continue;
            }

            $rating = new Rating();
            $rating->setScore($score);
            $rating->setComment($comment);
            $rating->setMovie($movie);
            $rating->setMovieLover($movieLover);

            $manager->persist($rating);
        }

        $manager->flush();
    }
}
