<?php

/**
 * Classe de gestion des fichiers XML
 */
 
class pl3_outil_fiche_xml {
	const NOM_BALISE_GENERIQUE = "petilabo";

	protected $id = 0;
	protected $noeud = null;
	protected $nom_fichier_xml = null;
	protected $liste_noms_objets = array();
	protected $liste_objets = array();
	private $dom = null;
	private $petilabo = null;
	
	/* Constructeur */
	public function __construct() {
		$this->id = uniqid();
		$this->nom_fichier_xml = "xml/".static::NOM_FICHE.".xml";
		$this->dom = new DOMDocument();
	}
	protected function ajouter_objet($nom_classe) {
		$nom_balise = $nom_classe::NOM_BALISE;
		$this->liste_noms_objets[$nom_classe] = $nom_balise;
	}

	/* Accesseurs */
	public function lire_id() {return $this->id;}
	public function lire_nom_fichier_xml() {return $this->nom_fichier_xml;}

	/* Chargement */
	public function charger_xml() {
		$ret = $this->charger();
		if ($ret) {$this->charger_objets(); }
		else {die("Erreur XML...");}
	}

	protected function charger() {
		$ret = false;
		$load = $this->dom->load($this->nom_fichier_xml);
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
	protected function afficher_objets() {
		foreach ($this->liste_objets as $liste_objets) {
			foreach ($liste_objets as $objet) {
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