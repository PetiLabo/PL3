<?php

/**
 * Classe de gestion des contenus
 */
 
class pl3_objet_page_contenu extends pl3_outil_objet_xml {
	/* Fiche */
	const NOM_FICHE = "page";

	/* Balise */
	const NOM_BALISE = "contenu";
	public static $Balise = array("nom" => self::NOM_BALISE, "type" => self::TYPE_COMPOSITE);
	
	/* Attributs */
	const NOM_ATTRIBUT_STYLE = "style";
	public static $Liste_attributs = array(
		array("nom" => self::NOM_ATTRIBUT_STYLE, "type" => self::TYPE_REFERENCE, "reference" => "pl3_objet_style_style_contenu"));
	
	/* Constructeur */
	public function __construct($id, $objet_parent, &$noeud = null) {
		$this->declarer_objet("pl3_objet_page_bloc");
		parent::__construct($id, $objet_parent, $noeud);
	}

	/* Méthodes */
	public function charger_xml() {
		$this->liste_objets["pl3_objet_page_bloc"] = $this->parser_balise(pl3_objet_page_bloc::NOM_BALISE);
		foreach($this->liste_objets["pl3_objet_page_bloc"] as $bloc) {
			$bloc->charger_xml();
		}
	}
	
	public function ecrire_xml($niveau) {
		$attr_style = $this->get_xml_attribut(self::NOM_ATTRIBUT_STYLE);
		$xml = $this->ouvrir_xml($niveau, array($attr_style));
		foreach($this->liste_objets["pl3_objet_page_bloc"] as $bloc) {
			$xml .= $bloc->ecrire_xml(1 + $niveau);
		}
		$xml .= $this->fermer_xml($niveau);
		return $xml;
	}

	public function ajouter_bloc(&$bloc) {
		$this->liste_objets["pl3_objet_page_bloc"][] = $bloc;
	}
	
	public function afficher($mode) {
		$ret = "";
		$style = $this->get_attribut_style();
		if (strlen($style) == 0) {$style = _NOM_STYLE_DEFAUT;}
		$classe = "contenu contenu_".$style;
		$ret .= "<div id=\"contenu-".$this->id."\" class=\"".$classe."\">\n";
		$liste_blocs = $this->liste_objets["pl3_objet_page_bloc"];
		foreach ($liste_blocs as $bloc) {$ret .= $bloc->afficher($mode);}
		if ($mode == _MODE_ADMIN_GRILLE) {
			$ret .= "<div id=\"nouveau-bloc-".$this->id."\" class=\"bloc bloc_ajout\">";
			$ret .= "<p id=\"poignee-bloc-".$this->id."\" class=\"bloc_poignee_ajout\">";
			$ret .= "<a class=\"fa fa-plus\" href=\"#\" title=\"Ajouter un bloc\" style=\"height:100%;\"></a>";
			$ret .= "</p></div>\n";
		}
		$ret .= "</div>\n";
		return $ret;
	}
}