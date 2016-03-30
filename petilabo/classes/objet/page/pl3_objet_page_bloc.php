<?php

/**
 * Classe de gestion des blocs
 */
 
class pl3_objet_page_bloc extends pl3_outil_objet_xml {
	const NOM_BALISE = "bloc";
	const NOM_ATTRIBUT_STYLE = "style";
	const NOM_ATTRIBUT_TAILLE = "taille";
	public static $Liste_attributs = array(
		array("nom" => self::NOM_ATTRIBUT_STYLE, "type" => self::TYPE_REFERENCE, "reference" => "pl3_objet_style_style_bloc"),
		array("nom" => self::NOM_ATTRIBUT_TAILLE, "type" => self::TYPE_ENTIER));

	private $objets = array();
	
	public function remplacer_objet(&$nouvel_objet) {
		$nouvel_id = $nouvel_objet->lire_id();
		$nb_objets = count($this->objets);
		for ($cpt = 0;$cpt < $nb_objets;$cpt ++) {
			$objet = $this->objets[$cpt];
			if ($objet != null) {
				$id = $objet->lire_id();
				if ($id == $nouvel_id) {
					$this->objets[$cpt] = $nouvel_objet;
					return true;
				}
			}
		}
		return false;
	}

	public function charger_xml() {
		$this->objets = pl3_outil_parser_xml::Parser_toute_balise(pl3_fiche_page::NOM_FICHE, $this, $this->noeud);
	}

	public function ecrire_xml($niveau) {
		$attr_style = $this->get_xml_attribut(self::NOM_ATTRIBUT_STYLE);
		$attr_taille = $this->get_xml_attribut(self::NOM_ATTRIBUT_TAILLE);
		$xml = $this->ouvrir_xml($niveau, array($attr_style, $attr_taille));
		foreach ($this->objets as $objet) {
			$xml .= $objet->ecrire_xml(1 + $niveau);
		}
		$xml .= $this->fermer_xml($niveau);
		return $xml;
	}
	
	public function afficher() {
		$ret = "";
		$taille = $this->get_attribut_entier(self::NOM_ATTRIBUT_TAILLE, 1);
		$ret .= "<div id=\"bloc-".$this->lire_id_parent()."-".$this->lire_id()."\" class=\"bloc\" style=\"flex-grow:".$taille.";\">\n";
		$ret .= "<p>".$taille."</p>\n";
		foreach($this->objets as $objet) {
			$ret .= $objet->afficher();
		}
		$ret .= "</div>\n";
		return $ret;
	}
	
	/* Recherches */
	public function chercher_objet_par_id($id) {
		foreach($this->objets as $instance) {
			$valeur_id = $instance->lire_id();
			if (!(strcmp($valeur_id, $id))) {return $instance;}
		}
		return null;
	}
}