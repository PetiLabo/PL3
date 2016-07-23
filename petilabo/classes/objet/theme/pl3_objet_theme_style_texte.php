<?php

/**
 * Classe de gestion des styles de texte
 */

class pl3_objet_theme_style_texte extends pl3_outil_objet_xml_css_bloc {
	const NOM_BALISE = "style_texte";
	const ATTRIBUTS  = array(array("nom" => "nom", "type" => self::TYPE_CHAINE));
	const OBJETS     = array("css_marge", "css_retrait", "css_couleur", "css_taille", "css");
}