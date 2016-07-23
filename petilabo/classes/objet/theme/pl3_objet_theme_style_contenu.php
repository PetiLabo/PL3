<?php

/**
 * Classe de gestion des styles de contenu
 */

class pl3_objet_theme_style_contenu extends pl3_outil_objet_xml_css_bloc {
	const NOM_BALISE = "style_contenu";
	const ATTRIBUTS  = array(array("nom" => "nom", "type" => self::TYPE_CHAINE));
	const OBJETS     = array("css_marge", "css_retrait", "css_bordure", "css_fond", "css");
}