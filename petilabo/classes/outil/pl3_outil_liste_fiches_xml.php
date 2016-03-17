<?php

class pl3_outil_liste_fiches_xml {
	public static function Nouvelle_fiche($chemin = _CHEMIN_PAGE_COURANTE) {
		$nom_classe = static::NOM_CLASSE;
		$fiche = new $nom_classe($chemin);
		static::$Liste_fiches[] = $fiche;
		return $fiche;
	}
	
	public static function Chercher_instance_classe_par_attribut($nom_classe, $nom_attribut, $valeur_attribut) {
		foreach (static::$Liste_fiches as $fiche) {
			$instance = $fiche->chercher_objet_classe_par_attribut($nom_classe, $nom_attribut, $valeur_attribut);
			if ($instance != null) {return $instance;}
		}
		return null;
	}
	
	public static function Chercher_instance_balise_par_attribut($nom_balise, $nom_attribut, $valeur_attribut) {
		foreach (static::$Liste_fiches as $fiche) {
			$instance = $fiche->chercher_objet_balise_par_attribut($nom_balise, $nom_attribut, $valeur_attribut);
			if ($instance != null) {return $instance;}
		}
		return null;
	}
}