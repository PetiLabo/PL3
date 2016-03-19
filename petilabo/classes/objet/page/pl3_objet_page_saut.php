<?php

/**
 * Classe de gestion des sauts
 */
 
class pl3_objet_page_saut extends pl3_outil_objet_xml {
	const NOM_BALISE = "saut";
	public static $Noms_attributs = array();

	public function ecrire_xml($niveau) {
		$xml = $this->ouvrir_fermer_xml($niveau);
		return $xml;
	}
	
	public function afficher() {
		$html_id = $this->get_html_id();
		echo "<div class=\"container_saut\"><p id=\"".$html_id."\" class=\"saut objet_editable\">&nbsp;</p></div>\n";
	}
}