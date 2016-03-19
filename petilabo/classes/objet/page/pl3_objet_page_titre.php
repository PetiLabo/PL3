<?php

/**
 * Classe de gestion des titres
 */
 
class pl3_objet_page_titre extends pl3_outil_objet_xml {
	const NOM_BALISE = "titre";
	const NOM_ATTRIBUT_STYLE = "style";
	const NOM_ATTRIBUT_NIVEAU = "niveau";
	public static $Noms_attributs = array(self::NOM_ATTRIBUT_STYLE, self::NOM_ATTRIBUT_NIVEAU);

	public function ecrire_xml($niveau) {
		$attr_style = $this->get_xml_attribut_chaine(self::NOM_ATTRIBUT_STYLE);
		$attr_niveau = $this->get_xml_attribut_entier(self::NOM_ATTRIBUT_NIVEAU);
		$xml = $this->ouvrir_fermer_xml($niveau, array($attr_style, $attr_niveau));
		return $xml;
	}
	
	public function afficher() {
		$html_id = $this->get_html_id();
		$valeur = $this->get_valeur();
		echo "<div class=\"container_titre\"><h1 id=\"".$html_id."\" class=\"titre objet_editable\">".$valeur."</h1></div>\n";
	}
}