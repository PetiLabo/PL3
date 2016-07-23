<?php

/**
 * Classe de gestion des styles de page
 */

class pl3_objet_theme_style_page extends pl3_outil_objet_xml_css_bloc {
	const NOM_BALISE = "style_page";
	const ATTRIBUTS  = array(array("nom" => "nom", "type" => self::TYPE_CHAINE));
	const OBJETS     = array("css_responsive", "css_largeur", "css_marge", "css_retrait", "css_fond", "css");
}