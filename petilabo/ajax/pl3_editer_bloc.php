<?php
define("_CHEMIN_BASE_URL", "../../");
require_once(_CHEMIN_BASE_URL."petilabo/pl3_init.php");

/* Initialisations */
$html = "";
$ajax_objet_valide = pl3_ajax_init::Init_bloc();

/* Traitement des paramètres */
if ($ajax_objet_valide) {
	$bloc = pl3_ajax_init::Get_bloc();
	$bloc_id = pl3_ajax_init::Get_bloc_id();
	$editeur_bloc = new pl3_admin_editeur_objet($bloc, $bloc_id);
	$html = $editeur_bloc->editer();
}

/* Retour JSON de la requête AJAX */
echo json_encode(array("valide" => $ajax_objet_valide, "html" => $html));
