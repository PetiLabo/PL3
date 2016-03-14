<?php

/**
 * Classe de gestion des fiches media.xml
 */
 
class pl3_fiche_media extends pl3_outil_fiche_xml {
	const NOM_FICHE = "media";
	
	/* Constructeur */
	public function __construct() {
		$this->ajouter_objet("pl3_objet_media_image");
		$this->ajouter_objet("pl3_objet_media_galerie");
		parent::__construct();
	}
	
	/* Afficher */
	public function afficher() {
		$this->afficher_objets();
	}
}