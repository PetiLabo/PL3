<?php

/**
 * Classe de gestion des images
 */
 
class pl3_objet_page_meta_titre extends pl3_outil_objet_xml {
	/* Balise */
	const NOM_BALISE = "titre";
	public static $Balise = array("nom" => self::NOM_BALISE, "type" => self::TYPE_REFERENCE, "reference" => "pl3_objet_texte_texte");
	
	/* Attributs */
	public static $Liste_attributs = array();
	
	/* Méthodes */
	public function ecrire_xml($niveau) {
		$xml = $this->ouvrir_fermer_xml($niveau);
		return $xml;
	}
	
	public function afficher($mode) {
		$ret = "";
		$valeur_titre = $this->get_valeur();
		if (strlen($valeur_titre) > 0) {
			$ret .= "<title>".$this->get_valeur()."</title>\n";
		}
		return $ret;
	}
}

class pl3_objet_page_meta_description extends pl3_outil_objet_xml {
	/* Balise */
	const NOM_BALISE = "description";
	public static $Balise = array("nom" => self::NOM_BALISE, "type" => self::TYPE_REFERENCE, "reference" => "pl3_objet_texte_texte");
	
	/* Attributs */
	public static $Liste_attributs = array();
	
	/* Méthodes */
	public function ecrire_xml($niveau) {
		$xml = $this->ouvrir_fermer_xml($niveau);
		return $xml;
	}
	
	public function afficher($mode) {
		$ret = "";
		$valeur_descr = $this->get_valeur();
		if (strlen($valeur_descr) > 0) {
			$ret .= "<meta name=\"description\" content=\"".$this->get_valeur()."\" />\n";
		}
		return $ret;
	}
}

class pl3_objet_page_meta extends pl3_outil_objet_composite_xml {
	/* Balise */
	const NOM_BALISE = "meta";
	public static $Balise = array("nom" => self::NOM_BALISE, "type" => self::TYPE_COMPOSITE);
	
	/* Attributs */
	public static $Liste_attributs = array();
	
	/* Constructeur */
	public function __construct(&$source_page, $nom_fiche, $id, &$parent, &$noeud = null) {
		$this->declarer_element(pl3_objet_page_meta_titre::NOM_BALISE);
		$this->declarer_element(pl3_objet_page_meta_description::NOM_BALISE);
		parent::__construct($source_page, $nom_fiche, $id, $parent, $noeud);
	}

	/* Méthodes */
	public function charger_xml() {
		$this->charger_elements_xml();
	}

	public function ecrire_xml($niveau) {
		$xml = $this->ouvrir_xml($niveau);
		$xml .= $this->ecrire_elements_xml(1 + $niveau);
		$xml .= $this->fermer_xml($niveau);
		return $xml;
	}
	
	public function afficher($mode) {
		$ret = $this->afficher_elements_xml($mode);
		return $ret;
	}
}