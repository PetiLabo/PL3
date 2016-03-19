<?php

/**
 * Classe de gestion des éléments XML
 */
 
abstract class pl3_outil_objet_xml {
	protected $id = 0;
	protected $id_parent = 0;
	protected $noeud = null;
	protected $attributs = array();
	protected $liste_noms_objets = array();
	protected $liste_objets = array();
	protected $avec_valeur = true;
	protected $valeur;
	protected $nom_fiche;
	
	/* Constructeur */
	public function __construct($nom_fiche, $id_parent, &$noeud = null) {
		$this->id = uniqid();
		$this->nom_fiche = $nom_fiche;
		$this->id_parent = $id_parent;
		$this->noeud = $noeud;
	}
	protected function declarer_objet($nom_classe) {
		$nom_balise = $nom_classe::NOM_BALISE;
		$this->liste_noms_objets[$nom_classe] = $nom_balise;
		$this->liste_objets[$nom_classe] = array();
	}

	/* Méthodes abstraites */
	abstract public function afficher();
	abstract public function ecrire_xml($niveau);
	
	/* Accesseurs */
	public function lire_id() {return $this->id;}
	
	
	/* Ajouts "inline" */
	public function ajouter_objet(&$objet) {
		$nom_classe = get_class($objet);
		if (isset($this->liste_noms_objets[$nom_classe])) {
			$this->liste_objets[$nom_classe][] = $objet;
		}
		else {die("ERREUR : Tentative d'ajout d'un objet dans une classe non déclarée.");}
	}
	
	/* Parsing des balises */
	public function parser_balise($nom_balise) {
		$ret = pl3_outil_parser_xml::Parser_balise($this->nom_fiche, $this->id, $nom_balise, $this->noeud);
		return $ret;
	}
	public function parser_balise_fille($nom_balise, $unique = true) {
		$tab_ret = pl3_outil_parser_xml::Parser_balise_fille($this->nom_fiche, $this->id, get_called_class(), $nom_balise, $this->noeud);
		if ($unique) {
			$nb_ret = (int) count($tab_ret);
			$ret = ($nb_ret > 0)?$tab_ret[$nb_ret - 1]:null;
		}
		else {
			$ret = $tab_ret;
		}
		return $ret;
	}
	
	/* Gestion de la valeur */
	public function avec_valeur() {return $this->avec_valeur;}
	public function set_valeur($valeur) {$this->valeur = $valeur;}
	public function get_valeur() {return $this->valeur;}
	
	/* Gestion du noeud */
	public function &get_noeud() {return $this->noeud;}

	/* Gestion des attributs */
	public function set_attribut($nom_attribut, $valeur_attribut) {
		$this->attributs[$nom_attribut] = $valeur_attribut;
	}
	public function get_attribut_chaine($nom_attribut) {
		$ret = isset($this->attributs[$nom_attribut])?$this->attributs[$nom_attribut]:null;
		return $ret;
	}
	public function get_attribut_entier($nom_attribut, $defaut = 0) {
		$ret = isset($this->attributs[$nom_attribut])?((int) $this->attributs[$nom_attribut]):((int) $defaut);
		return $ret;
	}
	public function has_attribut($nom_attribut) {
		return isset($this->attributs[$nom_attribut]);
	}

	/* Mise en forme XML des attributs */
	public function get_xml_attribut_chaine($nom_attribut) {
		$ret = ($this->has_attribut($nom_attribut))?($nom_attribut."=\"".$this->get_attribut_chaine($nom_attribut))."\"":"";
		return $ret;
	}
	public function get_xml_attribut_entier($nom_attribut) {
		$ret = ($this->has_attribut($nom_attribut))?($nom_attribut."=".((int) $this->get_attribut_chaine($nom_attribut))):"";
		return $ret;
	}
	
	/* Affichage des balises XML */
	public function ouvrir_xml($niveau, $liste_attributs = null) {
		$ret = $this->indenter_xml($niveau);
		$ret .= "&lt;".static::NOM_BALISE;
		$ret .= $this->ouvrir_attributs_xml($liste_attributs);
		$ret .= "&gt;\n";
		return $ret;
	}
	public function fermer_xml($niveau) {
		$ret = $this->indenter_xml($niveau);
		$ret .= "&lt;/".static::NOM_BALISE."&gt;\n";
		return $ret;
	}
	public function ouvrir_fermer_xml($niveau, $liste_attributs = null) {
		$ret = $this->indenter_xml($niveau);
		$ret .= "&lt;".static::NOM_BALISE;
		$ret .= $this->ouvrir_attributs_xml($liste_attributs);
		if ($this->avec_valeur) {
			$ret .= "&gt;";
			$ret .= $this->valeur;
			$ret .= "&lt;/".static::NOM_BALISE;
		}
		else {$ret .= "/";}
		$ret .= "&gt;\n";
		return $ret;
	}
	
	/* Méthodes de service pour l'affichage des balises XML */
	protected function indenter_xml($niveau) {
		$ret = str_repeat("&nbsp;", 5 * ((int) ($niveau)));
		return $ret;
	}
	protected function ouvrir_attributs_xml($liste_attributs) {
		$ret = "";
		$nb_attributs = count($liste_attributs);
		if ($nb_attributs > 0) {
			foreach($liste_attributs as $attribut) {
				if (strlen($attribut) > 0) {
					$ret .= " ".$attribut;
				}
			}
		}
		return $ret;
	}
	
	public function __call($methode, $args) {
		if (!(strncmp($methode, "get_attribut_", 13))) {
			$nom_attribut = substr($methode, 13);
			return $this->get_attribut_chaine($nom_attribut);
		}
		else if (!(strncmp($methode, "set_attribut_", 13))) {
			$nom_attribut = substr($methode, 13);
			$this->set_attribut($nom_attribut, $args[0]);
		}
		else {die("ERREUR : Appel d'une méthode non définie dans un objet XML"); }
	}

}