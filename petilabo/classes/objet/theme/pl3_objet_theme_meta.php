<?php

/**
 * Classe de gestion des balises meta
 */
 
class pl3_objet_theme_meta_auteur extends pl3_outil_objet_xml {
	/* Fiche */
	const NOM_FICHE = "theme";

	/* Balise */
	const NOM_BALISE = "auteur";
	public static $Balise = array("nom" => self::NOM_BALISE, "type" => self::TYPE_CHAINE);
	
	/* Attributs */
	public static $Liste_attributs = array();
	
	/* Méthodes */
	public function ecrire_xml($niveau) {
		$xml = $this->ouvrir_fermer_xml($niveau);
		return $xml;
	}
	
	public function afficher($mode) {return null;}
}

class pl3_objet_theme_meta_forge extends pl3_outil_objet_xml {
	/* Fiche */
	const NOM_FICHE = "theme";

	/* Balise */
	const NOM_BALISE = "forge";
	public static $Balise = array("nom" => self::NOM_BALISE, "type" => self::TYPE_CHAINE);
	
	/* Attributs */
	public static $Liste_attributs = array();
	
	/* Méthodes */
	public function ecrire_xml($niveau) {
		$xml = $this->ouvrir_fermer_xml($niveau);
		return $xml;
	}
	
	public function afficher($mode) {return null;}
}

class pl3_objet_theme_meta_documentation extends pl3_outil_objet_xml {
	/* Fiche */
	const NOM_FICHE = "theme";

	/* Balise */
	const NOM_BALISE = "documentation";
	public static $Balise = array("nom" => self::NOM_BALISE, "type" => self::TYPE_LIEN);
	
	/* Attributs */
	public static $Liste_attributs = array();
	
	/* Méthodes */
	public function ecrire_xml($niveau) {
		$xml = $this->ouvrir_fermer_xml($niveau);
		return $xml;
	}
	public function afficher($mode) {return null;}
}
 
class pl3_objet_theme_meta_telechargement extends pl3_outil_objet_xml {
	/* Fiche */
	const NOM_FICHE = "theme";

	/* Balise */
	const NOM_BALISE = "telechargement";
	public static $Balise = array("nom" => self::NOM_BALISE, "type" => self::TYPE_LIEN);

	/* Attributs */
	public static $Liste_attributs = array();

	/* Méthodes */
	public function ecrire_xml($niveau) {
		$xml = $this->ouvrir_fermer_xml($niveau);
		return $xml;
	}
	public function afficher($mode) {return null;}
}

class pl3_objet_theme_meta extends pl3_outil_objet_composite_xml {
	/* Fiche */
	const NOM_FICHE = "theme";

	/* Balise */
	const NOM_BALISE = "meta";
	public static $Balise = array("nom" => self::NOM_BALISE, "type" => self::TYPE_COMPOSITE);

	/* Attributs */
	public static $Liste_attributs = array();

	/* Constructeur */
	public function __construct($id, &$parent, &$noeud = null) {
		$this->declarer_element("pl3_objet_theme_meta_auteur");
		$this->declarer_element("pl3_objet_theme_meta_forge");
		$this->declarer_element("pl3_objet_theme_meta_documentation");
		$this->declarer_element("pl3_objet_theme_meta_telechargement");
		parent::__construct($id, $parent, $noeud);
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