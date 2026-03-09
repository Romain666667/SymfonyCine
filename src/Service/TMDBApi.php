<?php
namespace App\Service;

class TMDBApi
{
    private string $TMDBApiKey;
    private string $TMDBToken;

    /**
     * @todo Gérer le cas où la variable TMDB_API_KEY n'existe pas dans le .env
     */
    public function __construct()
    {
        // Utilisation de la clé API définie dans le fichier .env
        $apiKey = $_ENV['TMDB_API_KEY'];
        $this->TMDBApiKey = $apiKey;

        $token = $_ENV['TMDB_TOKEN'];
        $this->TMDBToken = $token;
    }

    /**
     * Recherche de films à partir d'un terme donné (string)
     *
     * @param string $term
     * @return array
     */
    public function search(string $term): array
    {
        $ch = curl_init();

        curl_setopt(
            $ch,
            CURLOPT_URL,
            'https://api.themoviedb.org/3/search/movie?api_key=' . $this->TMDBApiKey .
            "&query=" . urlencode($term) . "&language=fr"
        );
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

        $resultat_curl = curl_exec($ch);

        // On transforme le résultat de cURL en un objet JSON utilisable
        $json = json_decode($resultat_curl);

        // Renvoi du JSON
        return $json->results;
    }

    /**
     * Recherche de films à partir d'un identifiant donné (int)
     *
     * @param int $id
     * @return array
     */
    public function searchById(int $id): array
    {
        $endpoint = "https://api.themoviedb.org/3/movie/" . $id . "?language=fr-FR";

        $ch = curl_init();
        curl_setopt_array($ch, [
            CURLOPT_URL            => $endpoint,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING       => "",
            CURLOPT_MAXREDIRS      => 10,
            CURLOPT_TIMEOUT        => 30,
            CURLOPT_HTTP_VERSION   => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST  => "GET",
            CURLOPT_HTTPHEADER     => [
                "Authorization: Bearer " . $this->TMDBToken,
                "accept: application/json"
            ],
        ]);

        $resultat_curl = curl_exec($ch);

        // On transforme le résultat en JSON (tableau associatif)
        $json = json_decode($resultat_curl, true);

        return $json;
    }

    public function discover()
    {
        $endpoint = "https://api.themoviedb.org/3/discover/movie?include_adult=false&language=fr-FR";

        $ch = curl_init();
        curl_setopt_array($ch, [
            CURLOPT_URL            => $endpoint,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING       => "",
            CURLOPT_MAXREDIRS      => 10,
            CURLOPT_TIMEOUT        => 30,
            CURLOPT_HTTP_VERSION   => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST  => "GET",
            CURLOPT_HTTPHEADER     => [
                "Authorization: Bearer " . $this->TMDBToken,
                "accept: application/json"
            ],
        ]);

        $resultat_curl = curl_exec($ch);

        $json = json_decode($resultat_curl);

        /**
         * @TODO: Gestion de l'erreur
         */

        return $json->results;
    }
}
