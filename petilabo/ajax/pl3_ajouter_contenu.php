<?php
define("_CHEMIN_BASE_URL", "../../");
define("_CHEMIN_BASE_RESSOURCES", "../");
require_once(_CHEMIN_BASE_URL."petilabo/pl3_init.php");

/* Initialisations */
$ajax_contenu_valide = pl3_ajax_init::Init_page();

/* Traitement des paramètres */
$html = "";
if ($ajax_contenu_valide) {
	$ajax_contenu_valide = false;
	$source_page = pl3_ajax_init::Get_source_page();
	$page = $source_page->get_page();
	$page->charger_xml();
	$contenu = $page->instancier_nouveau("pl3_objet_page_contenu");
	$page->ajouter_contenu($contenu);
	$page->enregistrer_xml();
	$html .= $contenu->afficher(_MODE_ADMIN_GRILLE);
	$ajax_contenu_valide = true;

}

/* Retour JSON de la requête AJAX */
echo json_encode(array("valide" => $ajax_contenu_valide, "html" => $html));
