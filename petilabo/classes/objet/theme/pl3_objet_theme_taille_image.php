<?php
require_once _CHEMIN_OBJET."theme/"._STYLE_COMMUN_CSS;

/**
 * Classe de gestion des tailles d'images
 */

class pl3_objet_theme_taille_image_largeur extends pl3_outil_element_theme_xml {
	const NOM_BALISE = "largeur";
	const TYPE_BALISE = self::TYPE_CHAINE;

	public function afficher($mode) {return null;}
}

class pl3_objet_theme_taille_image_hauteur extends pl3_outil_element_theme_xml {
	const NOM_BALISE = "hauteur";
	const TYPE_BALISE = self::TYPE_CHAINE;

	public function afficher($mode) {return null;}
}

class pl3_objet_theme_taille_image_compression extends pl3_outil_element_theme_xml {
	const NOM_BALISE = "compression";
	const TYPE_BALISE = self::TYPE_ENTIER;

	public function afficher($mode) {return null;}
}

class pl3_objet_theme_taille_image extends pl3_outil_objet_composite_xml {
	/* Fiche */
	const NOM_FICHE = "theme";

	/* Balise */
	const NOM_BALISE = "taille_image";
	public static $Balise = array("nom" => self::NOM_BALISE, "type" => self::TYPE_COMPOSITE);
	
	/* Attributs */
	public static $Liste_attributs = array(
		array("nom" => self::NOM_ATTRIBUT_NOM, "type" => self::TYPE_CHAINE));

	/* Méthodes */
	public function __construct($id, &$parent, &$noeud = null) {
		$this->declarer_element("pl3_objet_theme_taille_image_largeur");
		$this->declarer_element("pl3_objet_theme_taille_image_hauteur");
		$this->declarer_element("pl3_objet_theme_taille_image_compression");
		parent::__construct($id, $parent, $noeud);
	}

	public function charger_xml() {
		$this->charger_elements_xml();
	}
	
	public function parser_balise_fille($nom_balise) {
		$source_page = $this->get_source_page();
		$tab_ret = $source_page->parser_balise_fille(self::NOM_FICHE, $this, "pl3_objet_theme_taille_image", $nom_balise, $this->noeud);
		$nb_ret = (int) count($tab_ret);
		$ret = ($nb_ret > 0)?$tab_ret[$nb_ret - 1]:null;
		return $ret;
	}

	public function ecrire_xml($niveau) {
		$attr_nom = $this->get_xml_attribut(self::NOM_ATTRIBUT_NOM);
		$xml = $this->ouvrir_xml($niveau, array($attr_nom));
		$xml .= $this->ecrire_elements_xml(1 + $niveau);
		$xml .= $this->fermer_xml($niveau);
		return $xml;
	}
	
	public function afficher($mode) {return null;}
}