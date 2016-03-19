<?php

/**
 * Classe de gestion des fiches page.xml
 */
 
class pl3_fiche_page extends pl3_outil_fiche_xml {
	const NOM_FICHE = "page";
	
	/* Constructeur */
	public function __construct() {
		$this->obligatoire = true;
		$this->declarer_objet("pl3_objet_page_contenu");
		parent::__construct(_CHEMIN_PAGE_COURANTE);
	}

	/* Afficher */
	public function afficher() {
		echo "<div class=\"page\">\n";
		$this->afficher_objets();
		echo "</div>\n";
	}
}