<?php
define("_CHEMIN_BASE_URL", "../../");
define("_CHEMIN_BASE_RESSOURCES", "../");
require_once(_CHEMIN_BASE_URL."petilabo/pl3_init.php");

$html = "";
$ajax_msg_erreur = "";
$ajax_page_valide = true;
$nom_page = pl3_admin_post::Post("nom_page");
$nom_page_courante = pl3_admin_post::Post("nom_page_courante");
define("_PAGE_COURANTE", $nom_page_courante);

$source_site = pl3_outil_source_site::Get();
$site = $source_site->get_site();
$liste_pages = $site->lire_liste_pages();

$nom_page_ok = strtolower($nom_page);
$nom_page_ok = preg_replace('/[^\\pL0-9]+/u', '-', $nom_page_ok);
$nom_page_ok = trim($nom_page_ok, "-");
$nom_page_ok = iconv("utf-8", "us-ascii//TRANSLIT", $nom_page_ok);
$nom_page_ok = preg_replace('/[^-a-z0-9]+/i', '', $nom_page_ok);

if (strcmp($nom_page_ok, $nom_page)) {
	$ajax_page_valide = false;
	$ajax_msg_erreur = "Le nom de la page contient des caractères non valides (suggestion : ".$nom_page_ok.").";
}
else {
	foreach($liste_pages as $page) {
		if (!(strcmp($page["nom"], $nom_page))) {
			$ajax_page_valide = false;
			$ajax_msg_erreur = "Le nom de la page est déjà utilisé sur le site.";
			break;
		}
	}
	
	if ($ajax_page_valide) {
		$ajax_page_valide = $site->ajouter_page($nom_page_ok);
		if ($ajax_page_valide) {
			$html = $site->ecrire_liste_vignettes_page();
		}
		else {
			$ajax_msg_erreur = "La création de la page ".$nom_page_ok." a échoué.";
		}
	}
}

/* Retour JSON de la requête AJAX */
echo json_encode(array("valide" => $ajax_page_valide, "msg" => $ajax_msg_erreur, "html" => $html));
