<?php

/**
 * Classe de gestion des tailles d'images
 */

class pl3_objet_theme_taille_image extends pl3_outil_objet_xml {
	const NOM_FICHE  = "theme";
	const NOM_BALISE = "taille_image";
	const TYPE       = self::TYPE_COMPOSITE;
	const ATTRIBUTS  = array(array("nom" => "nom", "type" => self::TYPE_CHAINE));
	const OBJETS     = array("image_largeur", "image_hauteur", "image_compression");
}