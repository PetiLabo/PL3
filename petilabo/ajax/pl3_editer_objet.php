<?php
define("_CHEMIN_PETILABO", "../");
define("_CHEMIN_XML", "../../xml/");
require_once _CHEMIN_PETILABO."pl3_init.php";

/* Initialisations */
$html = "";
$ajax_objet_valide = pl3_ajax_init::Init_objet();

/* Traitement des paramètres */
if ($ajax_objet_valide) {
	$objet = pl3_ajax_init::Get_Objet();
	$nom_balise_id = pl3_ajax_init::Get_nom_balise_id();
	$editeur_objet = new pl3_ajax_editeur_objet($objet, $nom_balise_id);
	$html = $editeur_objet->editer();
}

/* Retour JSON de la requête AJAX */
echo json_encode(array("valide" => $ajax_objet_valide, "html" => $html));
