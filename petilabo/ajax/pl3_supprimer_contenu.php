<?php
define("_CHEMIN_BASE_URL", "../../");
define("_CHEMIN_BASE_RESSOURCES", "../");
require_once(_CHEMIN_BASE_URL."petilabo/pl3_init.php");

/* Initialisations */
$ajax_contenu_valide = pl3_ajax_init::Init_contenu();

/* Traitement des paramètres */
if ($ajax_contenu_valide) {
	$source_page = pl3_ajax_init::Get_source_page();
	$contenu = pl3_ajax_init::Get_contenu();
	$contenu_id = $contenu->lire_id();
	$page = $source_page->get_page();
	$page->retirer_contenu($contenu_id);
	$source_page->enregistrer_xml();
}

/* Retour JSON de la requête AJAX */
echo json_encode(array("valide" => $ajax_contenu_valide));
