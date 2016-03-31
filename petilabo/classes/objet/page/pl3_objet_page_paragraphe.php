<?php

/**
 * Classe de gestion des paragraphes
 */
 
class pl3_objet_page_paragraphe extends pl3_outil_objet_xml {
	/* Balise */
	const NOM_BALISE = "paragraphe";
	public static $Balise = array("nom" => self::NOM_VALEUR, "type" => self::TYPE_REFERENCE, "reference" => "pl3_objet_texte_texte");
	
	/* Attributs */
	const NOM_ATTRIBUT_STYLE = "style";
	public static $Liste_attributs = array(
		array("nom" => self::NOM_ATTRIBUT_STYLE, "type" => self::TYPE_REFERENCE, "reference" => "pl3_objet_style_style_texte"));

	/* Méthodes */
	public function ecrire_xml($niveau) {
		$attr_style = $this->get_xml_attribut(self::NOM_ATTRIBUT_STYLE);
		$xml = $this->ouvrir_fermer_xml($niveau, array($attr_style));
		return $xml;
	}
	
	public function afficher() {
		$ret = "";
		$html_id = $this->get_html_id();
		$valeur = $this->get_valeur();
		$ret .= "<div class=\"container_paragraphe\">\n";
		$ret .= "<p id=\"".$html_id."\" class=\"paragraphe objet_editable\">".$valeur."</p>\n";
		$ret .= "</div>\n";
		return $ret;
	}
}