<?php

/**
 * Classe de gestion des styles de puces
 */
 
class pl3_objet_style_style_puce_icone extends pl3_outil_objet_xml {
	const NOM_BALISE = "icone";
	public static $Liste_attributs = array();
	
	public function ecrire_xml($niveau) {
		$xml = $this->ouvrir_fermer_xml($niveau);
		return $xml;
	}
	
	public function afficher() {
		echo "<!-- Icone -->\n";
	}
}

class pl3_objet_style_style_puce_taille extends pl3_outil_objet_xml {
	const NOM_BALISE = "taille";
	public static $Liste_attributs = array();
	
	public function ecrire_xml($niveau) {
		$xml = $this->ouvrir_fermer_xml($niveau);
		return $xml;
	}
	
	public function afficher() {
		echo "<!-- Taille -->\n";
	}
}

class pl3_objet_style_style_puce_ombre extends pl3_outil_objet_xml {
	const NOM_BALISE = "ombre";
	public static $Liste_attributs = array();
	
	public function ecrire_xml($niveau) {
		$xml = $this->ouvrir_fermer_xml($niveau);
		return $xml;
	}
	
	public function afficher() {
		echo "<!-- Ombre -->\n";
	}
}

 
class pl3_objet_style_style_puce extends pl3_outil_objet_xml {
	const NOM_BALISE = "style_puce";
	const NOM_ATTRIBUT_NOM = "nom";
	public static $Liste_attributs = array(
		array("nom" => self::NOM_ATTRIBUT_NOM, "type" => self::TYPE_CHAINE));
	public static $Noms_elements = array(
		pl3_objet_style_style_puce_icone::NOM_BALISE,
		pl3_objet_style_style_puce_taille::NOM_BALISE,
		pl3_objet_style_style_puce_ombre::NOM_BALISE);
	private $elements = array();
	
	public function charger_xml() {
		foreach (self::$Noms_elements as $nom_element) {
			$element = $this->parser_balise_fille($nom_element);
			if ($element != null) {$this->elements[$nom_element] = $element;}
		}
	}

	public function ecrire_xml($niveau) {
		$attr_nom = $this->get_xml_attribut(self::NOM_ATTRIBUT_NOM);
		$xml = $this->ouvrir_xml($niveau, array($attr_nom));
		foreach ($this->elements as $element) {
			$xml .= $element->ecrire_xml(1 + $niveau);
		}
		$xml .= $this->fermer_xml($niveau);
		return $xml;
	}
	
	public function afficher() {
		echo "<!-- Styles de puce -->\n";
		foreach ($this->elements as $element) {
			$element->afficher();
		}
	}
}