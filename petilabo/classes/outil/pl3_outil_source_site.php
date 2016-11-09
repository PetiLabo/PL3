<?php

class pl3_outil_source_site {
	/* Singleton */
	private static $Source_site = null;

	/* Ressources */
	private $liste_sources = array();
	private $liste_themes = array();
	
	/* Propriétés */
	protected $mode = _MODE_ADMIN_SITE_GENERAL;

	/* Constructeur privé */
	private function __construct() {
		/* Déclaration des textes */
		$liste_textes = new pl3_outil_liste_fiches_xml("texte");
		$liste_textes->ajouter_source(_NOM_SOURCE_GLOBAL, _CHEMIN_XML);
		$this->liste_sources[pl3_fiche_texte::NOM_FICHE] = $liste_textes;

		/* Déclaration des media */
		$liste_medias = new pl3_outil_liste_fiches_xml("media");
		$liste_medias->ajouter_source(_NOM_SOURCE_GLOBAL, _CHEMIN_XML);
		$this->liste_sources[pl3_fiche_media::NOM_FICHE] = $liste_medias;
	}
	public static function &Get() {
		if (is_null(self::$Source_site)) {
			self::$Source_site = new pl3_outil_source_site();
		}
		return self::$Source_site;
	}
	
	/* Accesseurs / mutateurs */
	public function set_mode($mode) {$this->mode = $mode;}
	public function get_mode() {return $this->mode;}

	/* Chargement XML */
	public function charger_xml() {
		foreach ($this->liste_sources as $nom_fiche => $liste_fiches) {
			$liste_fiches->charger_xml();
		}
	}

	/* Enregistrement XML */
	public function enregistrer_xml() {
		foreach ($this->liste_sources as $nom_fiche => $liste_fiches) {
			$liste_fiches->enregistrer_xml();
		}
	}

	/* Affichage */
	public function afficher_head() {
		$ret = "";
		$ret .= "<!doctype html>\n";
		$ret .= "<html xmlns=\"http://www.w3.org/1999/xhtml\" xml:lang=\"fr\" lang=\"fr\" dir=\"ltr\">\n";
		$ret .= "<head>\n";
		$ret .= "<meta http-equiv=\"content-type\" content=\"text/html; charset=UTF-8\" />\n";
		$ret .= "<meta name=\"viewport\" content=\"width=device-width,initial-scale=1\" />\n";
		$ret .= "<meta name=\"generator\" content=\"PL3\" />\n";
		$ret .= "<link rel=\"stylesheet\" type=\"text/css\" href=\"https://maxcdn.bootstrapcdn.com/font-awesome/4.5.0/css/font-awesome.min.css\"/>\n";
		$ret .= "<link rel=\"stylesheet\" type=\"text/css\" href=\""._CHEMIN_CSS."pl3.css\"/>\n";
		$ret .= "<link rel=\"stylesheet\" type=\"text/css\" href=\""._CHEMIN_CSS."pl3_admin.css\"/>\n";
		$ret .= "<script type=\"text/javascript\" src=\"//code.jquery.com/jquery-1.12.0.min.js\"></script>\n";
		$ret .= "</head>\n";
		return $ret;
	}
	public function ouvrir_body() {
		$ret = "";
		$ret .= "<body>\n";
		return $ret;
	}
	public function ecrire_body() {
		$ret = "<br><br><br>";
		if (($this->mode & _MODE_ADMIN_SITE_GENERAL) > 0) {
			$ret .= "<h2>Liste des pages</h2>\n";
			$ret .= "<ul>\n";
			$liste_pages = $this->lire_liste_pages();
			foreach($liste_pages as $page) {
				$ret .= "<li>".$page."</li>\n";
			}
			$ret .= "</ul>\n";
		}
		elseif (($this->mode & _MODE_ADMIN_SITE_THEMES) > 0) {
			$ret .= "<h2>Liste des thèmes</h2>\n";
			$ret .= "<ul>\n";
			$liste_themes = $this->lire_liste_themes();
			foreach($liste_themes as $theme) {
				$ret .= "<li>".$theme."</li>\n";
			}
			$ret .= "</ul>\n";
		}
		elseif (($this->mode & _MODE_ADMIN_SITE_OBJETS) > 0) {
			$ret .= "<h2>Liste des objets</h2>\n";
			$ret .= "<ul>\n";
			$liste_objets = $this->lire_liste_objets();
			foreach($liste_objets as $balise => $icone) {
				$ret .= "<li><span class=\"fa ".$icone."\"></span>&nbsp;".$balise."</li>\n";
			}
			$ret .= "</ul>\n";
		}
		return $ret;
	}
	public function fermer_body() {
		$ret = "";
		$ret .= "<script type=\"text/javascript\" src=\""._CHEMIN_JS."pl3_admin.js\"></script>\n";
		$ret .= "</body>\n";
		$ret .= "</html>\n";
		return $ret;
	}
	
	public function lire_liste_pages() {
		$ret = array();
		$liste = @glob(_CHEMIN_PAGES_XML."*/".(pl3_fiche_page::NOM_FICHE)._SUFFIXE_XML);
		foreach ($liste as $elem_liste) {
			if (is_file($elem_liste)) {
				$nom_dossier = dirname($elem_liste);
				$ret[] = str_replace(_CHEMIN_PAGES_XML, "", $nom_dossier);
			}
		}
		return $ret;
	}
	
	public function lire_liste_themes() {
		$ret = array();
		$liste = @glob(_CHEMIN_THEMES_XML."*/".(pl3_fiche_theme::NOM_FICHE)._SUFFIXE_XML);
		foreach ($liste as $elem_liste) {
			if (is_file($elem_liste)) {
				$nom_dossier = dirname($elem_liste);
				$ret[] = str_replace(_CHEMIN_THEMES_XML, "", $nom_dossier);
			}
		}
		return $ret;
	}
	
	// TODO : Supprimer l'équivalent dans pl3_fiche_page bien sûr ! 
	private function lire_liste_objets() {
		$ret = array();
		$liste = @glob(_CHEMIN_OBJET.pl3_fiche_page::NOM_FICHE."/"._PREFIXE_OBJET.pl3_fiche_page::NOM_FICHE."_*"._SUFFIXE_PHP);
		foreach ($liste as $elem_liste) {
			if (is_file($elem_liste)) {
				$nom_fichier = basename($elem_liste);
				$nom_classe = str_replace(_SUFFIXE_PHP, "", $nom_fichier);
				$nom_constante_balise = $nom_classe."::NOM_BALISE";
				$nom_constante_icone = $nom_classe."::NOM_ICONE";
				$nom_constante_fiche = $nom_classe."::NOM_FICHE";
				if ((@defined($nom_constante_balise)) && (@defined($nom_constante_icone)) && (@defined($nom_constante_fiche)))  {
					$nom_fiche = $nom_classe::NOM_FICHE;
					if (!(strcmp($nom_fiche, pl3_fiche_page::NOM_FICHE))) {
						$nom_balise = $nom_classe::NOM_BALISE;
						$nom_icone = $nom_classe::NOM_ICONE;
						$ret[$nom_balise] = $nom_icone;
					}
				}
			}
		}
		return $ret;
	}
}