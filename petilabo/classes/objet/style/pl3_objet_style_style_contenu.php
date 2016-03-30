<?php

/**
 * Classe de gestion des styles de contenu
 */

class pl3_objet_style_style_bloc extends pl3_outil_objet_composite_xml {
	const NOM_BALISE = "style_contenu";
	const NOM_ATTRIBUT_NOM = "nom";
	public static $Liste_attributs = array(
		array("nom" => self::NOM_ATTRIBUT_NOM, "type" => self::TYPE_CHAINE));
	
	public function __construct($nom_fiche, $id, &$parent, &$noeud = null) {
		parent::__construct($nom_fiche, $id, $parent, $noeud);
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
	
	public function afficher() {
		echo "<!-- Style de contenu -->\n";
		$this->afficher_elements_xml();
	}
}