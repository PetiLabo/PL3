<?php
define("_CHEMIN_BASE_URL", "../../");
define("_CHEMIN_BASE_RESSOURCES", "../");
require_once(_CHEMIN_BASE_URL."petilabo/pl3_init.php");

/* Initialisations */
$ajax_bloc_valide = pl3_ajax_init::Init_bloc();

/* Traitement des paramètres */
$contenu_id = 0;
$html = "";
if ($ajax_bloc_valide) {
	$source_page = pl3_ajax_init::Get_source_page();
	$contenu = pl3_ajax_init::Get_contenu();
	$contenu_id = $contenu->lire_id();
	$bloc = pl3_ajax_init::Get_bloc();
	$bloc_id = $bloc->lire_id();
	$contenu->retirer_bloc($bloc_id);
	$source_page->enregistrer_xml();
	$html .= $contenu->afficher(_MODE_ADMIN_MAJ_GRILLE);
}

/* Retour JSON de la requête AJAX */
echo json_encode(array("valide" => $ajax_bloc_valide, "contenu_id" => $contenu_id, "html" => $html));
