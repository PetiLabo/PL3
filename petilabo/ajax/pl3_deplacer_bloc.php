<?php
define("_CHEMIN_BASE_URL", "../../");
require_once(_CHEMIN_BASE_URL."petilabo/pl3_init.php");

/* Initialisations */
$ajax_contenu_valide = pl3_ajax_init::Init_contenu();

/* Traitement des paramètres */
if ($ajax_contenu_valide) {
	$ajax_contenu_valide = false;
	$source_page = pl3_ajax_init::Get_source_page();
	$contenu = pl3_ajax_init::Get_contenu();
	$tab_ordre = pl3_admin_post::Array_post("tab_ordre");
	$nb_tab_ordre = count($tab_ordre);
	$nb_blocs = $contenu->lire_nb_blocs();
	if ($nb_tab_ordre == $nb_blocs) {
		$ajax_contenu_valide = true;
		$contenu->reordonner($tab_ordre);
		$source_page->enregistrer_xml();
	}
}

/* Retour JSON de la requête AJAX */
echo json_encode(array("valide" => $ajax_contenu_valide));
