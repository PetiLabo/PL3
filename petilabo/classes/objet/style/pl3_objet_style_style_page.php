<?php

/**
 * Classe de gestion des styles de page
 */
 
class pl3_objet_style_style_page_largeur extends pl3_outil_objet_xml {
	/* Fiche */
	const NOM_FICHE = "style";

	/* Balise */
	const NOM_BALISE = "largeur";
	public static $Balise = array("nom" => self::NOM_BALISE, "type" => self::TYPE_CHAINE);
	
	/* Attributs */
	public static $Liste_attributs = array();
	
	/* Méthodes */
	public function ecrire_xml($niveau) {
		$xml = $this->ouvrir_fermer_xml($niveau);
		return $xml;
	}
	
	public function afficher($mode) {
		$ret = "width:".$this->get_valeur().";";
		return $ret;
	}
}

class pl3_objet_style_style_page_responsive extends pl3_outil_objet_xml {
	/* Fiche */
	const NOM_FICHE = "style";

	/* Balise */
	const NOM_BALISE = "responsive";
	public static $Balise = array("nom" => self::NOM_BALISE, "type" => self::TYPE_CHAINE);
	
	/* Attributs */
	public static $Liste_attributs = array();
	
	/* Méthodes */
	public function ecrire_xml($niveau) {
		$xml = $this->ouvrir_fermer_xml($niveau);
		return $xml;
	}
	
	public function afficher($mode) {
		return null;
	}
}

class pl3_objet_style_style_page_fond extends pl3_outil_objet_xml {
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

class pl3_objet_style_style_page extends pl3_outil_objet_composite_xml {
	/* Fiche */
	const NOM_FICHE = "style";

	/* Balise */
	const NOM_BALISE = "style_page";
	public static $Balise = array("nom" => self::NOM_BALISE, "type" => self::TYPE_COMPOSITE);
	
	/* Attributs */
	public static $Liste_attributs = array(
		array("nom" => self::NOM_ATTRIBUT_NOM, "type" => self::TYPE_CHAINE));

	/* Méthodes */
	public function __construct($id, &$parent, &$noeud = null) {
		$this->declarer_element(pl3_objet_style_style_page_largeur::NOM_BALISE);
		$this->declarer_element(pl3_objet_style_style_page_responsive::NOM_BALISE);
		$this->declarer_element(pl3_objet_style_style_page_fond::NOM_BALISE);
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
		$source_page = pl3_outil_racine_page::Get();
		$theme = $source_page->get_nom_theme();
		$nom = $this->get_attribut_nom();
		$ret = ".".$theme."_page_".$nom."{";
		$ret .= $this->afficher_elements_xml($mode);
		$ret .= "}\n";
		return $ret;
	}
}