<?php

/**
 * Classe de gestion des textes
 */

class pl3_objet_texte_texte extends pl3_outil_objet_xml {
	/* Balise */
	const NOM_BALISE = "texte";
	public static $Balise = array("nom" => self::NOM_BALISE, "type" => self::TYPE_COMPOSITE);
	
	/* Attributs */
	public static $Liste_attributs = array(
		array("nom" => self::NOM_ATTRIBUT_NOM, "type" => self::TYPE_CHAINE));
		
	/* Méthodes */
	public function ecrire_xml($niveau) {
		$xml = $this->ouvrir_fermer_xml($niveau);
		return $xml;
	}
	
	public function charger_xml() {
		$this->parser_valeur($this->noeud);
	}
	
	public function afficher($mode) {
		echo "<!-- Texte -->\n";
	}
}