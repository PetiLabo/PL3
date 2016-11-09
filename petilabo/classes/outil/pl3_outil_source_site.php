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
		$ret = "";
		return $ret;
	}
	public function fermer_body() {
		$ret = "";
		$ret .= "<script type=\"text/javascript\" src=\""._CHEMIN_JS."pl3_admin.js\"></script>\n";
		$ret .= "</body>\n";
		$ret .= "</html>\n";
		return $ret;
	}
}