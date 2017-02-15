<?php

/**
 * Classe de gestion des fiches liens.xml
 */
 
class pl3_fiche_liens extends pl3_outil_fiche_xml {
	const NOM_FICHE = "liens";
	
	/* Constructeur */
	public function __construct($chemin, $id) {
		$this->declarer_objet("pl3_objet_liens_lien");
		$this->declarer_objet("pl3_objet_liens_menu");
		parent::__construct($chemin, $id);
	}
	
	/* Afficher */
	public function afficher($mode) {
		$ret = $this->afficher_objets($mode);
		return $ret;
	}
}