<?php

/* Définition de la version PetiLabo */
define("_VERSION_PETILABO", "3.0.0");

/* Définitions complémentaires */
if (!defined("_CHEMIN_BASE_FICHIER")) {define("_CHEMIN_BASE_FICHIER", _CHEMIN_BASE_URL);}

/* Chemins PetiLabo */
define("_CHEMIN_PETILABO", _CHEMIN_BASE_URL."petilabo/");
define("_CHEMIN_CLASSES", _CHEMIN_PETILABO."classes/");
define("_CHEMIN_OBJET", _CHEMIN_CLASSES."objet/");

/* Chemins ressources */
define("_CHEMIN_RESSOURCES", _CHEMIN_BASE_FICHIER."petilabo/");
define("_CHEMIN_AJAX", _CHEMIN_RESSOURCES."ajax/");
define("_CHEMIN_CSS", _CHEMIN_RESSOURCES."css/");
define("_CHEMIN_JS", _CHEMIN_RESSOURCES."js/");
define("_CHEMIN_TIERS", _CHEMIN_RESSOURCES."tiers/");

/* Chemins XML */
define("_CHEMIN_XML", _CHEMIN_BASE_URL."xml/");
define("_CHEMIN_RESSOURCES_XML", _CHEMIN_BASE_FICHIER."xml/");
define("_CHEMIN_PAGES_XML", _CHEMIN_XML."pages/");
define("_CHEMIN_IMAGES_XML", _CHEMIN_RESSOURCES_XML."images/");

/* Préfixes */
define("_PREFIXE_PETILABO", "pl3_");
define("_PREFIXE_FICHE", _PREFIXE_PETILABO."fiche_");
define("_PREFIXE_OBJET", _PREFIXE_PETILABO."objet_");
define("_PREFIXE_ID_OBJET", "id_");

/* Suffixes */
define("_SUFFIXE_PHP", ".php");
define("_SUFFIXE_XML", ".xml");

/* Page principale */
define("_PAGE_PRINCIPALE", "index");
define("_PAGE_PRINCIPALE_ADMIN", "index-admin");

/* Modes PetiLabo */
define("_MODE_NORMAL", 0);
define("_MODE_ADMIN", 1);

/* Sources PetiLabo */
define("_NOM_SOURCE_GLOBAL", "global");
define("_NOM_SOURCE_LOCAL", "local");

/* Gestion des erreurs */
error_reporting(E_ALL);

/* Récupération du nom de la page (sauf requête AJAX) */
if (isset($_SERVER["PHP_SELF"])) {
	$php_self = htmlentities(trim($_SERVER["PHP_SELF"]));
	$nom_php_en_cours = basename($php_self);
	if (strstr($php_self, "/ajax/") === false) {
		if (isset($_GET["p"])) {
			$nom_get_en_cours = htmlentities(basename(trim($_GET["p"])));
			if (!(strcmp($nom_php_en_cours, _PAGE_PRINCIPALE._SUFFIXE_PHP))) {
				if (strlen($nom_get_en_cours) == 0) {$nom_page_en_cours = _PAGE_PRINCIPALE;}
				else {$nom_page_en_cours = str_replace(_SUFFIXE_PHP, "", $nom_get_en_cours);}
			}
			else if (!(strcmp($nom_php_en_cours, _PAGE_PRINCIPALE_ADMIN._SUFFIXE_PHP))) {
				if (strlen($nom_get_en_cours) == 0) {$nom_page_en_cours = _PAGE_PRINCIPALE_ADMIN;}
				else {$nom_page_en_cours = str_replace(_SUFFIXE_PHP, "", $nom_get_en_cours);}
			}
			else {
				die("ERREUR : Page XML introuvable");
			}
		}
		else {
			die("ERREUR : Page XML introuvable");
		}
		/* Définition des constantes pour la page courante */
		define("_PAGE_COURANTE", $nom_page_en_cours);
		define("_CHEMIN_PAGE_COURANTE", _CHEMIN_PAGES_XML.$nom_page_en_cours."/");		
	}
}
else {
	die("ERREUR : Page XML introuvable");
}

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
	else if (!(strcmp($categorie, "ajax_"))) {
		$chemin = _CHEMIN_AJAX;
	}
	$fichier = $chemin.$nom_classe.".php";
    if (@file_exists($fichier)) {
        @require_once($fichier);
    }
	else {
		die("ERREUR : Impossible de charger le fichier ".$fichier);
	}
}

// Activation de l'autoload
spl_autoload_register("autochargement");