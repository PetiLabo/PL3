<?php

/**
 * Classe de gestion des fichiers XML
 */
 
class pl3_outil_fiche_xml {
	const NOM_BALISE_GENERIQUE = "petilabo";

	protected $id = 0;
	protected $noeud = null;
	protected $obligatoire = false;
	protected $nom_fichier_xml = null;
	protected $liste_noms_objets = array();
	protected $liste_objets = array();
	private $dom = null;
	private $petilabo = null;
	
	/* Constructeur */
	public function __construct($chemin) {
		$this->id = uniqid();
		$this->nom_fichier_xml = $chemin.(static::NOM_FICHE)._SUFFIXE_XML;
		$this->dom = new DOMDocument();
	}
	protected function declarer_objet($nom_classe) {
		$nom_balise = $nom_classe::NOM_BALISE;
		$this->liste_noms_objets[$nom_classe] = $nom_balise;
		$this->liste_objets[$nom_classe] = array();
	}

	/* Accesseurs */
	public function lire_id() {return $this->id;}
	public function lire_nom_fichier_xml() {return $this->nom_fichier_xml;}

	/* Chargement */
	public function charger_xml() {
		$ret = $this->charger();
		if ($ret) {$this->charger_objets(); }
		else if ($this->obligatoire) {die("ERREUR : Fichier XML obligatoire introuvable");}
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
			foreach($liste_objets as $objet) {$objet->charger_xml();}
			$this->liste_objets[$nom_classe] = $liste_objets;
		}
	}
	
	/* Ajouts "inline" */
	public function ajouter_objet(&$objet) {
		$nom_classe = get_class($objet);
		if (isset($this->liste_noms_objets[$nom_classe])) {
			$this->liste_objets[$nom_classe][] = $objet;
		}
		else {die("ERREUR : Tentative d'ajout d'un objet dans une classe non déclarée.");}
	}
	
	/* Parser */
	protected function parser_balise($nom_balise) {
		$ret = pl3_outil_parser_xml::Parser_balise(static::NOM_FICHE, $this->id, $nom_balise, $this->noeud);
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
		$ret = "&lt;?xml version=\"1.0\" encoding=\"UTF-8\"?&gt;\n";
		$ret .= "&lt;".self::NOM_BALISE_GENERIQUE."&gt;\n";
		return $ret;
	}

	protected function fermer_fiche_xml() {
		$ret = "&lt;/".self::NOM_BALISE_GENERIQUE."&gt;\n";
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
	protected function afficher_objets($nom_objet = null) {
		if ($nom_objet == null) {
			foreach ($this->liste_objets as $liste_objets) {
				foreach ($liste_objets as $objet) {
					$objet->afficher();
				}
			}
		}
		else if (isset($this->liste_objets[$nom_objet])) {
			foreach ($this->liste_objets[$nom_objet] as $objet) {
				$objet->afficher();
			}
		}
	}
	
	/* Recherches */
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