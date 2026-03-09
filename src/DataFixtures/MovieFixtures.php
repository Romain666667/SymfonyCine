<?php

namespace App\DataFixtures;

use App\Entity\Movies;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class MovieFixtures extends Fixture
{
    /**
     * Données de films de test basées sur de vrais films
     */
    private array $moviesData = [
        [
            'title'       => 'Inception',
            'date'        => '2010-07-16',
            'time'        => '02:28:00',
            'poster_path' => '/oYuLEt3zVCKq57qu2F8dT7NIa6f.jpg',
            'imdb_id'     => 27205,
            'resume'      => 'Un voleur qui s\'introduit dans les rêves des autres pour leur dérober des secrets se voit offrir une chance de retrouver sa vie passée en échange d\'une mission impossible.',
        ],
        [
            'title'       => 'Interstellar',
            'date'        => '2014-11-05',
            'time'        => '02:49:00',
            'poster_path' => '/rAiYTfKGqDCRIIqo664sY9XZIvQ.jpg',
            'imdb_id'     => 157336,
            'resume'      => 'Un groupe d\'explorateurs utilise un tunnel de ver récemment découvert pour dépasser les limites des voyages spatiaux humains.',
        ],
        [
            'title'       => 'The Dark Knight',
            'date'        => '2008-07-18',
            'time'        => '02:32:00',
            'poster_path' => '/qJ2tW6WMUDux911r6m7haRef0WH.jpg',
            'imdb_id'     => 468569,
            'resume'      => 'Batman, Commissioner Gordon et Harvey Dent s\'unissent pour démanteler les organisations criminelles à Gotham, mais le Joker sème le chaos.',
        ],
        [
            'title'       => 'Pulp Fiction',
            'date'        => '1994-10-14',
            'time'        => '02:34:00',
            'poster_path' => '/d5iIlFn5s0ImszYzBPb8JPIfbXD.jpg',
            'imdb_id'     => 680,
            'resume'      => 'Les histoires entrecroisées de criminels, gangsters, et âmes perdues dans Los Angeles, racontées de manière non linéaire.',
        ],
        [
            'title'       => 'The Matrix',
            'date'        => '1999-03-31',
            'time'        => '02:16:00',
            'poster_path' => '/f89U3ADr1oiB1s9GkdPOEpXUk5H.jpg',
            'imdb_id'     => 603,
            'resume'      => 'Un programmeur découvre que la réalité telle qu\'il la connaît est une simulation créée par des machines pour soumettre l\'humanité.',
        ],
        [
            'title'       => 'Forrest Gump',
            'date'        => '1994-07-06',
            'time'        => '02:22:00',
            'poster_path' => '/saHP97rTPS5eLmrLQEcANmKrsFl.jpg',
            'imdb_id'     => 13,
            'resume'      => 'L\'histoire extraordinaire d\'un homme ordinaire du Sud des États-Unis qui assiste sans le vouloir à plusieurs événements marquants du XXe siècle.',
        ],
        [
            'title'       => 'The Shawshank Redemption',
            'date'        => '1994-09-23',
            'time'        => '02:22:00',
            'poster_path' => '/q6y0Go1tsGEsmtFryDOJo3dEmqu.jpg',
            'imdb_id'     => 278,
            'resume'      => 'Un banquier condamné à tort pour le meurtre de sa femme s\'adapte à la vie difficile en prison et noue une amitié profonde avec un autre détenu.',
        ],
        [
            'title'       => 'Goodfellas',
            'date'        => '1990-09-19',
            'time'        => '02:26:00',
            'poster_path' => '/aKuFiU82s5ISJpGZp7YkIr3kCUd.jpg',
            'imdb_id'     => 769,
            'resume'      => 'L\'histoire vraie de Henry Hill, un gangster qui a gravi les échelons de la mafia new-yorkaise pendant 25 ans.',
        ],
        [
            'title'       => 'Fight Club',
            'date'        => '1999-10-15',
            'time'        => '02:19:00',
            'poster_path' => '/pB8BM7pdSp6B6Ih7QZ4DrQ3PmJK.jpg',
            'imdb_id'     => 550,
            'resume'      => 'Un employé de bureau insomniaque forme un club de combat clandestin avec un fabricant de savon charismatique.',
        ],
        [
            'title'       => 'Parasite',
            'date'        => '2019-05-30',
            'time'        => '02:12:00',
            'poster_path' => '/7IiTTgloJzvGI1TAYymCfbfl3vT.jpg',
            'imdb_id'     => 496243,
            'resume'      => 'La famille Kim, tous au chômage, s\'infiltre progressivement dans la vie d\'une famille riche, les Park, avec des conséquences inattendues.',
        ],
    ];

    /**
     * Méthode pour charger les données de test dans la base de données
     *
     * @param ObjectManager $manager
     * @return void
     */
    public function load(ObjectManager $manager): void
    {
        echo "---------------------------------------------------\n";
        echo "Début du chargement des fixtures pour Movies...\n";
        echo "---------------------------------------------------\n\n";

        foreach ($this->moviesData as $data) {
            $movie = new Movies();
            $movie->setTitle($data['title']);
            $movie->setDate(new \DateTime($data['date']));
            $movie->setTime(new \DateTime($data['time']));
            $movie->setPosterPath($data['poster_path']);
            $movie->setImdbId($data['imdb_id']);
            $movie->setResume($data['resume']);

            $manager->persist($movie);

            echo "Film créé : " . $data['title'] . "\n";
        }

        echo "---------------------------------------------------\n\n";

        $manager->flush();

        echo "Toutes les fixtures films ont été chargées avec succès !\n";
    }
}
