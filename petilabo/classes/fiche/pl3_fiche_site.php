<?php

/**
 * Classe de gestion des fiches site.xml
 */
 
class pl3_fiche_site extends pl3_outil_fiche_xml {
	const NOM_FICHE = "site";
	
	/* Constructeur */
	public function __construct($chemin) {
		$this->obligatoire = true;
		parent::__construct($chemin, 1);
	}
	
	/* Mutateurs */
	public function ajouter_page($nom_page) {
		$dossier = _CHEMIN_PAGES_XML.$nom_page."/";
		$ret = @mkdir($dossier);
		if ($ret) {
			$xml = $this->ouvrir_fiche_xml().$this->fermer_fiche_xml();
			$page = new pl3_fiche_page($dossier);
			$page->enregistrer_xml();
			if ($ret) {
				$fichier_touch = $dossier."/"._PAGE_TOUCH._SUFFIXE_XML;
				@touch($fichier_touch);
			}
		}
		return $ret;
	}
	public function supprimer_page($nom_page) {
		$ret = false;
		if (strcmp($nom_page, "index")) {
			$dossier = _CHEMIN_PAGES_XML.$nom_page."/";
			$liste = @glob($dossier."*");
			foreach ($liste as $elem_liste) {
				@unlink($elem_liste);
			}
			$ret = @rmdir($dossier);
		}
		return $ret;
	}

	/* Chargement */
	public function charger_xml() {
		parent::charger_xml();
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
		$ret .= "<!doctype html>\n";
		$ret .= "<html xmlns=\"http://www.w3.org/1999/xhtml\" xml:lang=\"fr\" lang=\"fr\" dir=\"ltr\">\n";
		$ret .= "<head>\n";
		$ret .= "<meta http-equiv=\"content-type\" content=\"text/html; charset=UTF-8\" />\n";
		$ret .= "<meta name=\"viewport\" content=\"width=device-width,initial-scale=1\" />\n";
		
		/* Partie CSS */
		$ret .= $this->declarer_css("https://use.fontawesome.com/4624d65caf.css");
		$ret .= $this->declarer_css(_CHEMIN_CSS."pl3.css");
		$ret .= $this->declarer_css(_CHEMIN_CSS."pl3_admin.css");
		$ret .= $this->declarer_css(_CHEMIN_CSS."pl3_admin_site.css", _MODE_ADMIN_SITE_GENERAL);
		$ret .= $this->declarer_css(_CHEMIN_CSS."pl3_admin_theme.css", _MODE_ADMIN_SITE_THEMES);
		$ret .= $this->declarer_css(_CHEMIN_CSS."pl3_admin_liens.css", _MODE_ADMIN_SITE_LIENS);
		
		/* Partie JS */
		$ret .= $this->declarer_js("//code.jquery.com/jquery-1.12.0.min.js");
		$ret .= "</head>\n";
		return $ret;
	}

	public function afficher_body() {
		$ret = "";
		$ret .= $this->ouvrir_body();
		$ret .= $this->ecrire_body();
		$ret .= $this->fermer_body();
		return $ret;
	}	
	
	public function ouvrir_body() {
		$ret = "";
		$ret .= "<body>\n";
		return $ret;
	}

	public function ecrire_body() {
		$contenu_mode = "";
		$classe_mode = "page_parametres";
		if (($this->mode & _MODE_ADMIN_SITE_GENERAL) > 0) {
			$contenu_mode .= $this->ecrire_body_general();
		}
		elseif (($this->mode & _MODE_ADMIN_SITE_THEMES) > 0) {
			$contenu_mode .= $this->ecrire_body_themes();
		}
		elseif (($this->mode & _MODE_ADMIN_SITE_LIENS) > 0) {
			$contenu_mode .= $this->ecrire_body_liens();
		}
		$classe = $classe_mode." page_mode_admin";
		$ret = "<div class=\"".$classe."\" name=\"site\">".$contenu_mode."</div>\n";
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
		$ret .= $this->declarer_js(_CHEMIN_JS."pl3_admin.js");
		$ret .= $this->declarer_js(_CHEMIN_JS."pl3_admin_site.js", _MODE_ADMIN_SITE_GENERAL);
		$ret .= $this->declarer_js(_CHEMIN_JS."pl3_admin_theme.js", _MODE_ADMIN_SITE_THEMES);
		$ret .= $this->declarer_js(_CHEMIN_JS."pl3_admin_liens.js", _MODE_ADMIN_SITE_LIENS);
		$ret .= "</body>\n";
		$ret .= "</html>\n";
		return $ret;
	}
	
	public function ecrire_vignette_page($nom, $datec, $datem) {
		$ret = "";
		$classe = strcmp($nom, _PAGE_COURANTE)?"vignette_page":"vignette_page_active";
		$ret .= "<div class=\"".$classe."\">";
		$ret .= "<h2>".$nom."</h2>";
		$ret .= "<div class=\"vignette_page_info\">";
		$ret .= "<p>Création ".(($datec > 0)?("le ".date("d/m/Y à H:i",$datec)):"à une date inconnue")."</p>";
		$ret .= "<p>Modification ".(($datem > 0)?("le ".date("d/m/Y à H:i",$datem)):"à une date inconnue")."</p>";
		$ret .= "<hr><p class=\"vignette_icones\">";
		$ret .= "<a id=\"admin-mode-"._MODE_ADMIN_PAGE."\" class=\"vignette_icone_admin\" href=\"../admin/".$nom._SUFFIXE_PHP."\" title=\"Administrer la page ".$nom."\"><span class=\"fa fa-wrench\"></span></a>";
		if (strcmp($nom, "index")) {
			$ret .= "<a id=\"supprimer-".$nom."\" class=\"vignette_icone_supprimer\" href=\"#\" title=\"Supprimer la page ".$nom."\" target=\"_blank\"><span class=\"fa fa-trash\"></span></a>";
		}
		$ret .= "</p></div></div>";
		return $ret;
	}
	
	public function ecrire_liste_vignettes_page() {
		$ret = "";
		$liste_pages = $this->lire_liste_pages();
		foreach($liste_pages as $page) {
			$ret .= $this->ecrire_vignette_page($page["nom"], $page["datec"], $page["datem"]);
		}
		return $ret;
	}
	
	private function ecrire_body_general() {
		$ret = "";
		$ret .= "<h1>Liste des pages</h1>\n";
		$ret .= "<div id=\"liste-pages\" class=\"container_vignettes_page\">\n";
		$ret .= $this->ecrire_liste_vignettes_page();
		$ret .= "</div>\n";

		/* Création d'une nouvelle page */
		$ret .= "<div id=\"nouvelle-page\" class=\"container_vignettes_page\">\n";
		$ret .= "<div class=\"vignette_page_nouvelle\">";
		$ret .= "<h2>Création d'une nouvelle page</h2>";
		$ret .= "<div class=\"vignette_page_info\">";
		$ret .= "<form class=\"formulaire_nouvelle_page\" method=\"post\">";
		$ret .= "<p><label for=\"id-nouvelle-page\">Nom&nbsp;:</label><input id=\"id-nouvelle-page\" type=\"text\" name=\"nom-nouvelle-page\"/>";
		$ret .= "<input id=\"id-nom-page-courante\" type=\"hidden\" name=\"nom-page-courante\" value=\""._PAGE_COURANTE."\">"; 
		$ret .= "<input type=\"submit\" value=\" Créer la page \"></p>"; 
		$ret .= "</form></div></div></div>";

		return $ret;
	}

	public function ecrire_vignette_theme($nom) {
		$ret = "";
		$maj_version = false;

		$ret .= "<div class=\"vignette_theme\">";
		$ret .= "<h2>".$nom."</h2>";
		$src = _CHEMIN_RESSOURCES_XML."themes/".$nom."/theme.jpg";
		$ret .= "<img src=\"".$src."\" width=\"400\" />";
		$ret .= "<div class=\"vignette_theme_info\">";
		$version_locale = trim($this->lire_version_theme_local($nom));
		$ret .= "<p>Version installée&nbsp;: ".((strlen($version_locale) == 0)?"inconnue":$version_locale)."</p>";

		/* Chargement des méta du thème */
		$chemin = _CHEMIN_THEMES_XML.$nom."/";
		$theme = new pl3_fiche_theme($chemin, 1);
		$chargement = $theme->charger_xml();
		if ($chargement) {
			$auteur = $theme->get_auteur();
			$forge = $theme->get_forge();
			$documentation = $theme->get_documentation();
			$telechargement = $theme->get_telechargement();
			$version_distante = trim($this->lire_version_theme_distant($telechargement));
			if (strlen($version_distante) > 0) {
				if (strlen($version_locale) > 0) {
					if (strcmp($version_locale, $version_distante)) {
						$ret .= "<p>Nouvelle version ".$version_distante." disponible&nbsp;!</p>";
						$maj_version = true;
					}
					else {
						$ret .= "<p>La version du thème est à jour.</p>";
					}
				}
				else {
					$ret .= "<p>Dernière version disponible :  ".$version_distante."</p>";
					$maj_version = true;
				}
			}
			else {
				$ret .= "<p>Serveur de mise à jour indisponible.</p>";
			}
			if (strlen($forge) > 0) {
				if (strlen($auteur) > 0) {
					$ret .= "<p>Auteur&nbsp;: ".$auteur."</p>";
				}
				$ret .= "<p>Site web&nbsp;: ";
				if (strlen($documentation) > 0) {
					$ret .= "<a href=\"".$documentation."\" title=\"Ouvrir le site dans un nouvel onglet\" target=\"_blank\">".$forge."</a>";
				}
				else {
					$ret .= $forge;
				}
				$ret .= "</p>";
				if (strlen($auteur) == 0) {$ret .= "<p>&nbsp;</p>";}
			}
			else {
				if (strlen($auteur) > 0) {
					$ret .= "<p>Auteur&nbsp;: ";
					if (strlen($documentation) > 0) {
						$ret .= "<a href=\"".$documentation."\" title=\"Ouvrir le site dans un nouvel onglet\" target=\"_blank\">".$auteur."</a>";
					}
					else {
						$ret .= $auteur;
					}
					$ret .= "</p>";
				}
				else {
					$ret .= "<p>&nbsp;</p>";
				}
				$ret .= "<p>&nbsp;</p>";
			}
		}
		else {
			$ret .= "<p>Erreur lors du chargement du thème</p>";
		}
		$ret .= "<hr><p class=\"vignette_icones\">";
		if ($maj_version) {
			$ret .= "<a id=\"maj-".$nom."\" class=\"vignette_icone_maj\" title=\"Télécharger la version ".$version_distante."\" href=\"#\"><span class=\"fa fa-download\"></span></a>";
		}
		$ret .= "<a id=\"maj-".$nom."\" class=\"vignette_icone_supprimer\" title=\"Désinstaller le thème ".$nom."\" href=\"#\"><span class=\"fa fa-trash\"></span></a>";
		$ret .= "</p></div></div>";
		return $ret;
	}

	public function ecrire_liste_vignettes_theme() {
		$ret = "";
		$liste_themes = $this->lire_liste_themes();
		foreach($liste_themes as $theme) {
			$ret .= $this->ecrire_vignette_theme($theme);
		}
		return $ret;
	}

	private function ecrire_body_themes() {
		$ret = "";
		$ret .= "<h1>Liste des thèmes</h1>\n";
		$ret .= "<div id=\"liste-themes\" class=\"container_vignettes_theme\">\n";
		$ret .= $this->ecrire_liste_vignettes_theme();
		$ret .= "</div>\n";

		/* Téléchargement d'un nouveau thème */
		$ret .= "<div id=\"nouveau-theme\" class=\"container_vignettes_theme\">\n";
		$ret .= "<div class=\"vignette_theme_nouveau\">";
		$ret .= "<h2>Installation d'un nouveau thème</h2>";
		$ret .= "<div class=\"vignette_theme_info\">";
		$ret .= "<form class=\"formulaire_nouveau_theme\" method=\"post\">";
		$ret .= "<p><input id=\"id-nouveau-theme\" type=\"file\" accept=\".zip\" name=\"fichier-nouveau-theme\"/>";
		$ret .= "<input id=\"id-nom-page-courante\" type=\"hidden\" name=\"nom-page-courante\" value=\""._PAGE_COURANTE."\">"; 
		$ret .= "<input type=\"submit\" value=\" Installer \"></p>"; 
		$ret .= "</form></div></div></div>";

		return $ret;
	}
	
	private function ecrire_body_liens() {
		$ret = "";
		$ret .= "<h1>Liste des liens</h1>\n";
		$source_site = pl3_outil_source_site::Get();
		$source_liens = $source_site->get_liens();
		$liste_liens = is_null($source_liens)?array():$source_liens->get_liste_objets("pl3_objet_liens_lien");
		$liste_liens_internes = array();
		$liste_liens_externes = array();
		foreach ($liste_liens as $lien) {
			$nom = $lien->get_attribut_nom();
			$externe = (!(strcmp($lien->get_valeur_externe(), pl3_objet_liens_lien::VALEUR_BOOLEEN_VRAI)));
			if ($externe) {$liste_liens_externes[$nom] = $lien;}
			else {$liste_liens_internes[$nom] = $lien;}
		}
			/*
			$nom_ancre = $lien->get_valeur_ancre();
			if (strlen($nom_ancre) > 0) {
				$texte_ancre = $source_site->chercher_liste_textes_par_nom(pl3_objet_texte_texte::NOM_BALISE, $nom_ancre);
				if ($texte_ancre != null) {$nom_ancre = $texte_ancre->get_valeur();}
			}
			$ouvre_a = "<a ".$lien->afficher($this->mode).">";
			$ferme_a = "</a>\n";
			$html_lien = "<strong>".$nom."</strong>&nbsp;: ".$ouvre_a.$nom_ancre.$ferme_a."</a>\n";
			*/
		$ret .= "<div class=\"container_categorie_liens\">\n";
		$ret .= "<h2>Liens internes</h2>\n";
		$ret .= "<div id=\"liste-liens-internes\" class=\"container_vignettes_liens\">\n";
		foreach ($liste_liens_internes as $nom => $lien) {
			$ret .= "<div class=\"vignette_lien_interne\">";
			$ret .= "<a id=\"lien-interne-".$lien->lire_id()."\" class=\"fa fa-link icone_vignette_lien_interne\" href=\"\"></a>";
			$ret .= "<p class=\"vignette_legende_lien\">".$nom."</p>";
			$ret .= "</div>";
		}
		$ret .= "<div class=\"vignette_lien_interne\">";
		$ret .= "<a href=\"#\" class=\"fa fa-plus-circle icone_lien_interne_plus\" title=\"Ajouter un lien interne\"></a>";
		$ret .= "</div>";
		$ret .= "</div></div>\n";
		$ret .= "<div class=\"container_categorie_liens\">\n";
		$ret .= "<h2>Liens externes</h2>\n";
		$ret .= "<div id=\"liste-liens-externes\" class=\"container_vignettes_liens\">\n";
		foreach ($liste_liens_externes as $nom => $lien) {
			$ret .= "<div class=\"vignette_lien_externe\">";
			$ret .= "<a id=\"lien-externe-".$lien->lire_id()."\" class=\"fa fa-external-link icone_vignette_lien_externe\" href=\"\"></a>";
			$ret .= "<p class=\"vignette_legende_lien\">".$nom."</p>";
			$ret .= "</div>";
		}
		$ret .= "<div class=\"vignette_lien_externe\">";
		$ret .= "<a href=\"#\" class=\"fa fa-plus-circle icone_lien_externe_plus\" title=\"Ajouter un lien externe\"></a>";
		$ret .= "</div>";
		$ret .= "</div></div>\n";
		$liste_menus = is_null($source_liens)?array():$source_liens->get_liste_objets("pl3_objet_liens_menu");
		$ret .= "<div class=\"container_categorie_liens\">\n";
		$ret .= "<h2>Menus</h2>\n";
		$ret .= "<div id=\"liste-menus\" class=\"container_vignettes_liens\">\n";
		foreach ($liste_menus as $menu) {
			$nom = $menu->get_attribut_nom();
			$ret .= "<div class=\"vignette_menu\">";
			$ret .= "<a id=\"menu-".$menu->lire_id()."\" class=\"fa fa-server icone_vignette_menu\" href=\"\"></a>";
			$ret .= "<p class=\"vignette_legende_menu\">".$nom."</p>";
			$ret .= "</div>";
		}
		$ret .= "<div class=\"vignette_menu\">";
		$ret .= "<a href=\"#\" class=\"fa fa-plus-circle icone_menu_plus\" title=\"Ajouter un menu\"></a>";
		$ret .= "</div>";
		$ret .= "</div></div>\n";
		return $ret;
	}
	
	public function lire_liste_pages() {
		$ret = array();
		$liste = @glob(_CHEMIN_PAGES_XML."*/".(pl3_fiche_page::NOM_FICHE)._SUFFIXE_XML);
		foreach ($liste as $elem_liste) {
			if (is_file($elem_liste)) {
				$nom_dossier = dirname($elem_liste);
				$datem = @filemtime($elem_liste);
				$datec = @filemtime($nom_dossier."/"._PAGE_TOUCH._SUFFIXE_XML);
				$ret[] = array("nom" => str_replace(_CHEMIN_PAGES_XML, "", $nom_dossier), "datec" => $datec, "datem" => $datem);
			}
		}
		return $ret;
	}
	
	private function lire_liste_themes() {
		$ret = array();
		$liste = @glob(_CHEMIN_THEMES_XML."*/".(pl3_fiche_theme::NOM_FICHE)._SUFFIXE_XML);
		foreach ($liste as $elem_liste) {
			if (is_file($elem_liste)) {
				$nom_dossier = @dirname($elem_liste);
				$ret[] = str_replace(_CHEMIN_THEMES_XML, "", $nom_dossier);
			}
		}
		return $ret;
	}
	
	private function lire_version_theme_local($nom) {
		$ret = "";
		$chemin = _CHEMIN_THEMES_XML.$nom."/".(pl3_fiche_theme::NOM_FICHIER_VERSION);
		$fichier = @fopen($chemin, "r");
		if ($fichier) {
			$ret = @fgets($fichier);
			@fclose($fichier);
		}
		return $ret;
	}
	
	private function lire_version_theme_distant($lien) {
		$ret = null;
		$url = $lien."/".(pl3_fiche_theme::NOM_FICHIER_VERSION);
		$ch = curl_init();
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
		curl_setopt($ch, CURLOPT_URL, $url);
		$contenu = @curl_exec($ch);
		$code = @curl_getinfo($ch, CURLINFO_HTTP_CODE);
		// On ne traite que le code retour 200
		if ($code == 200) {$ret = strtok($contenu, "\n");}
		curl_close($ch);
		return $ret;
	}

	private function lire_liste_liens() {
		$ret = array();
		return $ret;
	}

	private function declarer_css($fichier_css, $mode = -1) {
		$ret = "";
		if (($mode == -1) || (($mode & $this->mode) > 0)) {
			$ret .= "<link rel=\"stylesheet\" type=\"text/css\" href=\"".$fichier_css."\"/>\n";
		}
		return $ret;
	}
	private function declarer_js($fichier_js, $mode = -1) {
		$ret = "";
		if (($mode == -1) || (($mode & $this->mode) > 0)) {
			$ret .= "<script type=\"text/javascript\" src=\"".$fichier_js."\"></script>\n";
		}
		return $ret;
	}
}