<?php

/**
 * Classe de gestion des styles de texte
 */
 
class pl3_objet_theme_style_css extends pl3_outil_objet_xml {
	/* Fiche */
	const NOM_FICHE = "theme";
	
	/* Balise */
	const NOM_BALISE = "css";
	public static $Balise = array("nom" => self::NOM_BALISE, "type" => self::TYPE_CHAINE);

	/* Attributs */
	public static $Liste_attributs = array();

	/* Méthodes */
	public function ecrire_xml($niveau) {
		$xml = $this->ouvrir_fermer_xml($niveau);
		return $xml;
	}
	
	public function afficher($mode) {
		$ret = $this->get_valeur();
		return $ret;
	}
}

class pl3_objet_theme_style_marge extends pl3_outil_element_theme_xml {
	const NOM_BALISE = "marge";
	const TYPE_BALISE = self::TYPE_CHAINE;
	const PROPRIETE_CSS = "margin";
}

class pl3_objet_theme_style_retrait extends pl3_outil_element_theme_xml {
	const NOM_BALISE = "retrait";
	const TYPE_BALISE = self::TYPE_CHAINE;
	const PROPRIETE_CSS = "padding";
}

class pl3_objet_theme_style_largeur extends pl3_outil_element_theme_xml {
	const NOM_BALISE = "largeur";
	const TYPE_BALISE = self::TYPE_CHAINE;
	const PROPRIETE_CSS = "width";
}

class pl3_objet_theme_style_bordure extends pl3_outil_element_theme_xml {
	const NOM_BALISE = "bordure";
	const TYPE_BALISE = self::TYPE_CHAINE;
	const PROPRIETE_CSS = "border";
}

class pl3_objet_theme_style_fond extends pl3_outil_element_theme_xml {
	const NOM_BALISE = "fond";
	const TYPE_BALISE = self::TYPE_CHAINE;
	const PROPRIETE_CSS = "background";
}

class pl3_objet_theme_style_couleur extends pl3_outil_element_theme_xml {
	const NOM_BALISE = "couleur";
	const TYPE_BALISE = self::TYPE_CHAINE;
	const PROPRIETE_CSS = "color";
}

class pl3_objet_theme_style_taille extends pl3_outil_element_theme_xml {
	const NOM_BALISE = "taille";
	const TYPE_BALISE = self::TYPE_CHAINE;
	const PROPRIETE_CSS = "font-size";
}
 