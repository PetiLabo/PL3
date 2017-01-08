<?php

/**
 * Classe de gestion des galeries
 */
 
class pl3_objet_media_galerie_element extends pl3_outil_objet_xml {
	/* Fiche */
	const NOM_FICHE = "media";

	/* Balise */
	const NOM_BALISE = "element";
	public static $Balise = array("nom" => self::NOM_BALISE, "type" => self::TYPE_REFERENCE, "reference" => "pl3_objet_media_image");
	public static $Liste_attributs = array();
	
	public function ecrire_xml($niveau) {
		$xml = $this->ouvrir_fermer_xml($niveau);
		return $xml;
	}
	
	public function afficher($mode) {
		$ret = "";
		$ret .= "<!-- Element -->\n";
		return $ret;
	}
}

class pl3_objet_media_galerie extends pl3_outil_objet_xml {
	/* Fiche */
	const NOM_FICHE = "media";

	/* Balise */
	const NOM_BALISE = "galerie";
	public static $Balise = array("nom" => self::NOM_BALISE, "type" => self::TYPE_COMPOSITE);
	public static $Liste_attributs = array(
		array("nom" => self::NOM_ATTRIBUT_NOM, "type" => self::TYPE_CHAINE));
	private $elements = array();
	
	public function charger_xml() {
		$this->elements = $this->parser_balise_fille(pl3_objet_media_galerie_element::NOM_BALISE, false);
	}
	
	public function get_elements() {return $this->elements;}

	public function ecrire_xml($niveau) {
		$attr_nom = $this->get_xml_attribut(self::NOM_ATTRIBUT_NOM);
		$xml = $this->ouvrir_xml($niveau, array($attr_nom));
		foreach ($this->elements as $element) {
			$xml .= $element->ecrire_xml(1 + $niveau);
		}
		$xml .= $this->fermer_xml($niveau);
		return $xml;
	}
	
	public function afficher($mode) {
		$ret = "";
		$ret .= "<!-- Galerie -->\n";
		foreach ($this->elements as $element) {
			$ret .= $element->afficher($mode);
		}
		return $ret;
	}
}