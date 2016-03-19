<?php

/* Définition de la version PetiLabo */
define("_VERSION_PETILABO", "3.0.0");

/* Chemins PetiLabo */
define("_CHEMIN_PETILABO", "petilabo/");
define("_CHEMIN_CLASSES", _CHEMIN_PETILABO."classes/");
define("_CHEMIN_OBJET", _CHEMIN_CLASSES."objet/");
define("_CHEMIN_CSS", _CHEMIN_PETILABO."css/");
define("_CHEMIN_JS", _CHEMIN_PETILABO."js/");

/* Chemins XML */
define("_CHEMIN_XML", "xml/");
define("_CHEMIN_PAGES_XML", _CHEMIN_XML."pages/");
define("_CHEMIN_IMAGES_XML", _CHEMIN_XML."images/");

/* Préfixes */
define("_PREFIXE_PETILABO", "pl3_");
define("_PREFIXE_OBJET", _PREFIXE_PETILABO."objet_");

/* Suffixes */
define("_SUFFIXE_PHP", ".php");
define("_SUFFIXE_XML", ".xml");

/* Page principale */
define("_PAGE_PRINCIPALE", "index.php");

/* Gestion des erreurs */
error_reporting(E_ALL);

/* Récupération du nom de la page */
if (isset($_SERVER["PHP_SELF"]) && isset($_GET["p"])) {
	$php_self = htmlentities(trim($_SERVER["PHP_SELF"]));
	$nom_php_en_cours = basename($php_self);
	if (!(strcmp($nom_php_en_cours, _PAGE_PRINCIPALE))) {
		$nom_get_en_cours = htmlentities(trim($_GET["p"]));
		if (strlen($nom_get_en_cours) == 0) {$nom_get_en_cours = _PAGE_PRINCIPALE;}
		$nom_page_en_cours = str_replace(_SUFFIXE_PHP, "", $nom_get_en_cours);
	}
	else {
		die("ERREUR : Page XML introuvable");
	}
}
else {die("ERREUR : Page XML introuvable");}

define("_CHEMIN_PAGE_COURANTE", _CHEMIN_PAGES_XML.$nom_page_en_cours."/");

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