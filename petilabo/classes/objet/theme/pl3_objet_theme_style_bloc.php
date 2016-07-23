<?php

/**
 * Classe de gestion des styles de bloc
 */

class pl3_objet_theme_style_bloc extends pl3_outil_objet_xml_css_bloc {
	const NOM_BALISE = "style_bloc";
	const ATTRIBUTS  = array(array("nom" => "nom", "type" => self::TYPE_CHAINE));
	const OBJETS     = array("css_marge", "css_retrait", "css_bordure", "css_fond", "css");
}