<?php

/**
 * Classe de gestion des textes
 */

class pl3_objet_texte_texte extends pl3_outil_objet_xml {
	/* Fiche */
	const NOM_FICHE = "texte";

	/* Balise */
	const NOM_BALISE = "texte";
	public static $Balise = array("nom" => self::NOM_BALISE, "type" => self::TYPE_CHAINE);
	
	/* Attributs */
	public static $Liste_attributs = array(
		array("nom" => self::NOM_ATTRIBUT_NOM, "type" => self::TYPE_CHAINE));

	/* Initialisation */
	public function construire_nouveau() {
		$this->construire_nouveau_nom();
		$this->set_valeur("[...]");
	}
	
	/* Méthodes */
	public function ecrire_xml($niveau) {
		$attr_nom = $this->get_xml_attribut(self::NOM_ATTRIBUT_NOM);
		$xml = $this->ouvrir_fermer_xml($niveau, array($attr_nom));
		return $xml;
	}
	
	public function charger_xml() {
		$this->parser_valeur($this->noeud);
	}
	
	public function afficher($mode) {
		echo "<!-- Texte -->\n";
	}
}