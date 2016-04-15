<?php

/**
 * Classe de gestion des blocs
 */
 
class pl3_objet_page_bloc extends pl3_outil_objet_xml {
	/* Fiche */
	const NOM_FICHE = "page";

	/* Balise */
	const NOM_BALISE = "bloc";
	public static $Balise = array("nom" => self::NOM_BALISE, "type" => self::TYPE_COMPOSITE);
	
	/* Attributs */
	const NOM_ATTRIBUT_STYLE = "style";
	const NOM_ATTRIBUT_TAILLE = "taille";
	public static $Liste_attributs = array(
		array("nom" => self::NOM_ATTRIBUT_STYLE, "type" => self::TYPE_REFERENCE, "reference" => "pl3_objet_style_style_bloc"),
		array("nom" => self::NOM_ATTRIBUT_TAILLE, "type" => self::TYPE_ENTIER));

	/* Autres propriétés */
	private $objets = array();
	
	/* Méthode */
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
		$this->objets = $this->source_page->parser_toute_balise(pl3_fiche_page::NOM_FICHE, $this, $this->noeud);
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
	
	public function afficher($mode) {
		$ret = "";
		$num_id_bloc = $this->lire_id_parent()."-".$this->lire_id();
		$taille = $this->get_attribut_entier(self::NOM_ATTRIBUT_TAILLE, 1);
		$ret .= "<div id=\"bloc-".$num_id_bloc."\" class=\"bloc\" style=\"flex-grow:".$taille.";\">\n";
		foreach($this->objets as $objet) {
			$ret .= $objet->afficher($mode);
		}
		if ($mode == _MODE_ADMIN) {
			$liste_objets_avec_icone = $this->source_page->get_page()->get_liste_objets_avec_icone();
			if (count($liste_objets_avec_icone) > 0) {
				$ret .= "<p id=\"poignee-bloc-".$num_id_bloc."\" class=\"bloc_poignee_ajout\">";
				foreach ($liste_objets_avec_icone as $nom_balise => $nom_icone) {
					$ret .= "<a class=\"fa ".$nom_icone."\" href=\"#\" title=\"Ajouter une balise ".$nom_balise."\"></a>";
				}
				$ret .= "<span class=\"fa fa-trash bloc_poignee_corbeille\"></span>";
				$ret .= "</p>\n";
			}
		}
		$ret .= "</div>\n";
		return $ret;
	}
	
	/* Accesseur */
	public function lire_nb_objets() {
		return count($this->objets);
	}
	
	/* Recherches */
	public function chercher_objet_par_id($id) {
		foreach($this->objets as $instance) {
			$valeur_id = $instance->lire_id();
			if (!(strcmp($valeur_id, $id))) {return $instance;}
		}
		return null;
	}
	
	/* Mutateur */
	public function reordonner($tab_ordre) {
		$nouveaux_objets = array();
		foreach ($tab_ordre as $no_ordre) {
			$index = ((int) $no_ordre) - 1;
			$nouveaux_objets[] = &$this->objets[$index];
		}
		$this->objets = $nouveaux_objets;
	}
}