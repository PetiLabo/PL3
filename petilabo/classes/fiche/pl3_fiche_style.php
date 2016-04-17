<?php

/**
 * Classe de gestion des fiches style.xml
 */
 
class pl3_fiche_style extends pl3_outil_fiche_xml {
	const NOM_FICHE = "style";
	
	/* Constructeur */
	public function __construct(&$source_page, $chemin, $id) {
		$this->declarer_objet("pl3_objet_style_style_page");
		$this->declarer_objet("pl3_objet_style_style_contenu");
		$this->declarer_objet("pl3_objet_style_style_bloc");
		parent::__construct($source_page, $chemin, $id);
	}

	/* Afficher */
	public function afficher($mode) {
		$ret = $this->afficher_objets($mode);
		return $ret;
	}
}