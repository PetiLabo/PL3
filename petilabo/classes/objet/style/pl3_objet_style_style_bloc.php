<?php

/**
 * Classe de gestion des styles de bloc
 */
 
class pl3_objet_style_style_bloc_fond extends pl3_outil_objet_xml {
	/* Fiche */
	const NOM_FICHE = "style";

	/* Balise */
	const NOM_BALISE = "fond";
	public static $Balise = array("nom" => self::NOM_BALISE, "type" => self::TYPE_CHAINE);
	
	/* Attributs */
	public static $Liste_attributs = array();
	
	/* Méthodes */
	public function ecrire_xml($niveau) {
		$xml = $this->ouvrir_fermer_xml($niveau);
		return $xml;
	}
	
	public function afficher($mode) {
		$ret = "background:".$this->get_valeur().";";
		return $ret;
	}
}

class pl3_objet_style_style_bloc extends pl3_outil_objet_composite_xml {
	/* Fiche */
	const NOM_FICHE = "style";

	/* Balise */
	const NOM_BALISE = "style_bloc";
	public static $Balise = array("nom" => self::NOM_BALISE, "type" => self::TYPE_COMPOSITE);
	
	/* Attributs */
	public static $Liste_attributs = array(
		array("nom" => self::NOM_ATTRIBUT_NOM, "type" => self::TYPE_CHAINE));

	/* Méthodes */
	public function __construct(&$source_page, $id, &$parent, &$noeud = null) {
		$this->declarer_element(pl3_objet_style_style_bloc_fond::NOM_BALISE);
		parent::__construct($source_page, $id, $parent, $noeud);
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
		$theme = $this->source_page->get_theme();
		$nom = $this->get_attribut_nom();
		$ret = ".".$theme."_bloc_".$nom."{";
		$ret .= $this->afficher_elements_xml($mode);
		$ret .= "}\n";
		return $ret;
	}
}