<?php
define("_CHEMIN_PETILABO", "../");
define("_CHEMIN_XML", "../../xml/");
require_once _CHEMIN_PETILABO."pl3_init.php";
require_once _CHEMIN_AJAX."pl3_ajax_init.php";

/* Traitement de l'édition des objets */
$html = "";
if ($ajax_objet_valide) {
	$editeur_objet = new pl3_ajax_editeur_objet($objet, $nom_balise."-".$balise_id);
	$html = $editeur_objet->editer();
}


/* Retour JSON de la requête AJAX */
echo json_encode(array("valide" => $ajax_objet_valide, "html" => $html));
