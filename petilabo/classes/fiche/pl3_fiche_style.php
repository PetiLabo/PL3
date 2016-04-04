<?php

/**
 * Classe de gestion des fiches style.xml
 */
 
class pl3_fiche_style extends pl3_outil_fiche_xml {
	const NOM_FICHE = "style";
	
	/* Constructeur */
	public function __construct($chemin, $id) {
		$this->declarer_objet("pl3_objet_style_style_puce");
		parent::__construct($chemin, $id);
	}
	
	/* Afficher */
	public function afficher($mode) {
		$ret = $this->afficher_objets($mode);
		return $ret;
	}
}