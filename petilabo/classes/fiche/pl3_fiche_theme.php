<?php

/**
 * Classe de gestion des fiches theme.xml
 */
 
class pl3_fiche_theme extends pl3_outil_fiche_xml {
	const NOM_FICHE = "theme";
	const NOM_FICHIER_VERSION = "theme.ver";
	
	/* Propriétés */
	private $auteur = null;
	private $forge = null;
	private $documentation = null;
	private $telechargement = null;

	/* Constructeur */
	public function __construct($chemin, $id) {
		$this->declarer_objet("pl3_objet_theme_meta");
		$this->declarer_objet("pl3_objet_theme_taille_image");
		$this->declarer_objet("pl3_objet_theme_style_page");
		$this->declarer_objet("pl3_objet_theme_style_contenu");
		$this->declarer_objet("pl3_objet_theme_style_bloc");
		$this->declarer_objet("pl3_objet_theme_style_texte");
		$this->declarer_objet("pl3_objet_theme_style_survol");
		parent::__construct($chemin, $id);
	}

	/* Accesseurs */
	public function get_auteur() {return $this->auteur;}
	public function get_forge() {return $this->forge;}
	public function get_documentation() {return $this->documentation;}
	public function get_telechargement() {return $this->telechargement;}

	/* Afficher */
	public function afficher($mode) {
		$ret = $this->afficher_objets($mode);
		return $ret;
	}
	
	public function charger_xml() {
		$ret = parent::charger_xml();
		if ($ret) {
			$meta = $this->get_meta();
			if ($meta != null) {
				$meta_auteur = $meta->get_valeur_auteur();
				if (strlen($meta_auteur) > 0) {$this->auteur = $meta_auteur;}
				$meta_forge = $meta->get_valeur_forge();
				if (strlen($meta_forge) > 0) {$this->forge = $meta_forge;}
				$meta_documentation = $meta->get_valeur_documentation();
				if (strlen($meta_documentation) > 0) {$this->documentation = $meta_documentation;}
				$meta_telechargement = $meta->get_valeur_telechargement();
				if (strlen($meta_telechargement) > 0) {$this->telechargement = $meta_telechargement;}
			}
		}
		return $ret;
	}

	private function get_meta() {
		$ret = null;
		$liste_meta = $this->liste_objets["pl3_objet_theme_meta"];
		if (count($liste_meta) > 0) {$ret = $liste_meta[0];}
		return $ret;
	}
}