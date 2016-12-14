<?php

/**
 * Classe de gestion des fichiers XML
 */
 
class pl3_outil_fiche_xml extends pl3_outil_source_xml {
	const NOM_BALISE_GENERIQUE = "petilabo";

	/* Propriétés */
	protected $mode = _MODE_NORMAL;
	protected $id = 0;
	protected $noeud = null;
	protected $obligatoire = false;
	protected $nom_fichier_xml = null;
	protected $liste_noms_objets = array();
	protected $liste_objets = array();
	protected $fiche_a_jour = true;
	private $dom = null;
	
	/* Constructeur */
	public function __construct($chemin, $id) {
		$this->id = $id;
		$this->nom_fichier_xml = $chemin.(static::NOM_FICHE)._SUFFIXE_XML;
		$this->dom = new DOMDocument();
	}
	
	/* Gestion des objets */
	protected function declarer_objet($nom_classe) {
		$nom_balise = $nom_classe::NOM_BALISE;
		$this->liste_noms_objets[$nom_classe] = $nom_balise;
		$this->liste_objets[$nom_classe] = array();
	}

	public function instancier_nouveau($nom_classe) {
		if (isset($this->liste_objets[$nom_classe])) {
			$objet = new $nom_classe(1 + count($this->liste_objets[$nom_classe]), $this);
			$objet->construire_nouveau();
			return $objet;
		}
		else {
			die("ERREUR : Instanciation d'un objet inexistant");
		}
	}

	/* Accesseurs / mutateurs */
	public function set_mode($mode) {$this->mode = $mode;}
	public function get_mode() {return $this->mode;}
	public function lire_id() {return $this->id;}
	public function lire_nom_fichier_xml() {return $this->nom_fichier_xml;}
	public function fiche_a_jour() {return $this->fiche_a_jour;}

	/* Chargement */
	public function charger_xml() {
		$ret = $this->charger();
		if ($ret) {$this->charger_objets(); }
		else if ($this->obligatoire) {die("ERREUR : Fichier XML obligatoire introuvable");}
		if (!($this->fiche_a_jour)) {
			$this->enregistrer_xml();
			$this->fiche_a_jour = true;
		}
		return $ret;
	}
	protected function charger() {
		$ret = false;
		$load = @$this->dom->load($this->nom_fichier_xml);
		if ($load) {
			$document = $this->dom->getElementsByTagName(self::NOM_BALISE_GENERIQUE);
			if ($document->length > 0) {
				$this->noeud = $document->item(0);
				$ret = true;
			}
		}
		return $ret;
	}
	protected function charger_objets() {
		foreach ($this->liste_noms_objets as $nom_classe => $nom_balise) {
			$liste_objets = $this->parser_balise($nom_balise);
			foreach($liste_objets as $objet) {
				$objet->charger_xml();
				if (!($objet->objet_a_jour())) {$this->fiche_a_jour = false;}
			}
			$this->liste_objets[$nom_classe] = $liste_objets;
		}
	}
	
	/* Sauvegarde */
	public function enregistrer_xml() {
		$ret = file_put_contents($this->nom_fichier_xml, $this->ecrire_xml());
		return $ret;
	}
	
	/* Ajouts "inline" */
	public function ajouter_objet(&$objet) {
		$nom_classe = get_class($objet);
		if (isset($this->liste_noms_objets[$nom_classe])) {
			$this->liste_objets[$nom_classe][] = $objet;
		}
		else {die("ERREUR : Tentative d'ajout d'un objet dans une classe non déclarée.");}
	}
	
	/* Suppressions "inline" */
	public function enlever_objet(&$objet_enleve) {
		$nom_classe = get_class($objet_enleve);
		if (isset($this->liste_noms_objets[$nom_classe])) {
			$index_enleve = -1;
			$objet_enleve_id = $objet_enleve->lire_id();
			foreach($this->liste_objets[$nom_classe] as $index => $objet) {
				if (!(strcmp($objet->lire_id(), $objet_enleve_id))) {$index_enleve = $index;}
			}
			if ($index_enleve >= 0) {unset($this->liste_objets[$nom_classe][$index_enleve]);}
		}
		else {die("ERREUR : Tentative d'ajout d'un objet dans une classe non déclarée.");}
	}
		
	/* Parser */
	protected function parser_balise($nom_balise) {
		$source_page = $this->get_source_page();
		$ret = $source_page->parser_balise(static::NOM_FICHE, $this, $nom_balise, $this->noeud);
		return $ret;
	}
	
	/* Ecritures XML */
	public function ecrire_xml() {
		$xml = $this->ouvrir_fiche_xml();
		$xml .= $this->ecrire_objets_xml();
		$xml .= $this->fermer_fiche_xml();
		return $xml;
	}
	
	protected function ouvrir_fiche_xml() {
		$ret = "<?xml version=\"1.0\" encoding=\"UTF-8\"?>\n";
		$ret .= "<".self::NOM_BALISE_GENERIQUE.">\n";
		return $ret;
	}

	protected function fermer_fiche_xml() {
		$ret = "</".self::NOM_BALISE_GENERIQUE.">\n";
		return $ret;
	}
	protected function ecrire_objets_xml() {
		$ret = "";
		foreach ($this->liste_objets as $liste_objets) {
			foreach ($liste_objets as $objet) {
				$ret .= $objet->ecrire_xml(1);
			}
		}
		return $ret;
	}

	/* Afficher */
	protected function afficher_objets($mode, $nom_objet = null) {
		$ret = "";
		if ($nom_objet == null) {
			foreach ($this->liste_objets as $liste_objets) {
				foreach ($liste_objets as $objet) {
					$ret .= $objet->afficher($mode);
				}
			}
		}
		else if (isset($this->liste_objets[$nom_objet])) {
			foreach ($this->liste_objets[$nom_objet] as $objet) {
				$ret .= $objet->afficher($mode);
			}
		}
		return $ret;
	}
	
	/* Recherches */
	public function chercher_objet_classe_par_id($nom_classe, $valeur_id) {
		if (isset($this->liste_objets[$nom_classe])) {
			foreach($this->liste_objets[$nom_classe] as $instance) {
				$id = $instance->lire_id();
				if ($valeur_id == $id) {return $instance;}
			}
		}
		return null;
	}
	public function chercher_objet_classe_par_attribut($nom_classe, $nom_attribut, $valeur_attribut) {
		if (isset($this->liste_objets[$nom_classe])) {
			foreach($this->liste_objets[$nom_classe] as $instance) {
				$valeur_instance = $instance->get_attribut_chaine($nom_attribut);
				if ($valeur_instance == $valeur_attribut) {return $instance;}
			}
		}
		return null;
	}
	public function chercher_objet_balise_par_attribut($nom_balise, $nom_attribut, $valeur_attribut) {
		$nom_classe = $this->objet_balise_to_classe($nom_balise);
		if ($nom_classe != null) {return $this->chercher_objet_classe_par_attribut($nom_classe, $nom_attribut, $valeur_attribut);}
		else {return null;}
	}
	public function chercher_liste_noms_par_classe($nom_classe) {
		$ret = array();
		if (isset($this->liste_objets[$nom_classe])) {
			foreach($this->liste_objets[$nom_classe] as $instance) {
				$nom = $instance->get_attribut_chaine(self::NOM_ATTRIBUT_NOM);
				$id = (int) $instance->lire_id();
				$ret[$id] = $nom;
			}
		}
		return $ret;
	}
	
	/* Debug */
	public function dump() {
		echo "<pre>".htmlentities($this->dom->saveXML())."</pre>\n";
	}
	
	/* Méthodes de service */
	private function objet_balise_to_classe($nom_balise) {
		$recherche = array_search($nom_balise, $this->liste_noms_objets);
		return ($recherche !== false)?((string) $recherche):null;
	}
}