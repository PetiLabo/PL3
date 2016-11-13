<?php
define("_CHEMIN_BASE_URL", "../../");
define("_CHEMIN_BASE_RESSOURCES", "../");
require_once(_CHEMIN_BASE_URL."petilabo/pl3_init.php");

$html = "";
$ajax_msg_erreur = "";
$nom_page = pl3_admin_post::Post("nom_page");
$nom_page_courante = pl3_admin_post::Post("nom_page_courante");
define("_PAGE_COURANTE", $nom_page_courante);

$source_site = pl3_outil_source_site::Get();
$site = $source_site->get_site();
$ajax_page_valide = $site->supprimer_page($nom_page);
if ($ajax_page_valide) {
	$html = $site->ecrire_liste_vignettes_page();
}
else {
	$ajax_msg_erreur = "La suppression de la page ".$nom_page." a échoué.";
}

/* Retour JSON de la requête AJAX */
echo json_encode(array("valide" => $ajax_page_valide, "msg" => $ajax_msg_erreur, "html" => $html));
