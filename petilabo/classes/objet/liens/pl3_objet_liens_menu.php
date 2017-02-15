<?php

/**
 * Classe de gestion des menus
 */
 
class pl3_objet_liens_menu_choix extends pl3_outil_objet_xml {
	/* Fiche */
	const NOM_FICHE = "liens";

	/* Balise */
	const NOM_BALISE = "choix";
	public static $Balise = array("nom" => self::NOM_BALISE, "type" => self::TYPE_REFERENCE, "reference" => "pl3_objet_liens_lien");
	public static $Liste_attributs = array();
	
	public function ecrire_xml($niveau) {
		$xml = $this->ouvrir_fermer_xml($niveau);
		return $xml;
	}
	
	public function afficher($mode) {
		$ret = "";
		$ret .= "<!-- Choix -->\n";
		return $ret;
	}
}

class pl3_objet_liens_menu extends pl3_outil_objet_xml {
	/* Fiche */
	const NOM_FICHE = "liens";

	/* Balise */
	const NOM_BALISE = "menu";
	public static $Balise = array("nom" => self::NOM_BALISE, "type" => self::TYPE_COMPOSITE);
	public static $Liste_attributs = array(
		array("nom" => self::NOM_ATTRIBUT_NOM, "type" => self::TYPE_CHAINE));
	private $choix = array();
	
	public function charger_xml() {
		$this->choix = $this->parser_balise_fille(pl3_objet_liens_menu_choix::NOM_BALISE, false);
	}
	
	public function get_elements() {return $this->choix;}
	public function set_elements($tab_nom_lien) {
		unset($this->choix);
		$this->choix = array();
		$element_id = 1;
		foreach($tab_nom_lien as $nom_lien) {
			$element = new pl3_objet_liens_menu_choix($element_id, $this);
			$element->set_valeur($nom_lien);
			$this->choix[] = $element;
			$element_id += 1;
		}
	}

	public function ecrire_xml($niveau) {
		$attr_nom = $this->get_xml_attribut(self::NOM_ATTRIBUT_NOM);
		$xml = $this->ouvrir_xml($niveau, array($attr_nom));
		foreach ($this->choix as $element) {
			$xml .= $element->ecrire_xml(1 + $niveau);
		}
		$xml .= $this->fermer_xml($niveau);
		return $xml;
	}
	
	public function afficher($mode) {
		$ret = "";
		$ret .= "<!-- Menu -->\n";
		foreach ($this->choix as $element) {
			$ret .= $element->afficher($mode);
		}
		return $ret;
	}
}