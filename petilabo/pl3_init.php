<?php

/* Définition de la version PetiLabo */
define("_VERSION_PETILABO", "3.0.0");

/* Chemins */
define("_CHEMIN_PETILABO", "petilabo/");
define("_CHEMIN_CLASSES", _CHEMIN_PETILABO."classes/");
define("_CHEMIN_OBJET", _CHEMIN_CLASSES."objet/");

/* Préfixes */
define("_PREFIXE_PETILABO", "pl3_");
define("_PREFIXE_OBJET", _PREFIXE_PETILABO."objet_");

/* Gestion des erreurs */
error_reporting(E_ALL);

/**
 * Fonction d'autoload
 */
function autochargement($nom_classe) {

	/* Toutes les classes doivent commencer par pl3_ */
	$prefixe = strtolower(substr($nom_classe, 0, 4));
	if (strcmp($prefixe, "pl3_")) {
		die("Erreur : la classe ".$nom_classe." porte un préfixe incorrect");
	}

	$categorie = strtolower(substr($nom_classe, 4, 5));
	$chemin = _CHEMIN_CLASSES.$categorie."/";
	if (!(strcmp($categorie, "objet"))) {
		$pos_fiche = strpos($nom_classe, "_", 10);
		if ($pos_fiche !== false) {
			$fiche = substr($nom_classe, 10, $pos_fiche - 10);
			$chemin .= $fiche."/";
		}
		else {
			die("Erreur : la classe objet ".$nom_classe." porte sur une fiche inconnue");
		}
	}
	$fichier = $chemin.$nom_classe.".php";
    if (@file_exists($fichier)) {
        @require_once($fichier);
    }
	else {
		die("Impossible de charger le fichier ".$fichier);
	}
}

// Activation de l'autoload
spl_autoload_register("autochargement");