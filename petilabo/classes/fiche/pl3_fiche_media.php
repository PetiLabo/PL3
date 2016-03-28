<?php

/**
 * Classe de gestion des fiches media.xml
 */
 
class pl3_fiche_media extends pl3_outil_fiche_xml {
	const NOM_FICHE = "media";
	
	/* Constructeur */
	public function __construct($chemin, $id) {
		$this->declarer_objet("pl3_objet_media_image");
		$this->declarer_objet("pl3_objet_media_galerie");
		parent::__construct($chemin, $id);
	}
	
	/* Afficher */
	public function afficher() {
		$ret = $this->afficher_objets();
		return $ret;
	}
}