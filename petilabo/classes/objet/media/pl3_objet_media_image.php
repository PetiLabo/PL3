<?php

/**
 * Classe de gestion des images
 */
 
class pl3_objet_media_image_fichier extends pl3_outil_objet_xml {
	const NOM_BALISE = "fichier";
	public static $Balise = array("nom" => self::NOM_BALISE, "type" => self::TYPE_FICHIER);
	public static $Liste_attributs = array();
	
	public function ecrire_xml($niveau) {
		$xml = $this->ouvrir_fermer_xml($niveau);
		return $xml;
	}
	
	public function afficher($mode) {
		echo "<!-- Fichier -->\n";
	}
}

class pl3_objet_media_image_alt extends pl3_outil_objet_xml { 
	const NOM_BALISE = "alt";
	public static $Balise = array("nom" => self::NOM_BALISE, "type" => self::TYPE_REFERENCE, "reference" => "pl3_objet_texte_texte");
	public static $Liste_attributs = array();
	
	public function ecrire_xml($niveau) {
		$xml = $this->ouvrir_fermer_xml($niveau);
		return $xml;
	}
	
	public function afficher($mode) {
		echo "<!-- Alt -->\n";
	}
}

class pl3_objet_media_image extends pl3_outil_objet_composite_xml {
	const NOM_BALISE = "image";
	public static $Balise = array("nom" => self::NOM_BALISE, "type" => self::TYPE_COMPOSITE);
	public static $Liste_attributs = array(
		array("nom" => self::NOM_ATTRIBUT_NOM, "type" => self::TYPE_CHAINE));
	
	public function __construct(&$source_page, $nom_fiche, $id, &$parent, &$noeud = null) {
		$this->declarer_element(pl3_objet_media_image_fichier::NOM_BALISE);
		$this->declarer_element(pl3_objet_media_image_alt::NOM_BALISE);
		parent::__construct($source_page, $nom_fiche, $id, $parent, $noeud);
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
		$ret = "";
		$ret .= "<!-- Image -->\n";
		$ret .= $this->afficher_elements_xml($mode);
		return $ret;
	}
}