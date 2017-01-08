<?php

/**
 * Classe de gestion des fiches page.xml
 */
 
class pl3_fiche_page extends pl3_outil_fiche_xml {
	const NOM_FICHE = "page";
	
	/* Propriétés */
	private $nom_theme = _NOM_THEME_DEFAUT;
	private $nom_style = _NOM_STYLE_DEFAUT;
	private $liste_objets_avec_icone = array();
	
	/* Constructeur */
	public function __construct($chemin) {
		$this->obligatoire = true;
		$this->declarer_objet("pl3_objet_page_meta");
		$this->declarer_objet("pl3_objet_page_contenu");
		$this->lire_objets_avec_icone();
		parent::__construct($chemin, 1);
	}
	
	/* Chargement */
	public function charger_xml() {
		$ret = parent::charger_xml();
		if ($ret) {
			$meta = $this->get_meta();
			if ($meta != null) {
				$meta_theme = $meta->get_valeur_theme();
				if (strlen($meta_theme) > 0) {$this->nom_theme = $meta_theme;}
				$meta_style = $meta->get_valeur_style();
				if (strlen($meta_style) > 0) {$this->nom_style = $meta_style;}
			}
		}
		return $ret;
	}

	/* Accesseurs */
	public function get_nom_theme() {return $this->nom_theme;}
	public function get_nom_style() {return $this->nom_style;}
	public function get_liste_objets_avec_icone() {return $this->liste_objets_avec_icone;}
	public function lire_nb_contenus() {return count($this->liste_objets["pl3_objet_page_contenu"]);}

	/* Mutateurs */
	public function ajouter_contenu(&$contenu) {
		$contenu->maj_cardinal_et_largeur();
		$this->liste_objets["pl3_objet_page_contenu"][] = $contenu;
	}
	public function retirer_contenu($contenu_id) {
		$liste_contenus = array();
		$nb_contenus = count($this->liste_objets["pl3_objet_page_contenu"]);
		$id_cpt = 1;
		for ($cpt = 0;$cpt < $nb_contenus;$cpt ++) {
			$contenu = &$this->liste_objets["pl3_objet_page_contenu"][$cpt];
			if ($contenu != null) {
				if ($contenu->lire_id() != $contenu_id) {
					$contenu->ecrire_id($id_cpt);
					$liste_contenus[] = $contenu;
					$id_cpt += 1;
				}
				else {
					$contenu->detruire();
					unset($contenu);
				}
			}
		}
		$this->liste_objets["pl3_objet_page_contenu"] = $liste_contenus;
	}
	public function reordonner($tab_ordre) {
		$nouveaux_contenus = array();
		foreach ($tab_ordre as $no_ordre) {
			$index = ((int) $no_ordre) - 1;
			$nouveaux_contenus[] = &$this->liste_objets["pl3_objet_page_contenu"][$index];
		}
		$this->liste_objets["pl3_objet_page_contenu"] = $nouveaux_contenus;
	}

	/* Afficher */
	public function afficher() {
		$ret = "";
		$ret .= $this->afficher_head();
		$ret .= $this->afficher_body();
		return $ret;
	}
	
	public function afficher_head() {
		$ret = "";
		$ret .= $this->ouvrir_head();
		$ret .= $this->ecrire_head();
		$ret .= $this->fermer_head();
		return $ret;
	}
	
	public function afficher_body() {
		$ret = "";
		$ret .= $this->ouvrir_body();
		$ret .= $this->ecrire_body();
		$ret .= $this->fermer_body();
		return $ret;
	}	
	
	public function ouvrir_head() {
		$ret = "";
		$ret .= "<!doctype html>\n";
		$ret .= "<html xmlns=\"http://www.w3.org/1999/xhtml\" xml:lang=\"fr\" lang=\"fr\" dir=\"ltr\">\n";
		$ret .= "<head>\n";
		$ret .= "<meta http-equiv=\"content-type\" content=\"text/html; charset=UTF-8\" />\n";
		$ret .= "<meta name=\"viewport\" content=\"width=device-width,initial-scale=1\" />\n";
		$ret .= "<meta name=\"generator\" content=\"PL3\" />\n";
		return $ret;
	}
	
	public function ecrire_head() {
		$ret = $this->afficher_objets($this->mode, "pl3_objet_page_meta");
		return $ret;
	}
	
	public function fermer_head() {
		$ret = "";
		/* Partie CSS */
		$ret .= $this->declarer_css("https://use.fontawesome.com/4624d65caf.css");
		$ret .= $this->declarer_css(_CHEMIN_CSS."pl3.css");
		$ret .= $this->declarer_css(_CHEMIN_CSS."pl3_objets.css");
		$ret .= $this->declarer_css(_CHEMIN_CSS."pl3_admin.css", _MODE_ADMIN);
		$ret .= $this->declarer_css(_CHEMIN_CSS."pl3_admin_media.css", _MODE_ADMIN_MEDIA);
		$ret .= $this->declarer_css(_CHEMIN_CSS."pl3_admin_grille.css", _MODE_ADMIN_GRILLE);
		$ret .= $this->declarer_css(_CHEMIN_CSS."pl3_admin_objets.css", _MODE_ADMIN_OBJETS);
		$ret .= $this->declarer_css(_CHEMIN_TIERS."featherlight/css/featherlight.min.css", _MODE_ADMIN_MEDIA);
		$ret .= $this->declarer_css(_CHEMIN_TIERS."trumbo/ui/trumbowyg.min.css", _MODE_ADMIN_OBJETS);
		$ret .= $this->declarer_css(_CHEMIN_TIERS."trumbo/plugins/colors/ui/trumbowyg.colors.min.css", _MODE_ADMIN_OBJETS);
		$theme = $this->get_nom_theme();
		$ret .= $this->declarer_css(_CHEMIN_RESSOURCES_CSS."style_".$theme.".css");
		
		/* Partie JS */
		$ret .= $this->declarer_js("https://code.jquery.com/jquery-1.12.4.min.js", _MODE_TOUS, array("integrity" => "sha256-ZosEbRLbNQzLpnKIkEdrPv7lOy9C27hHQ+Xp8a4MxAQ=", "crossorigin" => "anonymous"));
		$ret .= $this->declarer_js("https://code.jquery.com/ui/1.12.1/jquery-ui.min.js", _MODE_ADMIN_MEDIA|_MODE_ADMIN_GRILLE|_MODE_ADMIN_OBJETS, array("integrity" => "sha256-VazP97ZCwtekAsvgPBSUwPFKdrwD3unUfSGVYrahUqU=", "crossorigin" => "anonymous"));
		$ret .= "</head>\n";
		return $ret;
	}
	
	public function ouvrir_body() {
		$ret = "";
		$ret .= "<body>\n";
		return $ret;
	}
	
	public function ecrire_body() {
		if (($this->mode & _MODE_ADMIN_PAGE) > 0) {
			$contenu_mode = "<h2>Paramètres de la page <strong><em>"._PAGE_COURANTE."</em></strong></h2>\n";
			$contenu_mode .= "<br/>\n<p class=\"texte\"><label for=\"meta_titre\">Titre</label></p>\n";
			$contenu_mode .= "<p class=\"texte\"><input type=\"text\" id=\"meta_titre\" name=\"meta_titre\" /></p>\n";
			$contenu_mode .= "<br/>\n<p class=\"texte\"><label for=\"meta_description\">Description</label></p>\n";
			$contenu_mode .= "<p class=\"texte\"><input type=\"text\" id=\"meta_description\" name=\"meta_description\" /></p>\n";
			$contenu_mode .= "<br/>\n<p class=\"texte\"><label for=\"meta_theme\">Thème</label></p>\n";
			$contenu_mode .= "<p class=\"texte\"><input type=\"text\" id=\"meta_theme\" name=\"meta_theme\" /></p>\n";
			/*
			$meta = $this->get_meta();
			$editeur = new pl3_admin_editeur_objet($meta, "editeur_type_meta", "meta-".$meta->lire_id());
			$contenu_mode .= $editeur->editer();
			*/
			$classe_mode = "page_parametres";
		}
		else if (($this->mode & _MODE_ADMIN_XML) > 0) {
			$xml = $this->ecrire_xml();
			$html = htmlspecialchars($xml, ENT_QUOTES, "UTF-8");
			$contenu_mode = nl2br(str_replace(" ","&nbsp;", $html));
			$classe_mode = "page_xml";
		}
		else {
			$contenu_mode = $this->afficher_objets($this->mode, "pl3_objet_page_contenu");
			if ($this->mode & _MODE_ADMIN_GRILLE) {
				$contenu_mode .= "<div class=\"contenu_ajout contenu_defaut\">";
				$contenu_mode .= "<p class=\"contenu_poignee_ajout\">";
				$contenu_mode .= "<a class=\"fa fa-bars\" href=\"#\" title=\"Ajouter un contenu\"></a>";
				$contenu_mode .= "</p></div>\n";
			}
			$classe_mode = "page";
		}
		$classe = $classe_mode.((($this->mode & _MODE_ADMIN) > 0)?" page_mode_admin":"");
		$ret = "<div class=\"".$classe."\" name=\""._PAGE_COURANTE."\">".$contenu_mode."</div>\n";
		return $ret;
	}
	
	public function fermer_body() {
		$ret = "";
		/* TEMPORAIRE : Ajout d'un lien pour switcher le mode */
		if (($this->mode & _MODE_ADMIN) > 0) {
			$ret .= "<p style=\"margin-top:20px;\"><a href=\"../"._PAGE_COURANTE._SUFFIXE_PHP."\">Mode normal</a></p>\n";
		}
		else {
			$ret .= "<p style=\"margin-top:20px;\"><a href=\"admin/"._PAGE_COURANTE._SUFFIXE_PHP."\">Mode admin</a></p>\n";
		}

		/* Appel des outils javascript */
		$ret .= $this->declarer_js(_CHEMIN_JS."pl3_admin.js", _MODE_ADMIN);
		$ret .= $this->declarer_js(_CHEMIN_JS."pl3_admin_media.js", _MODE_ADMIN_MEDIA);
		$ret .= $this->declarer_js(_CHEMIN_JS."pl3_admin_grille.js", _MODE_ADMIN_GRILLE);
		$ret .= $this->declarer_js(_CHEMIN_JS."pl3_admin_objets.js", _MODE_ADMIN_OBJETS);
		$ret .= $this->declarer_js(_CHEMIN_TIERS."featherlight/js/featherlight.min.js", _MODE_ADMIN_MEDIA);
		$ret .= $this->declarer_js(_CHEMIN_TIERS."trumbo/trumbowyg.min.js", _MODE_ADMIN_OBJETS);
		$ret .= $this->declarer_js(_CHEMIN_TIERS."trumbo/langs/fr.min.js", _MODE_ADMIN_OBJETS);
		$ret .= $this->declarer_js(_CHEMIN_TIERS."trumbo/plugins/colors/trumbowyg.colors.min.js", _MODE_ADMIN_OBJETS);
		$ret .= $this->declarer_js(_CHEMIN_TIERS."trumbo/plugins/editlink/trumbowyg.editlink.min.js", _MODE_ADMIN_OBJETS);
		$ret .= "</body>\n";
		$ret .= "</html>\n";
		return $ret;
	}
	
	private function get_meta() {
		$ret = null;
		$liste_meta = $this->liste_objets["pl3_objet_page_meta"];
		if (count($liste_meta) > 0) {$ret = $liste_meta[0];}
		return $ret;
	}
	
	private function lire_objets_avec_icone() {
		$liste = @glob(_CHEMIN_OBJET.self::NOM_FICHE."/"._PREFIXE_OBJET.self::NOM_FICHE."_*"._SUFFIXE_PHP);
		foreach ($liste as $elem_liste) {
			if (is_file($elem_liste)) {
				$nom_fichier = basename($elem_liste);
				$nom_classe = str_replace(_SUFFIXE_PHP, "", $nom_fichier);
				$nom_constante_balise = $nom_classe."::NOM_BALISE";
				$nom_constante_icone = $nom_classe."::NOM_ICONE";
				$nom_constante_fiche = $nom_classe."::NOM_FICHE";
				if ((@defined($nom_constante_balise)) && (@defined($nom_constante_icone)) && (@defined($nom_constante_fiche)))  {
					$nom_fiche = $nom_classe::NOM_FICHE;
					if (!(strcmp($nom_fiche, "page"))) {
						$nom_balise = $nom_classe::NOM_BALISE;
						$nom_icone = $nom_classe::NOM_ICONE;
						$this->liste_objets_avec_icone[$nom_balise] = $nom_icone;
					}
				}
			}
		}
	}

	private function declarer_css($fichier_css, $mode = _MODE_TOUS) {
		$ret = "";
		if (($mode == -1) || (($mode & $this->mode) > 0)) {
			$ret .= "<link rel=\"stylesheet\" type=\"text/css\" href=\"".$fichier_css."\"/>\n";
		}
		return $ret;
	}
	private function declarer_js($fichier_js, $mode = _MODE_TOUS, $liste_attr = array()) {
		$ret = "";
		if (($mode == -1) || (($mode & $this->mode) > 0)) {
			$ret .= "<script type=\"text/javascript\" src=\"".$fichier_js."\"";
			foreach($liste_attr as $attr => $valeur) {
				$ret .= " ".$attr."=\"".$valeur."\"";
			}
			$ret .= "></script>\n";
		}
		return $ret;
	}
}