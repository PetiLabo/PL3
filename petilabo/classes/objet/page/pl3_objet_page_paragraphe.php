<?php

/**
 * Classe de gestion des paragraphes
 */
 
class pl3_objet_page_paragraphe extends pl3_outil_objet_xml {
	/* Icone */
	const NOM_ICONE = "fa-file-text-o ";

	/* Fiche */
	const NOM_FICHE = "page";

	/* Balise */
	const NOM_BALISE = "paragraphe";
	public static $Balise = array("nom" => self::NOM_VALEUR, "type" => self::TYPE_INDIRECTION, "reference" => "pl3_objet_texte_texte_riche");

	/* Attributs */
	const NOM_ATTRIBUT_STYLE = "style";
	public static $Liste_attributs = array(
		array("nom" => self::NOM_ATTRIBUT_STYLE, "type" => self::TYPE_REFERENCE, "reference" => "pl3_objet_style_style_texte"));

	/* Initialisation */
	public function construire_nouveau() {
		$source_page = $this->get_source_page();
		/* Création d'une instance de texte riche */
		$objet_texte_riche = $source_page->instancier_nouveau(self::$Balise["reference"]);
		if ($objet_texte_riche) {
			$objet_texte_riche->construire_nouveau();
			$source_page->enregistrer_nouveau($objet_texte_riche);

			/* Ce nouveau texe riche est la valeur du nouveau paragraphe */
			$this->set_valeur($objet_texte_riche->get_attribut_nom());
		}
	}

	/* Méthodes */
	public function ecrire_xml($niveau) {
		$attr_style = $this->get_xml_attribut(self::NOM_ATTRIBUT_STYLE);
		$xml = $this->ouvrir_fermer_xml($niveau, array($attr_style));
		return $xml;
	}
	
	public function afficher($mode) {
		$ret = "";
		$source_page = $this->get_source_page();
		$nom_texte = $this->get_valeur();
		$texte = $source_page->chercher_liste_textes_par_nom(pl3_objet_texte_texte_riche::NOM_BALISE, $nom_texte);
		if ($texte != null) {
			$html_id = $this->get_html_id();
			$valeur_texte = html_entity_decode($texte->get_valeur(), ENT_QUOTES, "UTF-8");
			$ret .= "<div class=\"container_paragraphe\">\n";
			$ret .= "<div id=\"".$html_id."\" class=\"paragraphe objet_editable\">".$valeur_texte."</div>\n";
			$ret .= "</div>\n";
		}
		return $ret;
	}
}