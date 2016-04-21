<?php
require_once _CHEMIN_OBJET."theme/"._STYLE_COMMUN_CSS;

/**
 * Classe de gestion des styles de contenu
 */

class pl3_objet_theme_style_contenu extends pl3_outil_objet_composite_xml {
	/* Fiche */
	const NOM_FICHE = "theme";

	/* Balise */
	const NOM_BALISE = "style_contenu";
	public static $Balise = array("nom" => self::NOM_BALISE, "type" => self::TYPE_COMPOSITE);
	
	/* Attributs */
	public static $Liste_attributs = array(
		array("nom" => self::NOM_ATTRIBUT_NOM, "type" => self::TYPE_CHAINE));

	/* Méthodes */
	public function __construct($id, &$parent, &$noeud = null) {
		$this->declarer_element(pl3_objet_theme_style_marge::NOM_BALISE);
		$this->declarer_element(pl3_objet_theme_style_retrait::NOM_BALISE);
		$this->declarer_element(pl3_objet_theme_style_bordure::NOM_BALISE);
		$this->declarer_element(pl3_objet_theme_style_fond::NOM_BALISE);
		$this->declarer_element(pl3_objet_theme_style_css::NOM_BALISE);
		parent::__construct($id, $parent, $noeud);
	}

	public function charger_xml() {
		$this->charger_elements_xml();
	}
	
	public function parser_balise_fille($nom_balise) {
		$source_page = $this->get_source_page();
		$tab_ret = $source_page->parser_balise_fille(self::NOM_FICHE, $this, "pl3_objet_theme_style", $nom_balise, $this->noeud);
		$nb_ret = (int) count($tab_ret);
		$ret = ($nb_ret > 0)?$tab_ret[$nb_ret - 1]:null;
		return $ret;
	}

	public function ecrire_xml($niveau) {
		$attr_nom = $this->get_xml_attribut(self::NOM_ATTRIBUT_NOM);
		$xml = $this->ouvrir_xml($niveau, array($attr_nom));
		$xml .= $this->ecrire_elements_xml(1 + $niveau);
		$xml .= $this->fermer_xml($niveau);
		return $xml;
	}
	
	public function afficher($mode) {
		$nom = $this->get_attribut_nom();
		$ret = ".contenu_".$nom."{";
		$ret .= $this->afficher_elements_xml($mode);
		$ret .= "}\n";
		return $ret;
	}
}