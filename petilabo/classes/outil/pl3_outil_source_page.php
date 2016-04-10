<?php

/**
 * Classe de gestion d'une source page
 */
 
class pl3_outil_source_page {
	private $liste_sources = array();
	private $page = null;

	public function __construct() {
		/* Textes */
		$liste_textes = new pl3_outil_liste_fiches_xml($this, "pl3_fiche_texte");
		$liste_textes->ajouter_source(_NOM_SOURCE_GLOBAL, _CHEMIN_XML);
		$liste_textes->ajouter_source(_NOM_SOURCE_LOCAL, _CHEMIN_PAGE_COURANTE);
		$this->liste_sources[pl3_fiche_texte::NOM_FICHE] = $liste_textes;

		/* Media */
		$liste_medias = new pl3_outil_liste_fiches_xml($this, "pl3_fiche_media");
		$liste_medias->ajouter_source(_NOM_SOURCE_GLOBAL, _CHEMIN_XML);
		$liste_medias->ajouter_source(_NOM_SOURCE_LOCAL, _CHEMIN_PAGE_COURANTE);
		$this->liste_sources[pl3_fiche_media::NOM_FICHE] = $liste_medias;

		/* Styles */
		$liste_styles = new pl3_outil_liste_fiches_xml($this, "pl3_fiche_style");
		$liste_styles->ajouter_source(_NOM_SOURCE_GLOBAL, _CHEMIN_XML);
		$liste_styles->ajouter_source(_NOM_SOURCE_LOCAL, _CHEMIN_PAGE_COURANTE);
		$this->liste_sources[pl3_fiche_style::NOM_FICHE] = $liste_styles;

		/* Fichier page */
		$this->page = new pl3_fiche_page($this, _CHEMIN_PAGE_COURANTE);
	}

	public function charger_xml() {
		foreach ($this->liste_sources as $nom_fiche => $liste_fiches) {
			$liste_fiches->charger_xml();
		}
		$this->charger_page_xml();
	}

	public function charger_page_xml() {
		$this->page->charger_xml();
	}

	public function afficher($mode) {
		$html = $this->page->afficher($mode);
		return $html;
	}

	/* Accesseurs */
	public function get_page() {return $this->page;}
	
	/* Recherches */
	public function chercher_liste_textes_par_nom($balise, $nom) {
		return $this->chercher_liste_fiches_par_nom(pl3_fiche_texte::NOM_FICHE, $balise, $nom);
	}
	public function chercher_liste_medias_par_nom($balise, $nom) {
		return $this->chercher_liste_fiches_par_nom(pl3_fiche_media::NOM_FICHE, $balise, $nom);
	}
	public function chercher_liste_styles_par_nom($balise, $nom) {
		return $this->chercher_liste_fiches_par_nom(pl3_fiche_style::NOM_FICHE, $balise, $nom);
	}
	public function chercher_liste_fiches_par_nom($nom_fiche, $balise, $nom) {
		if (isset($this->liste_sources[$nom_fiche])) {
			return $this->liste_sources[$nom_fiche]->chercher_instance_balise_par_nom($balise, $nom);
		}
		else {return null;}
	}

	/* Méthodes de parsing */
	public function parser_balise($fiche, &$objet_parent, $nom_balise, &$noeud) {
		$ret = array();
		if ($noeud != null) {
			$nom_classe = _PREFIXE_OBJET.$fiche."_".$nom_balise;
			$reflection = new ReflectionClass($nom_classe);
			$balise = $reflection->getConstant("NOM_BALISE");
			$liste = $noeud->getElementsByTagName($balise);
			foreach($liste as $element) {
				$instance = $reflection->newInstanceArgs(array(&$this, 1 + count($ret), &$objet_parent, &$element));
				$instance->parser_attributs($element);
				$ret[] = $instance;
			}
		}
		return $ret;
	}
	
	public function parser_balise_fille($fiche, &$objet_parent, $nom_classe, $nom_balise, &$noeud) {
		$ret = array();
		if ($noeud != null) {
			$nom_classe = $nom_classe."_".$nom_balise;
			$reflection = new ReflectionClass($nom_classe);
			$balise = $reflection->getConstant("NOM_BALISE");
			$liste = $noeud->getElementsByTagName($balise);
			foreach($liste as $element) {
				$instance = $reflection->newInstanceArgs(array(&$this, 1 + count($ret), &$objet_parent, &$element));
				$instance->parser_attributs($element);
				$instance->parser_valeur($element);
				$ret[] = $instance;
			}
		}
		return $ret;
	}
	
	public function parser_toute_balise($fiche, &$objet_parent, &$noeud) {
		$ret = array();
		if ($noeud != null) {
			$liste_objets = $noeud->childNodes;
			foreach ($liste_objets as $objet) {
				if ($objet->nodeType != XML_ELEMENT_NODE) {continue;}
				$nom_balise = $objet->nodeName;
				$nom_classe = _PREFIXE_OBJET.$fiche."_".$nom_balise;
				$nom_fichier = _CHEMIN_OBJET.$fiche."/".$nom_classe.".php";
				/* On teste le fichier et non la classe car l'échec de l'autoload provoque un die */
				$fichier_existe = @file_exists($nom_fichier);
				if ($fichier_existe) {
					$reflection = new ReflectionClass($nom_classe);
					$instance = $reflection->newInstanceArgs(array(&$this, 1 + count($ret), &$objet_parent, &$objet));
					$instance->parser_attributs($objet);
					$instance->parser_valeur($objet);
					$ret[] = $instance;
				}
				else {
					echo "ERREUR : L'objet ".$nom_balise." n'existe pas.<br>\n";
				}
			}
		}
		return $ret;
	}
}