<?php

class pl3_outil_liste_fiches_xml extends pl3_outil_source_xml {
	private $nom_classe_fiche;
	private $liste_fiches;
	
	public function __construct(&$source_page, $nom_classe_fiche) {
		$this->nom_classe_fiche = $nom_classe_fiche;
		parent::__construct($source_page);
	}
	
	public function ajouter_source($nom_source, $chemin_source) {
		$id_fiche = 1 + count($this->liste_fiches);
		$this->liste_fiches[$nom_source] = new $this->nom_classe_fiche($this->source_page, $chemin_source, $id_fiche);
		return $id_fiche;
	}
	
	public function charger_xml() {
		foreach ($this->liste_fiches as $nom_source => $fiche) {
			$fiche->charger_xml();
		}
	}
	
	public function chercher_instance_balise_par_attribut($nom_balise, $nom_attribut, $valeur_attribut) {
		foreach ($this->liste_fiches as $fiche) {
			$instance = $fiche->chercher_objet_balise_par_attribut($nom_balise, $nom_attribut, $valeur_attribut);
			if ($instance != null) {return $instance;}
		}
		return null;
	}
	
	public function chercher_instance_balise_par_nom($nom_balise, $valeur) {
		return $this->chercher_instance_balise_par_attribut($nom_balise, self::NOM_ATTRIBUT_NOM, $valeur);
	}
}