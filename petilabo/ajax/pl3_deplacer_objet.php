<?php
define("_CHEMIN_PETILABO", "../");
define("_CHEMIN_XML", "../../xml/");
require_once _CHEMIN_PETILABO."pl3_init.php";

/* Initialisations */
$ajax_objet_valide = pl3_ajax_init::Init_bloc();

/* Traitement des paramètres */
if ($ajax_objet_valide) {
	$ajax_objet_valide = false;
	$bloc = pl3_ajax_init::Get_bloc();
	$tab_ordre = pl3_ajax_post::Array_post("tab_ordre");
	$nb_tab_ordre = count($tab_ordre);
	$nb_blocs = $bloc->lire_nb_objets();
	if ($nb_tab_ordre == $nb_blocs) {
		$ajax_objet_valide = true;
		$bloc->reordonner($tab_ordre);
		$page = pl3_ajax_init::Get_page();
		$page->enregistrer_xml();
	}
}

/* Retour JSON de la requête AJAX */
echo json_encode(array("valide" => $ajax_objet_valide));
