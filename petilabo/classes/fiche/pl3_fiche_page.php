<?php

/**
 * Classe de gestion des fiches page.xml
 */
 
class pl3_fiche_page extends pl3_outil_fiche_xml {
	const NOM_FICHE = "page";
	
	/* Propriétés */
	private $mode = _MODE_NORMAL;
	
	/* Constructeur */
	public function __construct(&$source_page, $chemin) {
		$this->obligatoire = true;
		$this->declarer_objet("pl3_objet_page_meta");
		$this->declarer_objet("pl3_objet_page_contenu");
		parent::__construct($source_page, $chemin, 1);
	}
	
	/* Accesseur/mutateur */
	public function set_mode($mode) {$this->mode = $mode;}
	public function get_mode() {return $this->mode;}

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
		$ret = $this->afficher_objets($this->mode, "pl3_objet_page_meta");
		return $ret;
	}
	
	public function fermer_head() {
		$ret = "";
		/* Partie CSS */
		$ret .= $this->declarer_css("https://maxcdn.bootstrapcdn.com/font-awesome/4.5.0/css/font-awesome.min.css");
		$ret .= $this->declarer_css(_CHEMIN_CSS."pl3.css");
		$ret .= $this->declarer_css(_CHEMIN_CSS."pl3_objets.css");
		$ret .= $this->declarer_css(_CHEMIN_CSS."pl3_admin.css", _MODE_ADMIN);
		$ret .= $this->declarer_css(_CHEMIN_TIERS."trumbo/ui/trumbowyg.min.css", _MODE_ADMIN);
		$ret .= $this->declarer_css(_CHEMIN_TIERS."trumbo/plugins/colors/ui/trumbowyg.colors.min.css", _MODE_ADMIN);
	
		/* Partie JS */
		$ret .= $this->declarer_js("//code.jquery.com/jquery-1.12.0.min.js");
		$ret .= $this->declarer_js("//code.jquery.com/ui/1.11.4/jquery-ui.js", _MODE_ADMIN);
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
		$ret .= $this->afficher_objets($this->mode, "pl3_objet_page_contenu");
		$ret .= "</div>\n";
		return $ret;
	}
	
	public function fermer_body() {
		$ret = "";
		/* TEMPORAIRE : Ajout d'un lien pour switcher le mode */
		if ($this->mode == _MODE_ADMIN) {
			$ret .= "<p style=\"margin-top:20px;\"><a href=\"../"._PAGE_COURANTE._SUFFIXE_PHP."\">Mode normal</a></p>\n";
		}
		else {
			$ret .= "<p style=\"margin-top:20px;\"><a href=\"admin/"._PAGE_COURANTE._SUFFIXE_PHP."\">Mode admin</a></p>\n";
		}

		/* Appel des outils javascript */
		$ret .= $this->declarer_js(_CHEMIN_JS."pl3.js");
		$ret .= $this->declarer_js(_CHEMIN_JS."pl3_admin.js", _MODE_ADMIN);
		$ret .= $this->declarer_js(_CHEMIN_TIERS."trumbo/trumbowyg.min.js", _MODE_ADMIN);
		$ret .= $this->declarer_js(_CHEMIN_TIERS."trumbo/langs/fr.min.js", _MODE_ADMIN);
		$ret .= $this->declarer_js(_CHEMIN_TIERS."trumbo/plugins/colors/trumbowyg.colors.min.js", _MODE_ADMIN);
		$ret .= $this->declarer_js(_CHEMIN_TIERS."trumbo/plugins/editlink/trumbowyg.editlink.min.js", _MODE_ADMIN);
		$ret .= "<br><p><strong>Recherche des objets avec icone :</strong><p>.".$this->lire_objets_avec_icone();
		$ret .= "</body>\n";
		$ret .= "</html>\n";
		return $ret;
	}
	
	public function lire_objets_avec_icone() {
		$ret = "";
		$liste = @glob(_CHEMIN_OBJET.self::NOM_FICHE."/"._PREFIXE_OBJET.self::NOM_FICHE."_*"._SUFFIXE_PHP);
		foreach ($liste as $elem_liste) {
			if (is_file($elem_liste)) {
				$nom_fichier = basename($elem_liste);
				$nom_classe = str_replace(_SUFFIXE_PHP, "", $nom_fichier);
				$nom_constante_balise = $nom_classe."::NOM_BALISE";
				$nom_constante_icone = $nom_classe."::NOM_ICONE";
				$nom_constante_fiche = $nom_classe."::NOM_FICHE";
				if ((defined($nom_constante_balise)) && (defined($nom_constante_icone)) && (defined($nom_constante_fiche)))  {
					$nom_fiche = $nom_classe::NOM_FICHE;
					if (!(strcmp($nom_fiche, "page"))) {
						$nom_balise = $nom_classe::NOM_BALISE;
						$nom_icone = $nom_classe::NOM_ICONE;
						$ret .= "<p><span class=\"fa ".$nom_icone."\"></span> => ".$nom_balise."</p>\n";
					}
				}
			}
		}
		return $ret;
	}

	private function declarer_css($fichier_css, $mode = -1) {
		$ret = "";
		if (($mode == -1) || ($mode == $this->mode)) {
			$ret .= "<link rel=\"stylesheet\" type=\"text/css\" href=\"".$fichier_css."\"/>\n";
		}
		return $ret;
	}
	private function declarer_js($fichier_js, $mode = -1) {
		$ret = "";
		if (($mode == -1) || ($mode == $this->mode)) {
			$ret .= "<script type=\"text/javascript\" src=\"".$fichier_js."\"></script>\n";
		}
		return $ret;
	}
}