<?php
namespace App\Service;
class Slugifier{
/**
 * Méthode qui prend une string en paramètre et qui renvoie une slug
 *
 * @param $string string
 * @return @string
 */
 public function slugify( string $string ): string
 {
 // Génération du slug
 // hello how are you ===> hello-how-are-you
 return strtolower(trim(preg_replace('/[^A-Za-z0-9-]+/', '-', $string), '-'));
 }
}