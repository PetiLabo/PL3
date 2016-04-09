<?php

/**
 * Classe de gestion des fiches texte.xml
 */
 
class pl3_fiche_texte extends pl3_outil_fiche_xml {
	const NOM_FICHE = "texte";
	
	/* Constructeur */
	public function __construct(&$source_page, $chemin, $id) {
		$this->declarer_objet("pl3_objet_texte_texte");
		parent::__construct($source_page, $chemin, $id);
	}
	
	/* Afficher */
	public function afficher($mode) {
		$ret = $this->afficher_objets($mode);
		return $ret;
	}
}