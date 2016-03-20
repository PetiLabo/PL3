<?php
define("_CHEMIN_PETILABO", "../");
define("_CHEMIN_XML", "../../xml/");
require_once "pl3_fonctions_ajax.php";
require_once _CHEMIN_PETILABO."pl3_init.php";

/* Récupération des paramètres post */
$nom_page = post("nom_page");
$balise_id = post("balise_id");
$nom_balise = post("nom_balise");

/* Validation des paramètres post */
$edit_objet_valide = false;
if ((strlen($nom_page) > 0) && (strlen($balise_id) > 0) && (strlen($nom_balise) > 0)) {
	$chemin_page = _CHEMIN_PAGES_XML.$nom_page."/";
	$fichier_page = (pl3_fiche_page::NOM_FICHE)._SUFFIXE_XML;
	if (@file_exists($chemin_page.$fichier_page)) {
		$liste_id = explode("-", $balise_id);
		if (count($liste_id) == 3) {
			list($contenu_param, $bloc_param, $objet_param) = $liste_id;
			$contenu_id = (int) $contenu_param;
			$bloc_id = (int) $bloc_param;
			$objet_id = (int) $objet_param;
			if (($contenu_id > 0) && ($bloc_id > 0) && ($objet_id > 0)) {
				define("_PAGE_COURANTE", $nom_page);
				define("_CHEMIN_PAGE_COURANTE", $chemin_page);
				$edit_objet_valide = true;
			}
		}
	}
}

/* Chargement des objets XML en fonction des paramètres */
if ($edit_objet_valide) {
	$edit_objet_valide = false;
	$page = new pl3_fiche_page(_CHEMIN_PAGE_COURANTE);
	$contenu = $page->charger_objet_xml("pl3_objet_page_contenu", $contenu_id);
	if ($contenu != null) {
		$bloc = $contenu->chercher_objet_classe_par_id("pl3_objet_page_bloc", $bloc_id);
		if ($bloc != null) {
			$objet = $bloc->chercher_objet_par_id($objet_id);
			if ($objet != null) {$edit_objet_valide = true;}
		}
	}
}

/* Traitement de l'édition des objets */
$html = "";
if ($edit_objet_valide) {
	$html .= afficher_ligne_xml($objet);
	$html .= afficher_valeur($objet);
	$html .= afficher_attributs($objet);
}


/* Retour JSON de la requête AJAX */
echo json_encode(array("valide" => $edit_objet_valide, "html" => $html));
