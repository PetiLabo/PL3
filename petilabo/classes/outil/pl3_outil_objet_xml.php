<?php

/**
 * Classe de gestion des objets XML
 */
 
abstract class pl3_outil_objet_xml extends pl3_outil_source_xml {
	const NOM_VALEUR = "valeur";
	const TYPE_ENTIER = 1;
	const TYPE_CHAINE = 2;
	const TYPE_TEXTE = 3;
	const TYPE_ICONE = 4;
	const TYPE_REFERENCE = 5;
	const TYPE_INDIRECTION = 6;
	const TYPE_FICHIER = 7;
	const TYPE_COMPOSITE = 8;

	protected $id = 0;
	protected $objet_parent = null;
	protected $noeud = null;
	protected $attributs = array();
	protected $liste_noms_objets = array();
	protected $liste_objets = array();
	protected $avec_valeur = true;
	protected $valeur;
	
	/* Constructeur */
	public function __construct($id, &$objet_parent, &$noeud = null) {
		$this->id = $id;
		$this->objet_parent = $objet_parent;
		$this->noeud = $noeud;
	}
	
	/* Gestion des objets : déclaration d'un objet fils */
	protected function declarer_objet($nom_classe) {
		$nom_balise = $nom_classe::NOM_BALISE;
		$this->liste_noms_objets[$nom_classe] = $nom_balise;
		$this->liste_objets[$nom_classe] = array();
	}

	/* Gestion des objets : instanciation d'un objet fils */
	public function instancier_nouveau($nom_classe) {
		if (isset($this->liste_objets[$nom_classe])) {
			$objet = new $nom_classe(1 + count($this->liste_objets[$nom_classe]), $this);
			return $objet;
		}
		else {
			die("ERREUR : Instanciation d'un objet inexistant");
		}
	}
	/* Gestion des objets : construction d'un nom pour une nouvelle instance */
	public function construire_nouveau_nom() {
		if (!(isset($this->attributs[self::NOM_ATTRIBUT_NOM]))) {
			$attribut_nom = _PREFIXE_ID_OBJET.(static::NOM_BALISE)."_".($this->lire_id());
			$this->attributs[self::NOM_ATTRIBUT_NOM] = $attribut_nom;
		}
	}

	/* Méthodes abstraites */
	// abstract public function construire_nouveau();
	abstract public function afficher($mode);
	abstract public function ecrire_xml($niveau);
	
	/* Accesseurs */
	public function lire_id() {return $this->id;}
	public function lire_id_parent() {return $this->objet_parent->lire_id();}	
	
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
		$source_page = pl3_outil_racine_page::Get();
		$ret = $source_page->parser_balise(static::NOM_FICHE, $this, $nom_balise, $this->noeud);
		return $ret;
	}
	public function parser_balise_fille($nom_balise, $unique = true) {
		$source_page = pl3_outil_racine_page::Get();
		$tab_ret = $source_page->parser_balise_fille(static::NOM_FICHE, $this, get_called_class(), $nom_balise, $this->noeud);
		if ($unique) {
			$nb_ret = (int) count($tab_ret);
			$ret = ($nb_ret > 0)?$tab_ret[$nb_ret - 1]:null;
		}
		else {
			$ret = $tab_ret;
		}
		return $ret;
	}
	public function parser_attributs(&$dom_element) {
		$attributs = $this->get_liste_attributs();
		foreach($attributs as $attribut) {
			$nom_attribut = $attribut["nom"];
			$avec_attribut = $dom_element->hasAttribute($nom_attribut);
			if ($avec_attribut) {
				$valeur_attribut = $dom_element->getAttribute($nom_attribut);
				$this->set_attribut($nom_attribut, $valeur_attribut);
			}
		}
	}
	public function parser_valeur(&$dom_element) {
		$balise_avec_valeur = $this->avec_valeur();
		if ($balise_avec_valeur) {
			$valeur = $dom_element->nodeValue;
			$this->set_valeur($valeur);
		}
	}

	
	/* Gestion de la valeur */
	public function avec_valeur() {return $this->avec_valeur;}
	public function set_valeur($valeur) {
		$ret = false;
		if ($this->avec_valeur) {
			$valeur = htmlspecialchars($valeur, ENT_QUOTES, "UTF-8");
			if ($this->valeur != $valeur) {
				$this->valeur = $valeur;
				$ret = true;
			}
		}
		return $ret;
	}
	public function get_valeur() {return $this->valeur;}
	public function get_nom_valeur() {return static::$Balise["nom"];}
	public function get_type_valeur() {return static::$Balise["type"];}
	public function get_reference_valeur() {return (isset(static::$Balise["reference"]))?(static::$Balise["reference"]):null;}
	public function get_balise() {return static::$Balise;}
	
	/* Gestion du noeud */
	public function &get_noeud() {return $this->noeud;}
	public function &get_parent() {return $this->objet_parent;}

	/* Gestion des attributs */
	public function get_liste_attributs() {return static::$Liste_attributs;}
	public function set_attribut($nom_attribut, $valeur_attribut) {
		$ret = true;
		if (isset($this->attributs[$nom_attribut])) {
			$ret = ($this->attributs[$nom_attribut] != $valeur_attribut);
		}
		$this->attributs[$nom_attribut] = $valeur_attribut;
		return $ret;
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
	public function get_xml_attribut($nom_attribut) {
		$ret = ($this->has_attribut($nom_attribut))?($nom_attribut."=\"".$this->get_attribut_chaine($nom_attribut))."\"":"";
		return $ret;
	}
	
	/* Affichage des balises XML */
	public function ouvrir_xml($niveau, $liste_attributs = null) {
		$ret = $this->indenter_xml($niveau);
		$ret .= "<".static::NOM_BALISE;
		$ret .= $this->ouvrir_attributs_xml($liste_attributs);
		$ret .= ">\n";
		return $ret;
	}
	public function fermer_xml($niveau) {
		$ret = $this->indenter_xml($niveau);
		$ret .= "</".static::NOM_BALISE.">\n";
		return $ret;
	}
	public function ouvrir_fermer_xml($niveau, $liste_attributs = null) {
		$ret = $this->indenter_xml($niveau);
		$ret .= "<".static::NOM_BALISE;
		$ret .= $this->ouvrir_attributs_xml($liste_attributs);
		if ($this->avec_valeur) {
			$ret .= ">";
			$ret .= $this->valeur;
			$ret .= "</".static::NOM_BALISE;
		}
		else {$ret .= "/";}
		$ret .= ">\n";
		return $ret;
	}
	
	/* Afficher */
	protected function get_html_id() {
		$objet_parent = $this->get_parent();
		$ret = $objet_parent->lire_id_parent()."-".$this->lire_id_parent()."-".$this->lire_id();
		return $this->get_html_name()."-".$ret;
	}
	protected function get_html_name() {
		$nom_classe = get_called_class();
		$nom = str_replace(_PREFIXE_OBJET, "", $nom_classe);
		$nom = str_replace(pl3_fiche_page::NOM_FICHE."_", "", $nom);
		return $nom;
	}
	
	/* Recherches */
	public function chercher_objet_classe_par_id($nom_classe, $id) {
		if (isset($this->liste_objets[$nom_classe])) {
			foreach($this->liste_objets[$nom_classe] as $instance) {
				$valeur_id = $instance->lire_id();
				if (!(strcmp($valeur_id, $id))) {return $instance;}
			}
		}
		return null;
	}
	
	/* Méthodes de service pour l'affichage des balises XML */
	protected function indenter_xml($niveau) {
		$ret = str_repeat(" ", 4 * ((int) ($niveau)));
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
		else {die("ERREUR : Appel d'une méthode ".$methode." non définie dans un objet XML"); }
	}

}