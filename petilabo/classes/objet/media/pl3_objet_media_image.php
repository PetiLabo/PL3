<?php

/**
 * Classe de gestion des images
 */
 
class pl3_objet_media_image_fichier extends pl3_outil_objet_xml {
	const NOM_BALISE = "fichier";
	public static $Noms_attributs = array();
	
	public function ecrire_xml($niveau) {
		$xml = $this->ouvrir_fermer_xml($niveau);
		return $xml;
	}
	
	public function afficher() {
		echo "<!-- Fichier -->\n";
	}
}

class pl3_objet_media_image_alt extends pl3_outil_objet_xml {
	const NOM_BALISE = "alt";
	public static $Noms_attributs = array();
	
	public function ecrire_xml($niveau) {
		$xml = $this->ouvrir_fermer_xml($niveau);
		return $xml;
	}
	
	public function afficher() {
		echo "<!-- Alt -->\n";
	}
}

class pl3_objet_media_image extends pl3_outil_objet_xml {
	const NOM_BALISE = "image";
	const NOM_ATTRIBUT_NOM = "nom";
	public static $Noms_attributs = array(self::NOM_ATTRIBUT_NOM);
	public static $Noms_elements = array(
		pl3_objet_media_image_fichier::NOM_BALISE,
		pl3_objet_media_image_alt::NOM_BALISE);
	private $elements = array();
	
	public function charger_xml() {
		foreach (self::$Noms_elements as $nom_element) {
			$element = $this->parser_balise_fille($nom_element);
			if ($element != null) {$this->elements[$nom_element] = $element;}
		}
	}

	public function ecrire_xml($niveau) {
		$attr_nom = $this->get_xml_attribut_chaine(self::NOM_ATTRIBUT_NOM);
		$xml = $this->ouvrir_xml($niveau, array($attr_nom));
		foreach ($this->elements as $element) {
			$xml .= $element->ecrire_xml(1 + $niveau);
		}
		$xml .= $this->fermer_xml($niveau);
		return $xml;
	}
	
	public function afficher() {
		echo "<!-- Image -->\n";
		foreach ($this->elements as $element) {
			$element->afficher();
		}
	}
	
	public function get_nom_fichier() {
		$nom_fichier = null;
		if (isset($this->elements[pl3_objet_media_image_fichier::NOM_BALISE])) {
			$fichier = $this->elements[pl3_objet_media_image_fichier::NOM_BALISE];
			$nom_fichier = $fichier->get_valeur();
		}
		return $nom_fichier;
	}
}