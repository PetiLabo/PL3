<?php

/**
 * Classe de gestion des contenus
 */
 
class pl3_objet_page_contenu extends pl3_outil_objet_xml {
	const NOM_BALISE = "contenu";
	const NOM_ATTRIBUT_STYLE = "style";
	public static $Liste_attributs = array(
		array("nom" => self::NOM_ATTRIBUT_STYLE, "type" => self::TYPE_REFERENCE, "reference" => "pl3_objet_style_style_contenu"));
	
	public function __construct($nom_fiche, $id, $objet_parent, &$noeud = null) {
		$this->declarer_objet("pl3_objet_page_bloc");
		parent::__construct($nom_fiche, $id, $objet_parent, $noeud);
	}

	public function charger_xml() {
		$this->liste_objets["pl3_objet_page_bloc"] = $this->parser_balise(pl3_objet_page_bloc::NOM_BALISE);
		foreach($this->liste_objets["pl3_objet_page_bloc"] as $bloc) {
			$bloc->charger_xml();
		}
	}
	
	public function ecrire_xml($niveau) {
		$attr_style = $this->get_xml_attribut_chaine(self::NOM_ATTRIBUT_STYLE);
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
	
	public function afficher() {
		$ret = "";
		$ret .= "<div id=\"contenu-".$this->id."\" class=\"contenu\">\n";
		$liste_blocs = $this->liste_objets["pl3_objet_page_bloc"];
		foreach ($liste_blocs as $bloc) {
			$ret .= $bloc->afficher();
		}
		$ret .= "</div>\n";
		return $ret;
	}
}