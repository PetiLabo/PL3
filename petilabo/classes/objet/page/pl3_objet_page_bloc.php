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
		array("nom" => self::NOM_ATTRIBUT_NOM, "type" => self::TYPE_CHAINE),
		array("nom" => self::NOM_ATTRIBUT_STYLE, "type" => self::TYPE_REFERENCE, "reference" => "pl3_objet_theme_style_bloc"),
		array("nom" => self::NOM_ATTRIBUT_TAILLE, "type" => self::TYPE_ENTIER, "min" => 1));

	/* Autres propriétés */
	private $objets = array();
	
	/* Cardinal et largeur du parent (pour affichage en inline-block) */
	private $largeur_parent = 1;
	public function set_largeur_parent($largeur_parent) {
		if ($largeur_parent > 0) {$this->largeur_parent = $largeur_parent;}
	}
	private $cardinal_parent = 1;
	public function set_cardinal_parent($cardinal_parent) {
		if ($cardinal_parent > 0) {$this->cardinal_parent = $cardinal_parent;}
	}
	
	/* Gestion des objet dans le bloc */
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
	public function instancier_nouveau($nom_classe) {
		$objet = new $nom_classe(1 + count($this->objets), $this);
		return $objet;
	}
	public function ajouter_objet(&$objet) {
		$this->objets[] = $objet;
	}
	public function retirer_objet($objet_id) {
		$liste_objets = array();
		$nb_objets = count($this->objets);
		$id_cpt = 1;
		for ($cpt = 0;$cpt < $nb_objets;$cpt ++) {
			$objet = &$this->objets[$cpt];
			if ($objet != null) {
				if ($objet->lire_id() != $objet_id) {
					$objet->ecrire_id($id_cpt);
					$liste_objets[] = $objet;
					$id_cpt += 1;
				}
				else {
					$objet->detruire();
					unset($objet);
				}
			}
		}
		$this->objets = $liste_objets;
	}
	public function detruire() {
		foreach($this->objets as $objet) {
			$objet->detruire();
			unset($objet);
		}
	}
	
	public function charger_xml() {
		$source_page = $this->get_source_page();
		$this->objets = $source_page->parser_toute_balise(pl3_fiche_page::NOM_FICHE, $this, $this->noeud);
	}

	public function ecrire_xml($niveau) {
		$attr_nom = $this->get_xml_attribut(self::NOM_ATTRIBUT_NOM);
		$attr_style = $this->get_xml_attribut(self::NOM_ATTRIBUT_STYLE);
		$attr_taille = $this->get_xml_attribut(self::NOM_ATTRIBUT_TAILLE);
		$xml = $this->ouvrir_xml($niveau, array($attr_nom, $attr_style, $attr_taille));
		foreach ($this->objets as $objet) {
			$xml .= $objet->ecrire_xml(1 + $niveau);
		}
		$xml .= $this->fermer_xml($niveau);
		return $xml;
	}

	public function afficher($mode) {
		$num_id_bloc = $this->lire_id_parent()."-".$this->lire_id();
		$taille = $this->get_attribut_entier(self::NOM_ATTRIBUT_TAILLE, 1);
		$style = $this->get_attribut_style();
		if (strlen($style) == 0) {$style = _NOM_STYLE_DEFAUT;}
		if ($mode == _MODE_ADMIN_GRILLE) {
			$ret = $this->afficher_grille($num_id_bloc, $taille, $style);
		}
		else {
			$ret = $this->afficher_standard($mode, $num_id_bloc, $taille, $style);
		}
		return $ret;
	}
	
	private function afficher_standard($mode, $num_id_bloc, $taille, $style) {
		$ret = "";
		$source_page = $this->get_source_page();
		$classe = "bloc bloc_".$style;
		$style_bloc = "flex-grow:".$taille.";";
		$ret .= "<div id=\"bloc-".$num_id_bloc."\" class=\"".$classe."\" style=\"".$style_bloc."\">";
		foreach($this->objets as $objet) {$ret .= $objet->afficher($mode);}
		if ($mode == _MODE_ADMIN_OBJETS) {
			$liste_objets_avec_icone = $source_page->get_page()->get_liste_objets_avec_icone();
			if (count($liste_objets_avec_icone) > 0) {
				$ret .= "<p id=\"poignee-bloc-".$num_id_bloc."\" class=\"bloc_poignee_ajout\">";
				foreach ($liste_objets_avec_icone as $nom_balise => $nom_icone) {
					$nom_classe = _PREFIXE_OBJET.(self::NOM_FICHE)."_".$nom_balise;
					$ret .= "<a class=\"fa ".$nom_icone."\" href=\"".$nom_classe."\" title=\"Ajouter un objet ".$nom_balise."\"></a>";
				}
				$ret .= "</p>\n";
			}
		}
		$ret .= "</div>";
		return $ret;
	}
	
	private function afficher_grille($num_id_bloc, $taille, $style) {
		/* En mode grille on affiche en inline-block à cause des pbs JQuery UI Sortable / display flex */
		$ret = "";
		$classe = "bloc_grille bloc_".$style;
		$taille_totale = 1000 - 20 * $this->cardinal_parent;
		$largeur_bloc = floor(($taille_totale * $taille) / $this->largeur_parent) / 10;
		$style_bloc = "width:".$largeur_bloc."%;";
		$ret .= "<div id=\"bloc-".$num_id_bloc."\" class=\"".$classe."\" style=\"".$style_bloc."\">";
		$nom = $this->get_attribut_nom();
		$ret .= "<p class=\"bloc_legende_nom\">".$nom." ".$this->largeur_parent."</p>";
		$ret .= "</div>";
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