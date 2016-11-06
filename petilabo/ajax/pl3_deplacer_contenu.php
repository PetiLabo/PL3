<?php
define("_CHEMIN_BASE_URL", "../../");
require_once(_CHEMIN_BASE_URL."petilabo/pl3_init.php");

/* Initialisations */
$ajax_contenu_valide = pl3_ajax_init::Init_page();

/* Traitement des paramètres */
$msg = "";
if ($ajax_contenu_valide) {
	$ajax_contenu_valide = false;
	$source_page = pl3_ajax_init::Get_source_page();
	$page = $source_page->get_page();
	$page->charger_xml();
	$tab_ordre = pl3_admin_post::Array_post("tab_ordre");
	$nb_tab_ordre = count($tab_ordre);
	$nb_contenus = $page->lire_nb_contenus();
	if ($nb_tab_ordre == $nb_contenus) {
		$ajax_contenu_valide = true;
		$page->reordonner($tab_ordre);
		$page->enregistrer_xml();
	}
}

/* Retour JSON de la requête AJAX */
echo json_encode(array("valide" => $ajax_contenu_valide, "msg" => $msg));
