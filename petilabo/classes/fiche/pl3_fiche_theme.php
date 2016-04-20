<?php

/**
 * Classe de gestion des fiches theme.xml
 */
 
class pl3_fiche_theme extends pl3_outil_fiche_xml {
	const NOM_FICHE = "theme";
	
	/* Constructeur */
	public function __construct($chemin, $id) {
		$this->declarer_objet("pl3_objet_theme_style_page");
		$this->declarer_objet("pl3_objet_theme_style_contenu");
		$this->declarer_objet("pl3_objet_theme_style_bloc");
		$this->declarer_objet("pl3_objet_theme_style_texte");
		parent::__construct($chemin, $id);
	}

	/* Afficher */
	public function afficher($mode) {
		$ret = $this->afficher_objets($mode);
		return $ret;
	}
}