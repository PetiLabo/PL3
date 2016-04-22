<?php

/**
 * Classe de gestion des titres
 */
 
class pl3_objet_page_titre extends pl3_outil_objet_simple_xml {
	/* Icone */
	const NOM_ICONE = "fa-text-height";

	/* Fiche */
	const NOM_FICHE = "page";

	/* Balise */
	const NOM_BALISE = "titre";
	public static $Balise = array("nom" => self::NOM_VALEUR, "type" => self::TYPE_INDIRECTION, "reference" => "pl3_objet_texte_texte");
	
	/* Attributs */
	const NOM_ATTRIBUT_STYLE = "style";
	const NOM_ATTRIBUT_NIVEAU = "niveau";
	public static $Liste_attributs = array(
		array("nom" => self::NOM_ATTRIBUT_STYLE, "type" => self::TYPE_REFERENCE, "reference" => "pl3_objet_theme_style_texte"),
		array("nom" => self::NOM_ATTRIBUT_NIVEAU, "type" => self::TYPE_ENTIER));

	/* Initialisation */
	public function construire_nouveau() {
		/* Création d'une instance de texte riche */
		$source_page = $this->get_source_page();
		$objet_texte = $source_page->instancier_nouveau(self::$Balise["reference"]);
		if ($objet_texte) {
			$objet_texte->construire_nouveau();
			$source_page->enregistrer_nouveau($objet_texte);

			/* Ce nouveau texe riche est la valeur du nouveau paragraphe */
			$this->set_valeur($objet_texte->get_attribut_nom());
			$this->set_attribut_niveau(1);
		}
	}
	
	/* Affichage */
	public function afficher($mode) {
		$ret = "";
		$source_page = $this->get_source_page();
		$nom_texte = $this->get_valeur();
		$texte = $source_page->chercher_liste_textes_par_nom(pl3_objet_texte_texte::NOM_BALISE, $nom_texte);
		if ($texte != null) {
			$html_id = $this->get_html_id();
			$valeur_texte = $texte->get_valeur();
			$niveau = $this->get_attribut_entier(self::NOM_ATTRIBUT_NIVEAU);
			$style = $this->get_attribut_chaine(self::NOM_ATTRIBUT_STYLE);
			if (strlen($style) == 0) {$style = _NOM_STYLE_DEFAUT;}
			$ret .= "<div class=\"container_titre\">\n";
			$ret .= "<h".$niveau." id=\"".$html_id."\" class=\"titre objet_editable texte_".$style."\">".$valeur_texte."</h".$niveau.">\n";
			$ret .= "</div>\n";
		}
		return $ret;
	}
}