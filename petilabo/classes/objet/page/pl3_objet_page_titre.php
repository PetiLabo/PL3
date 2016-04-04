<?php

/**
 * Classe de gestion des titres
 */
 
class pl3_objet_page_titre extends pl3_outil_objet_xml {
	/* Balise */
	const NOM_BALISE = "titre";
	public static $Balise = array("nom" => self::NOM_VALEUR, "type" => self::TYPE_REFERENCE, "reference" => "pl3_objet_texte_texte");
	
	/* Attributs */
	const NOM_ATTRIBUT_STYLE = "style";
	const NOM_ATTRIBUT_NIVEAU = "niveau";
	public static $Liste_attributs = array(
		array("nom" => self::NOM_ATTRIBUT_STYLE, "type" => self::TYPE_REFERENCE, "reference" => "pl3_objet_style_style_texte"),
		array("nom" => self::NOM_ATTRIBUT_NIVEAU, "type" => self::TYPE_ENTIER));

	/* Méthodes */
	public function ecrire_xml($niveau) {
		$attr_style = $this->get_xml_attribut(self::NOM_ATTRIBUT_STYLE);
		$attr_niveau = $this->get_xml_attribut(self::NOM_ATTRIBUT_NIVEAU);
		$xml = $this->ouvrir_fermer_xml($niveau, array($attr_style, $attr_niveau));
		return $xml;
	}
	
	public function afficher($mode) {
		$ret = "";
		$html_id = $this->get_html_id();
		$valeur = $this->get_valeur();
		$niveau = $this->get_attribut_entier(self::NOM_ATTRIBUT_NIVEAU);
		$style = $this->get_attribut_chaine(self::NOM_ATTRIBUT_STYLE);
		$ret .= "<div class=\"container_titre\">\n";
		$ret .= "<h".$niveau." id=\"".$html_id."\" class=\"titre objet_editable ".$style."\">".$valeur."</h".$niveau.">\n";
		$ret .= "</div>\n";
		return $ret;
	}
}