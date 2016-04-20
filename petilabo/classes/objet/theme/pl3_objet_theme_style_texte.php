<?php

/**
 * Classe de gestion des styles de texte
 */
 
class pl3_objet_theme_style_texte_css extends pl3_outil_objet_xml {
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

class pl3_objet_theme_style_texte_taille extends pl3_outil_element_theme_xml {
	const NOM_BALISE = "taille";
	const TYPE_BALISE = self::TYPE_CHAINE;
	const PROPRIETE_CSS = "font-size";
}

class pl3_objet_theme_style_texte extends pl3_outil_objet_composite_xml {
	/* Fiche */
	const NOM_FICHE = "theme";

	/* Balise */
	const NOM_BALISE = "style_texte";
	public static $Balise = array("nom" => self::NOM_BALISE, "type" => self::TYPE_COMPOSITE);
	
	/* Attributs */
	public static $Liste_attributs = array(
		array("nom" => self::NOM_ATTRIBUT_NOM, "type" => self::TYPE_CHAINE));

	/* Méthodes */
	public function __construct($id, &$parent, &$noeud = null) {
		$this->declarer_element(pl3_objet_theme_style_texte_taille::NOM_BALISE);
		$this->declarer_element(pl3_objet_theme_style_texte_css::NOM_BALISE);
		parent::__construct($id, $parent, $noeud);
	}

	public function charger_xml() {
		$this->charger_elements_xml();
	}

	public function ecrire_xml($niveau) {
		$attr_nom = $this->get_xml_attribut(self::NOM_ATTRIBUT_NOM);
		$xml = $this->ouvrir_xml($niveau, array($attr_nom));
		$xml .= $this->ecrire_elements_xml(1 + $niveau);
		$xml .= $this->fermer_xml($niveau);
		return $xml;
	}
	
	public function afficher($mode) {
		$nom = $this->get_attribut_nom();
		$ret = ".texte_".$nom."{";
		$ret .= $this->afficher_elements_xml($mode);
		$ret .= "}\n";
		return $ret;
	}
}