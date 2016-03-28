<?php

/**
 * Classe de gestion des fiches page.xml
 */
 
class pl3_fiche_page extends pl3_outil_fiche_xml {
	const NOM_FICHE = "page";
	
	/* Constructeur */
	public function __construct($chemin) {
		$this->obligatoire = true;
		$this->declarer_objet("pl3_objet_page_meta");
		$this->declarer_objet("pl3_objet_page_contenu");
		parent::__construct($chemin, 1);
	}

	/* Afficher */
	public function afficher() {
		$ret = "";
		$ret .= $this->afficher_head();
		$ret .= $this->afficher_body();
		return $ret;
	}
	
	public function afficher_head() {
		$ret = "";
		$ret .= $this->ouvrir_head();
		$ret .= $this->ecrire_head();
		$ret .= $this->fermer_head();
		return $ret;
	}
	
	public function afficher_body() {
		$ret = "";
		$ret .= $this->ouvrir_body();
		$ret .= $this->ecrire_body();
		$ret .= $this->fermer_body();
		return $ret;
	}	
	
	public function ouvrir_head() {
		$ret = "";
		$ret .= "<!doctype html>\n";
		$ret .= "<html xmlns=\"http://www.w3.org/1999/xhtml\" xml:lang=\"fr\" lang=\"fr\" dir=\"ltr\">\n";
		$ret .= "<head>\n";
		$ret .= "<meta http-equiv=\"content-type\" content=\"text/html; charset=UTF-8\" />\n";
		$ret .= "<meta name=\"viewport\" content=\"width=device-width,initial-scale=1\" />\n";
		$ret .= "<meta name=\"generator\" content=\"PL3\" />\n";
		return $ret;
	}
	
	public function ecrire_head() {
		$ret = $this->afficher_objets("pl3_objet_page_meta");
		return $ret;
	}
	
	public function fermer_head() {
		$ret = "";
		$ret .= "<link rel=\"stylesheet\" type=\"text/css\" href=\"https://maxcdn.bootstrapcdn.com/font-awesome/4.5.0/css/font-awesome.min.css\" />\n";
		$ret .= "<link rel=\"stylesheet\" type=\"text/css\" href=\""._CHEMIN_CSS."pl3.css\" />\n";
		$ret .= "<script type=\"text/javascript\" src=\"//code.jquery.com/jquery-1.12.0.min.js\"></script>\n";
		$ret .= "<script type=\"text/javascript\" src=\"//code.jquery.com/ui/1.11.4/jquery-ui.js\"></script>\n";
		$ret .= "<script type=\"text/javascript\" src=\""._CHEMIN_JS."pl3.js\"></script>\n";
		$ret .= "</head>\n";
		return $ret;
	}
	
	public function ouvrir_body() {
		$ret = "";
		$ret .= "<body>\n";
		return $ret;
	}
	
	public function ecrire_body() {
		$ret = "";
		$ret .= "<div class=\"page\" name=\""._PAGE_COURANTE."\">\n";
		$ret .= $this->afficher_objets("pl3_objet_page_contenu");
		$ret .= "</div>\n";
		return $ret;
	}
	
	public function fermer_body() {
		$ret = "";
		$ret .= "</body>\n";
		$ret .= "</html>\n";
		return $ret;
	}
}