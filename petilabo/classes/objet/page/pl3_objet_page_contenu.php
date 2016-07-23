<?php

/**
 * Classe de gestion des contenus
 */

class pl3_objet_page_contenu extends pl3_outil_objet_xml {
	const NOM_FICHE  = "page";
	const NOM_BALISE = "contenu";
	const TYPE       = self::TYPE_COMPOSITE;
	const ATTRIBUTS  = array(array("nom" => "style", "type" => self::TYPE_REFERENCE, "reference" => "pl3_objet_theme_style_contenu"));
	const OBJETS     = array("bloc");

	/* Création */
	public function construire_nouveau() {
		$bloc = $this->instancier_nouveau("pl3_objet_page_bloc");
		$this->ajouter_bloc($bloc);
	}

	/* Méthodes */
	public function charger_xml() {
		$this->liste_objets["pl3_objet_page_bloc"] = $this->parser_balise(pl3_objet_page_bloc::NOM_BALISE);
		foreach($this->liste_objets["pl3_objet_page_bloc"] as $bloc) {
			$bloc->charger_xml();
		}
		$this->maj_cardinal_et_largeur();
	}

	public function ecrire_xml($niveau) {
		$attr_style = $this->get_xml_attribut("style");
		$xml = $this->ouvrir_xml($niveau, array($attr_style));
		foreach($this->liste_objets["pl3_objet_page_bloc"] as $bloc) {
			$xml .= $bloc->ecrire_xml(1 + $niveau);
		}
		$xml .= $this->fermer_xml($niveau);
		return $xml;
	}

	public function ajouter_bloc(&$bloc) {
		$this->liste_objets["pl3_objet_page_bloc"][] = $bloc;
		$this->maj_cardinal_et_largeur();
	}

	/* Affichage */
	public function afficher($mode) {
		$style = $this->get_attribut_style();
		if (strlen($style) == 0) {$style = _NOM_STYLE_DEFAUT;}
		if (($mode == _MODE_ADMIN_GRILLE) || ($mode == _MODE_ADMIN_MAJ_GRILLE)) {
			$ret = $this->afficher_grille($mode, $style);
		}
		else {
			$ret = $this->afficher_standard($mode, $style);
		}
		return $ret;
	}

	private function afficher_standard($mode, $style) {
		$ret = "";
		$classe = "contenu contenu_".$style;
		$ret .= "<div id=\"contenu-".$this->id."\" class=\"".$classe."\">\n";
		foreach ($this->liste_objets["pl3_objet_page_bloc"] as $bloc) {$ret .= $bloc->afficher($mode);}
		$ret .= "</div>\n";
		return $ret;
	}

	private function afficher_grille($mode, $style) {
		$ret = "";
		if ($mode == _MODE_ADMIN_GRILLE) {
			$ret .= "<div class=\"contenu_flex contenu_".$style."\" style=\"\">\n";
			// Affichage de la poignée du contenu
			$ret .= "<div class=\"contenu_flex_poignee\">";
			$ret .= $this->afficher_grille_poignee_contenu();
			$ret .= "</div>\n";
			// Affichage des blocs
			$ret .= "<div class=\"contenu_flex_blocs\">\n";
		}
		$ret .= $this->afficher_grille_blocs();
		if ($mode == _MODE_ADMIN_GRILLE) {
			$ret .= "</div>\n";
			// Affichage du bouton d'ajout de contenu
			$ret .= "<div class=\"contenu_flex_poignee\">";
			$ret .= $this->afficher_grille_poignee_bloc();
			$ret .= "</div>\n";
			$ret .= "</div>\n";
		}
		return $ret;
	}

	private function afficher_grille_blocs() {
		$ret = "";
		$ret .= "<div id=\"contenu-".$this->id."\" class=\"contenu_grille\">\n";
		foreach ($this->liste_objets["pl3_objet_page_bloc"] as $bloc) {$ret .= $bloc->afficher(_MODE_ADMIN_GRILLE);}
		$ret .= "</div>\n";
		return $ret;
	}

	private function afficher_grille_poignee_contenu() {
		$ret = "";
		$ret .= "<div class=\"bloc bloc_ajout\">";
		$ret .= "<p id=\"poignee-contenu-".$this->id."\" class=\"contenu_poignee_edit\">";
		$ret .= "<a class=\"fa fa-minus\" href=\"#\" title=\"Editer le contenu\"></a>";
		$ret .= "</p></div>\n";
		return $ret;
	}

	private function afficher_grille_poignee_bloc() {
		$ret = "";
		$ret .= "<div class=\"bloc bloc_ajout\">";
		$ret .= "<p id=\"poignee-bloc-".$this->id."\" class=\"bloc_poignee_ajout\">";
		$ret .= "<a class=\"fa fa-bars fa-rotate-90\" href=\"#\" title=\"Ajouter un bloc\"></a>";
		$ret .= "</p></div>\n";
		return $ret;
	}

	public function maj_cardinal_et_largeur() {
		$nombre_total = 0;
		$largeur_totale = 0;
		$liste_blocs = $this->liste_objets["pl3_objet_page_bloc"];
		foreach($liste_blocs as $bloc) {
			$largeur = (int) $bloc->get_attribut_taille();
			$largeur_totale += ($largeur > 0)?$largeur:1;
			$nombre_total += 1;
		}
		foreach($liste_blocs as $bloc) {
			$bloc->set_largeur_parent($largeur_totale);
			$bloc->set_cardinal_parent($nombre_total);
		}
	}
}