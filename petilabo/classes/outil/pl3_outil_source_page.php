<?php

class pl3_outil_source_page {
	/* Singleton */
	private static $Source_page = null;

	/* Ressources */
	private $liste_sources = array();
	private $liste_themes = array();
	private $page = null;

	/* Thèmes */
	private $theme = null;
	private $chemin_theme = null;
	private $fichier_theme_css = null;
	private $fichier_theme_style_xml = null;
	private $fichier_theme_additionnel_css = null;
	private $theme_a_jour = false;

	/* Constructeur privé */
	private function __construct() {
		/* Déclaration des textes */
		$liste_textes = new pl3_outil_liste_fiches_xml("texte");
		$liste_textes->ajouter_source(_NOM_SOURCE_GLOBAL, _CHEMIN_XML);
		$liste_textes->ajouter_source(_NOM_SOURCE_LOCAL, _CHEMIN_PAGE_COURANTE);
		$this->liste_sources[pl3_fiche_texte::NOM_FICHE] = $liste_textes;

		/* Déclaration des media */
		$liste_medias = new pl3_outil_liste_fiches_xml("media");
		$liste_medias->ajouter_source(_NOM_SOURCE_GLOBAL, _CHEMIN_XML);
		$liste_medias->ajouter_source(_NOM_SOURCE_LOCAL, _CHEMIN_PAGE_COURANTE);
		$this->liste_sources[pl3_fiche_media::NOM_FICHE] = $liste_medias;

		/* Déclaration du fichier page */
		$this->page = new pl3_fiche_page(_CHEMIN_PAGE_COURANTE);
	}
	public static function &Get() {
		if (is_null(self::$Source_page)) {
			self::$Source_page = new pl3_outil_source_page();
		}
		return self::$Source_page;
	}

	/* Instanciation de nouveaux objets */
	public function instancier_nouveau($classe_objet, $id_contenu = 0, $id_bloc = 0) {
		$instance = null;
		$nom_fiche = $classe_objet::NOM_FICHE;
		/* Cas d'un objet hors page : on l'insère à la racine de la fiche */
		if (strcmp($nom_fiche, "page")) {
			if (isset($this->liste_sources[$nom_fiche])) {
				$liste_fiches = $this->liste_sources[$nom_fiche];
				$instance = $liste_fiches->instancier_nouveau(_NOM_SOURCE_LOCAL, $classe_objet);
			}
		}
		/* Cas d'un objet page : on l'insère dans un bloc */
		else {
			$contenu = $this->page->chercher_objet_classe_par_id("pl3_objet_page_contenu", $id_contenu);
			if ($contenu != null) {
				$bloc = $contenu->chercher_objet_classe_par_id("pl3_objet_page_bloc", $id_bloc);
				if ($bloc != null) {
					$instance = $bloc->instancier_nouveau($classe_objet);
				}
			}
		}
		return $instance;
	}
	
	/* Enregistrement de nouveaux objets */
	public function enregistrer_nouveau(&$objet, $id_contenu = 0, $id_bloc = 0) {
		$classe_objet = get_class($objet);
		$nom_fiche = $classe_objet::NOM_FICHE;
		/* Cas d'un objet hors page : on l'insère à la racine de la fiche */
		if (strcmp($nom_fiche, "page")) {
			if (isset($this->liste_sources[$nom_fiche])) {
				$liste_fiches = $this->liste_sources[$nom_fiche];
				$liste_fiches->ajouter_objet(_NOM_SOURCE_LOCAL, $objet);
				$liste_fiches->enregistrer_xml(_NOM_SOURCE_LOCAL);
			}
		}
		/* Cas d'un objet page : on l'insère dans un bloc */
		else {
			$contenu = $this->page->chercher_objet_classe_par_id("pl3_objet_page_contenu", $id_contenu);
			if ($contenu != null) {
				$bloc = $contenu->chercher_objet_classe_par_id("pl3_objet_page_bloc", $id_bloc);
				if ($bloc != null) {
					$instance = $bloc->ajouter_objet($objet);
					$this->enregistrer_page_xml();
				}
			}
		}
	}

	/* Chargement et enregistrement XML */
	public function charger_xml() {
		/* Chargement des ressources */
		foreach ($this->liste_sources as $nom_fiche => $liste_fiches) {
			$liste_fiches->charger_xml();
		}
		
		/* Chargement de la page */
		$this->charger_page_xml();

		/* Identification du thème */
		$this->theme = $this->page->get_nom_theme();
		$this->chemin_theme = _CHEMIN_THEMES_XML.$this->theme."/";
		$this->fichier_theme_css = _CHEMIN_THEMES_CSS."style_".$this->theme._SUFFIXE_CSS;
		$this->fichier_theme_style_xml = $this->chemin_theme."theme.xml";
		$this->fichier_theme_additionnel_css = $this->chemin_theme."theme.css";
		$this->theme_a_jour = $this->verifier_theme_a_jour();

		/* Déclaration du thème */
		$this->liste_themes = new pl3_outil_liste_fiches_xml("theme");
		$this->liste_themes->ajouter_source(_NOM_SOURCE_THEME, $this->chemin_theme);

		/* Chargement du thème */
		$this->charger_theme_xml();
	}
	public function charger_theme_xml() {
		$this->liste_themes->charger_xml();
	}
	public function charger_page_xml() {$this->page->charger_xml();}
	
	public function enregistrer_xml() {
		foreach ($this->liste_sources as $nom_fiche => $liste_fiches) {
			$liste_fiches->enregistrer_xml();
		}
		$this->enregistrer_page_xml();
	}
	public function enregistrer_page_xml() {$this->page->enregistrer_xml();}

	/* Affichage */
	public function afficher($mode) {
		$this->generer_theme($mode);
		$this->page->set_mode($mode);
		$html = $this->page->afficher();
		return $html;
	}
	
	/* Génération du CSS */
	public function generer_theme($mode) {
		if (!($this->theme_a_jour)) {
			$css = $this->liste_themes->afficher($mode);
			$this->generer_theme_css($css);
		}
	}

	/* Accesseurs */
	public function &get_page() {return $this->page;}
	public function get_nom_theme() {return $this->theme;}
	
	/* Recherches */
	public function chercher_liste_textes_par_nom($balise, $nom) {
		return $this->chercher_liste_fiches_par_nom(pl3_fiche_texte::NOM_FICHE, $balise, $nom);
	}
	public function chercher_liste_medias_par_nom($balise, $nom) {
		return $this->chercher_liste_fiches_par_nom(pl3_fiche_media::NOM_FICHE, $balise, $nom);
	}
	public function chercher_liste_themes_par_nom($balise, $nom) {
		return $this->liste_themes->chercher_instance_balise_par_nom($balise, $nom);
	}
	public function chercher_liste_fiches_par_nom($nom_fiche, $balise, $nom) {
		if (isset($this->liste_sources[$nom_fiche])) {
			return $this->liste_sources[$nom_fiche]->chercher_instance_balise_par_nom($balise, $nom);
		}
		else {return null;}
	}
	public function chercher_liste_noms_par_fiche($nom_fiche, $nom_classe) {
		if (isset($this->liste_sources[$nom_fiche])) {
			return $this->liste_sources[$nom_fiche]->chercher_liste_noms_par_classe($nom_classe);
		}
		else if (!(strcmp($nom_fiche, "theme"))) {
			return $this->liste_themes->chercher_liste_noms_par_classe($nom_classe);
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
				$instance = $reflection->newInstanceArgs(array(1 + count($ret), &$objet_parent, &$element));
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
				$instance = $reflection->newInstanceArgs(array(1 + count($ret), &$objet_parent, &$element));
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
					$instance = $reflection->newInstanceArgs(array(1 + count($ret), &$objet_parent, &$objet));
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
	
	/* Vérification que le thème est à jour */
	private function verifier_theme_a_jour() {
		$ret = false;
		if (@file_exists($this->fichier_theme_css)) {
			$date_theme_css = @filemtime($this->fichier_theme_css);
			$date_theme_style_xml = @filemtime($this->fichier_theme_style_xml);
			if ($date_theme_css > $date_theme_style_xml) {
				if (@file_exists($this->fichier_theme_additionnel_css)) {
					$date_theme_additionnel_css = @filemtime($this->fichier_theme_additionnel_css);
					$ret = ($date_theme_css > $date_theme_additionnel_css);
				}
				else {
					$ret = true;
				}
			}
		}
		return $ret;
	}

	/* Generation du fichier CSS */
	private function generer_theme_css($css) {
		$css = "/* CSS thème ".$this->theme." */\n".$css;
		if (file_exists($this->fichier_theme_additionnel_css)) {
			$css .= "\n".@file_get_contents($this->fichier_theme_additionnel_css);
		}
		file_put_contents($this->fichier_theme_css, $css);
	}
}