<?php

class pl3_outil_source_site {
	/* Singleton */
	private static $Source_site = null;

	/* Ressources */
	private $site = null;

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

		/* Déclaration du fichier page */
		$this->site = new pl3_fiche_site(_CHEMIN_XML);
	}
	public static function &Get() {
		if (is_null(self::$Source_site)) {
			self::$Source_site = new pl3_outil_source_site();
		}
		return self::$Source_site;
	}

	/* Accesseurs */
	public function &get_site() {return $this->site;}

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
	public function afficher($mode) {
		$this->site->set_mode($mode);
		$html = $this->site->afficher();
		return $html;
	}
}