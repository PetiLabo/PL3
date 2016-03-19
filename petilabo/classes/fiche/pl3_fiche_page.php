<?php

/**
 * Classe de gestion des fiches page.xml
 */
 
class pl3_fiche_page extends pl3_outil_fiche_xml {
	const NOM_FICHE = "page";
	
	/* Constructeur */
	public function __construct() {
		$this->obligatoire = true;
		$this->declarer_objet("pl3_objet_page_meta");
		$this->declarer_objet("pl3_objet_page_contenu");
		parent::__construct(_CHEMIN_PAGE_COURANTE);
	}

	/* Afficher */
	public function afficher() {
		$this->afficher_head();
		$this->afficher_body();
	}
	
	public function afficher_head() {
		$this->ouvrir_head();
		$this->ecrire_head();
		$this->fermer_head();
	}
	
	public function afficher_body() {
		$this->ouvrir_body();
		$this->ecrire_body();
		$this->fermer_body();
	}	
	
	public function ouvrir_head() {
		echo "<!doctype html>\n";
		echo "<html xmlns=\"http://www.w3.org/1999/xhtml\" xml:lang=\"fr\" lang=\"fr\" dir=\"ltr\">\n";
		echo "<head>\n";
		echo "<meta http-equiv=\"content-type\" content=\"text/html; charset=UTF-8\" />\n";
		echo "<meta name=\"viewport\" content=\"width=device-width,initial-scale=1\" />\n";
		echo "<meta name=\"generator\" content=\"PL3\" />\n";
	}
	
	public function ecrire_head() {
		$this->afficher_objets("pl3_objet_page_meta");
	}
	
	public function fermer_head() {
		echo "<link rel=\"stylesheet\" type=\"text/css\" href=\"petilabo/css/pl3.css\" />\n";
		echo "</head>\n";
	}
	
	public function ouvrir_body() {
		echo "<body>\n";
	}
	
	public function ecrire_body() {
		echo "<div class=\"page\">\n";
		$this->afficher_objets("pl3_objet_page_contenu");
		echo "</div>\n";
	}
	
	public function fermer_body() {
		echo "</body>\n</html>\n";
	}
}