<?php

/**
 * Classe de gestion des balises meta
 */

class pl3_objet_page_meta extends pl3_outil_objet_xml {
	const NOM_FICHE  = "page";
	const NOM_BALISE = "meta";
	const TYPE       = self::TYPE_COMPOSITE;
	const OBJETS     = array("meta_titre", "meta_description", "meta_theme", "meta_style");

	/* Affichage */
	public function afficher($mode) {
		$ret = $this->afficher_elements($mode);
		return $ret;
	}
}