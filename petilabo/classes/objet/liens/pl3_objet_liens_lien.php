<?php

class pl3_objet_liens_lien_url extends pl3_outil_objet_xml {
	/* Fiche */
	const NOM_FICHE = "liens";

	/* Balise */
	const NOM_BALISE = "url";
	public static $Balise = array("nom" => self::NOM_BALISE, "type" => self::TYPE_CHAINE);
	
	/* Attributs */
	public static $Liste_attributs = array();

	/* Méthodes */
	public function ecrire_xml($niveau) {
		$xml = $this->ouvrir_fermer_xml($niveau);
		return $xml;
	}
	
	public function afficher($mode) {
		$valeur_url = html_entity_decode($this->get_valeur(), ENT_QUOTES, "UTF-8");
		$ret = " href=\"".$valeur_url."\"";
		return $ret;
	}
}

class pl3_objet_liens_lien_ancre extends pl3_outil_objet_xml {
	/* Fiche */
	const NOM_FICHE = "liens";

	/* Balise */
	const NOM_BALISE = "ancre";
	public static $Balise = array("nom" => self::NOM_BALISE, "type" => self::TYPE_INDIRECTION, "reference" => "pl3_objet_texte_texte");
	
	/* Attributs */
	public static $Liste_attributs = array();
	
	/* Méthodes */
	public function construire_nouveau() {
		/* Création d'une instance de texte */
		$nom_ancre = null;
		$source_page = $this->get_source_page();
		$objet_texte = $source_page->instancier_nouveau(self::$Balise["reference"]);
		if ($objet_texte) {
			$source_page->enregistrer_nouveau($objet_texte);
			$nom_ancre = $objet_texte->get_attribut_nom();
		}
		return $nom_ancre;
	}
	
	public function detruire() {
		$source_page = $this->get_source_page();
		$nom_ancre = $this->get_valeur();
		$ancre = $source_page->chercher_liste_textes_par_nom(pl3_objet_texte_texte::NOM_BALISE, $nom_ancre);
		if ($ancre != null) {$source_page->supprimer($ancre);}
	}

	public function ecrire_xml($niveau) {
		$xml = $this->ouvrir_fermer_xml($niveau);
		return $xml;
	}
	
	public function afficher($mode) {
		$source_page = $this->get_source_page();
		$nom_ancre = $this->get_valeur();
		if (strlen($nom_ancre) > 0) {
			$texte_ancre = $source_page->chercher_liste_textes_par_nom(pl3_objet_texte_texte::NOM_BALISE, $nom_ancre);
			if ($texte_ancre != null) {$nom_ancre = $texte_ancre->get_valeur();}
		}
		return $nom_ancre;
	}
}

class pl3_objet_liens_lien_bulle extends pl3_outil_objet_xml {
	/* Fiche */
	const NOM_FICHE = "liens";

	/* Balise */
	const NOM_BALISE = "bulle";
	public static $Balise = array("nom" => self::NOM_BALISE, "type" => self::TYPE_INDIRECTION, "reference" => "pl3_objet_texte_texte");
	
	/* Attributs */
	public static $Liste_attributs = array();
	
	/* Méthodes */
	public function construire_nouveau() {
		/* Création d'une instance de texte */
		$nom_bulle = null;
		$source_page = $this->get_source_page();
		$objet_texte = $source_page->instancier_nouveau(self::$Balise["reference"]);
		if ($objet_texte) {
			$source_page->enregistrer_nouveau($objet_texte);
			$nom_bulle = $objet_texte->get_attribut_nom();
		}
		return $nom_bulle;
	}
	
	public function detruire() {
		$source_page = $this->get_source_page();
		$nom_bulle = $this->get_valeur();
		$bulle = $source_page->chercher_liste_textes_par_nom(pl3_objet_texte_texte::NOM_BALISE, $nom_bulle);
		if ($bulle != null) {$source_page->supprimer($bulle);}
	}

	public function ecrire_xml($niveau) {
		$xml = $this->ouvrir_fermer_xml($niveau);
		return $xml;
	}
	
	public function afficher($mode) {
		$source_page = $this->get_source_page();
		$nom_bulle = $this->get_valeur();
		if (strlen($nom_bulle) > 0) {
			$texte_bulle = $source_page->chercher_liste_textes_par_nom(pl3_objet_texte_texte::NOM_BALISE, $nom_bulle);
			if ($texte_bulle != null) {$nom_bulle = $texte_bulle->get_valeur();}
		}
		$ret = (strlen($nom_bulle) > 0)?" title=\"".$nom_bulle."\"":"";
		return $ret;
	}
}

class pl3_objet_liens_lien_externe extends pl3_outil_objet_xml {
	/* Fiche */
	const NOM_FICHE = "liens";

	/* Balise */
	const NOM_BALISE = "externe";
	public static $Balise = array("nom" => self::NOM_BALISE, "type" => self::TYPE_BOOLEEN);
	
	/* Attributs */
	public static $Liste_attributs = array();
	
	/* Méthodes */
	public function ecrire_xml($niveau) {
		$xml = $this->ouvrir_fermer_xml($niveau);
		return $xml;
	}
	
	public function afficher($mode) {
		$ret = " target=\"".$this->get_target()."\"";
		return $ret;
	}
	
	/* Accesseurs */
	public function get_target() {
		$valeur = $this->get_valeur();
		$ret = (strcmp($valeur, self::VALEUR_BOOLEEN_VRAI))?"_top":"_blank";
		return $ret;
	}
}

/**
 * Classe de gestion des liens
 */
class pl3_objet_liens_lien extends pl3_outil_objet_composite_xml {
	/* Fiche */
	const NOM_FICHE = "liens";

	/* Balise */
	const NOM_BALISE = "lien";
	public static $Balise = array("nom" => self::NOM_BALISE, "type" => self::TYPE_COMPOSITE);
	
	/* Attributs */
	public static $Liste_attributs = array(
		array("nom" => self::NOM_ATTRIBUT_NOM, "type" => self::TYPE_CHAINE));
	
	/* Méthodes */
	public function __construct($id, &$parent, &$noeud = null) {
		$this->declarer_element("pl3_objet_liens_lien_url");
		$this->declarer_element("pl3_objet_liens_lien_ancre");
		$this->declarer_element("pl3_objet_liens_lien_bulle");
		$this->declarer_element("pl3_objet_liens_lien_externe");
		parent::__construct($id, $parent, $noeud);
	}
	public function construire_nouveau() {
		$ancre = new pl3_objet_liens_lien_ancre(1, $this);
		$nom_ancre = $ancre->construire_nouveau();
		if (strlen($nom_ancre) > 0) {
			$this->ajouter_element_xml($ancre);
			$this->set_valeur_ancre($nom_ancre);
		}
		$bulle = new pl3_objet_liens_lien_bulle(1, $this);
		$nom_bulle = $bulle->construire_nouveau();
		if (strlen($nom_bulle) > 0) {
			$this->ajouter_element_xml($bulle);
			$this->set_valeur_ancre($nom_bulle);
		}
	}
	/* Destruction */
	public function detruire() {
		$ancre = $this->get_element(pl3_objet_liens_lien_ancre::NOM_BALISE);
		if ($ancre) {$ancre->detruire();}
		$bulle = $this->get_element(pl3_objet_liens_lien_bulle::NOM_BALISE);
		if ($bulle) {$bulle->detruire();}
	}

	public function charger_xml() {
		$this->charger_elements_xml();
	}

	public function ecrire_xml($niveau) {
		$attr_nom = $this->get_xml_attribut(self::NOM_ATTRIBUT_NOM);
		$xml = $this->ouvrir_xml($niveau, array($attr_nom));
		$xml .= $this->ecrire_elements_xml(1 + $niveau);
		$xml .= $this->fermer_xml($niveau);
		return $xml;
	}
	
	public function afficher($mode) {
		$ret = "";
		$url = $this->get_element(pl3_objet_liens_lien_url::NOM_BALISE);
		$ret .= (is_null($url))?"":$url->afficher($mode);
		$bulle = $this->get_element(pl3_objet_liens_lien_bulle::NOM_BALISE);
		$ret .= (is_null($bulle))?"":$bulle->afficher($mode);
		$ret .= " target=\"".$this->get_valeur_target()."\"";		
		return $ret;
	}
	
	public function get_valeur_target() {
		$externe = $this->get_element(pl3_objet_liens_lien_externe::NOM_BALISE);
		$ret = (is_null($externe))?"_top":$externe->get_target();
		return $ret;
	}
}