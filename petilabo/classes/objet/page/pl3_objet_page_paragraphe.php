<?php

/**
 * Classe de gestion des paragraphes
 */
 
class pl3_objet_page_paragraphe extends pl3_outil_objet_xml {
	const NOM_BALISE = "paragraphe";
	const NOM_ATTRIBUT_STYLE = "style";
	public static $Noms_attributs = array(self::NOM_ATTRIBUT_STYLE);

	public function ecrire_xml($niveau) {
		$attr_style = $this->get_xml_attribut_chaine(self::NOM_ATTRIBUT_STYLE);
		$xml = $this->ouvrir_fermer_xml($niveau, array($attr_style));
		return $xml;
	}
	
	public function afficher() {
		$html_id = $this->get_html_id();
		$valeur = $this->get_valeur();
		echo "<div class=\"container_paragraphe\"><p id=\"".$html_id."\" class=\"paragraphe objet_editable\">".$valeur."</p></div>\n";
	}
}