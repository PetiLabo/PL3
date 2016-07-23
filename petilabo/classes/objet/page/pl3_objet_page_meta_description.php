<?php

class pl3_objet_page_meta_description extends pl3_outil_objet_xml {
	const NOM_FICHE  = "page";
	const NOM_BALISE = "description";
	const TYPE       = self::TYPE_REFERENCE;
	const REFERENCE  = "pl3_objet_texte_texte";

	/* Affichage */
	public function afficher($mode) {
		$ret = "";
		$description = $this->get_valeur();
		if (strlen($valeur_descr) > 0) {
			$ret .= "<meta name=\"description\" content=\"".$description."\" />\n";
		}
		return $ret;
	}
}