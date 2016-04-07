<?php

/**
 * Classe de gestion des fiches media.xml
 */
 
class pl3_fiche_media extends pl3_outil_fiche_xml {
	const NOM_FICHE = "media";
	
	/* Constructeur */
	public function __construct(&$source_page, $chemin, $id) {
		$this->declarer_objet("pl3_objet_media_image");
		$this->declarer_objet("pl3_objet_media_galerie");
		parent::__construct($source_page, $chemin, $id);
	}
	
	/* Afficher */
	public function afficher($mode) {
		$ret = $this->afficher_objets($mode);
		return $ret;
	}
}