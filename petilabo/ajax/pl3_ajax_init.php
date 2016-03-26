<?php

/* Récupération du nom de la page */
$ajax_objet_valide = false;
$nom_page = pl3_ajax_post::Post("nom_page");
if (strlen($nom_page) > 0) {
	$chemin_page = _CHEMIN_PAGES_XML.$nom_page."/";
	$fichier_page = (pl3_fiche_page::NOM_FICHE)._SUFFIXE_XML;
	define("_PAGE_COURANTE", $nom_page);
	define("_CHEMIN_PAGE_COURANTE", $chemin_page);
	$ajax_objet_valide = @file_exists($chemin_page.$fichier_page);
}

/* Récupération de la balise et de son id */
if ($ajax_objet_valide) {
	$ajax_objet_valide = false;
	$balise_id = pl3_ajax_post::Post("balise_id");
	$nom_balise = pl3_ajax_post::Post("nom_balise");
	if ((strlen($balise_id) > 0) && (strlen($nom_balise) > 0)) {
		$liste_id = explode("-", $balise_id);
		if (count($liste_id) == 3) {
			list($contenu_param, $bloc_param, $objet_param) = $liste_id;
			$contenu_id = (int) $contenu_param;
			$bloc_id = (int) $bloc_param;
			$objet_id = (int) $objet_param;
			$ajax_objet_valide = (($contenu_id > 0) && ($bloc_id > 0) && ($objet_id > 0));
		}
	}
}

/* Chargement des objets XML en fonction des paramètres */
if ($ajax_objet_valide) {
	$ajax_objet_valide = false;
	$page = new pl3_fiche_page(_CHEMIN_PAGE_COURANTE);
	$contenu = $page->charger_objet_xml("pl3_objet_page_contenu", $contenu_id);
	if ($contenu != null) {
		$bloc = $contenu->chercher_objet_classe_par_id("pl3_objet_page_bloc", $bloc_id);
		if ($bloc != null) {
			$objet = $bloc->chercher_objet_par_id($objet_id);
			if ($objet != null) {$ajax_objet_valide = true;}
		}
	}
}
