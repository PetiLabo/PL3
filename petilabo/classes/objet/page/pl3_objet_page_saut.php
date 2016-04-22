<?php

/**
 * Classe de gestion des sauts
 */
 
class pl3_objet_page_saut extends pl3_outil_objet_simple_xml {
	/* Icone */
	const NOM_ICONE = "fa-arrows-v";
	
	/* Fiche */
	const NOM_FICHE = "page";

	/* Balise */
	const NOM_BALISE = "saut";
	public static $Balise = array("nom" => self::NOM_VALEUR, "type" => self::TYPE_ENTIER, "min" => 1);
	
	/* Attributs */
	public static $Liste_attributs = array();

	/* Initialisation */
	public function construire_nouveau() {
		$this->set_valeur(1);
	}

	/* Affichage */
	public function afficher($mode) {
		$ret = "";
		$html_id = $this->get_html_id();
		$valeur = $this->get_valeur();
		$ret .= "<div class=\"container_saut\">\n";
		$ret .= "<p id=\"".$html_id."\" class=\"saut objet_editable\" style=\"line-height:".$valeur.";\">&nbsp;</p>\n";
		$ret .= "</div>\n";
		return $ret;
	}
}