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
		array("nom" => self::NOM_ATTRIBUT_STYLE, "type" => self::TYPE_REFERENCE, "reference" => "pl3_objet_theme_style_contenu"));
	
	/* Constructeur */
	public function __construct($id, $objet_parent, &$noeud = null) {
		$this->declarer_objet("pl3_objet_page_bloc");
		parent::__construct($id, $objet_parent, $noeud);
	}
	public function construire_nouveau() {
		$bloc = $this->instancier_nouveau("pl3_objet_page_bloc");
		$this->ajouter_bloc($bloc);
	}

	/* Accesseurs */
	public function lire_nb_blocs() {return count($this->liste_objets["pl3_objet_page_bloc"]);}
	
	/* Mutateurs */
	public function ajouter_bloc(&$bloc) {
		$this->liste_objets["pl3_objet_page_bloc"][] = $bloc;
		$this->maj_cardinal_et_largeur();
	}
	public function retirer_bloc($bloc_id) {
		$liste_blocs = array();
		$nb_blocs = count($this->liste_objets["pl3_objet_page_bloc"]);
		$id_cpt = 1;
		for ($cpt = 0;$cpt < $nb_blocs;$cpt ++) {
			$bloc = &$this->liste_objets["pl3_objet_page_bloc"][$cpt];
			if ($bloc != null) {
				if ($bloc->lire_id() != $bloc_id) {
					$bloc->ecrire_id($id_cpt);
					$liste_blocs[] = $bloc;
					$id_cpt += 1;
				}
				else {
					$bloc->detruire();
					unset($bloc);
				}
			}
		}
		$this->liste_objets["pl3_objet_page_bloc"] = $liste_blocs;
		$this->maj_cardinal_et_largeur();
	}
	public function reordonner($tab_ordre) {
		$nouveaux_blocs = array();
		foreach ($tab_ordre as $no_ordre) {
			$index = ((int) $no_ordre) - 1;
			$nouveaux_blocs[] = &$this->liste_objets["pl3_objet_page_bloc"][$index];
		}
		$this->liste_objets["pl3_objet_page_bloc"] = $nouveaux_blocs;
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
		$attr_style = $this->get_xml_attribut(self::NOM_ATTRIBUT_STYLE);
		$xml = $this->ouvrir_xml($niveau, array($attr_style));
		foreach($this->liste_objets["pl3_objet_page_bloc"] as $bloc) {
			$xml .= $bloc->ecrire_xml(1 + $niveau);
		}
		$xml .= $this->fermer_xml($niveau);
		return $xml;
	}
	
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
			$ret .= "<div id=\"grille-contenu-".$this->id."\" class=\"contenu_flex contenu_".$style."\" style=\"\">\n";
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