<?php

/**
 * Classe de gestion des fiches page.xml
 */
 
class pl3_fiche_page extends pl3_outil_fiche_xml {
	const NOM_FICHE = "page";
	
	/* Constructeur */
	public function __construct(&$source_page, $chemin) {
		$this->obligatoire = true;
		$this->declarer_objet("pl3_objet_page_meta");
		$this->declarer_objet("pl3_objet_page_contenu");
		parent::__construct($source_page, $chemin, 1);
	}

	/* Afficher */
	public function afficher($mode) {
		$ret = "";
		$ret .= $this->afficher_head($mode);
		$ret .= $this->afficher_body($mode);
		return $ret;
	}
	
	public function afficher_head($mode) {
		$ret = "";
		$ret .= $this->ouvrir_head($mode);
		$ret .= $this->ecrire_head($mode);
		$ret .= $this->fermer_head($mode);
		return $ret;
	}
	
	public function afficher_body($mode) {
		$ret = "";
		$ret .= $this->ouvrir_body($mode);
		$ret .= $this->ecrire_body($mode);
		$ret .= $this->fermer_body($mode);
		return $ret;
	}	
	
	public function ouvrir_head($mode) {
		$ret = "";
		$ret .= "<!doctype html>\n";
		$ret .= "<html xmlns=\"http://www.w3.org/1999/xhtml\" xml:lang=\"fr\" lang=\"fr\" dir=\"ltr\">\n";
		$ret .= "<head>\n";
		$ret .= "<meta http-equiv=\"content-type\" content=\"text/html; charset=UTF-8\" />\n";
		$ret .= "<meta name=\"viewport\" content=\"width=device-width,initial-scale=1\" />\n";
		$ret .= "<meta name=\"generator\" content=\"PL3\" />\n";
		return $ret;
	}
	
	public function ecrire_head($mode) {
		$ret = $this->afficher_objets($mode, "pl3_objet_page_meta");
		return $ret;
	}
	
	public function fermer_head($mode) {
		$ret = "";
		$ret .= "<link rel=\"stylesheet\" type=\"text/css\" href=\"https://maxcdn.bootstrapcdn.com/font-awesome/4.5.0/css/font-awesome.min.css\" />\n";
		$ret .= "<link rel=\"stylesheet\" type=\"text/css\" href=\""._CHEMIN_CSS."pl3.css\" />\n";
		$ret .= "<link rel=\"stylesheet\" type=\"text/css\" href=\""._CHEMIN_CSS."pl3_objets.css\" />\n";
		if ($mode == _MODE_ADMIN) {
			$ret .= "<link rel=\"stylesheet\" type=\"text/css\" href=\""._CHEMIN_CSS."pl3_admin.css\" />\n";
			
			$ret .= "<link rel=\"stylesheet\" type=\"text/css\" href=\""._CHEMIN_TIERS."/trumbo/ui/trumbowyg.min.css\" />\n";
			$ret .= "<link rel=\"stylesheet\" type=\"text/css\" href=\""._CHEMIN_TIERS."/trumbo/plugins/colors/ui/trumbowyg.colors.min.css\" />\n";
		}
		$ret .= "<script type=\"text/javascript\" src=\"//code.jquery.com/jquery-1.12.0.min.js\"></script>\n";
		if ($mode == _MODE_ADMIN) {
			$ret .= "<script type=\"text/javascript\" src=\"//code.jquery.com/ui/1.11.4/jquery-ui.js\"></script>\n";
		}
		$ret .= "<script type=\"text/javascript\" src=\""._CHEMIN_JS."pl3.js\"></script>\n";
		if ($mode == _MODE_ADMIN) {
			$ret .= "<script type=\"text/javascript\" src=\""._CHEMIN_JS."pl3_admin.js\"></script>\n";
		}
		$ret .= "</head>\n";
		return $ret;
	}
	
	public function ouvrir_body($mode) {
		$ret = "";
		$ret .= "<body>\n";
		return $ret;
	}
	
	public function ecrire_body($mode) {
		$ret = "";
		$ret .= "<div class=\"page\" name=\""._PAGE_COURANTE."\">\n";
		$ret .= $this->afficher_objets($mode, "pl3_objet_page_contenu");
		$ret .= "</div>\n";
		return $ret;
	}
	
	public function fermer_body($mode) {
		$ret = "";
		if ($mode == _MODE_ADMIN) {
			$ret .= "<p style=\"margin-top:20px;\"><a href=\"../"._PAGE_COURANTE._SUFFIXE_PHP."\">Mode normal</a></p>\n";
			$ret .= "<script type=\"text/javascript\" src=\""._CHEMIN_TIERS."trumbo/trumbowyg.min.js\"></script>\n";
			$ret .= "<script type=\"text/javascript\" src=\""._CHEMIN_TIERS."trumbo/langs/fr.min.js\"></script>\n";
			$ret .= "<script type=\"text/javascript\" src=\""._CHEMIN_TIERS."trumbo/plugins/colors/trumbowyg.colors.min.js\"></script>\n";
			$ret .= "<script type=\"text/javascript\" src=\""._CHEMIN_TIERS."trumbo/plugins/editlink/trumbowyg.editlink.min.js\"></script>\n";
		}
		else {
			$ret .= "<p style=\"margin-top:20px;\"><a href=\"admin/"._PAGE_COURANTE._SUFFIXE_PHP."\">Mode admin</a></p>\n";
		}
		$ret .= "</body>\n";
		$ret .= "</html>\n";
		return $ret;
	}
}