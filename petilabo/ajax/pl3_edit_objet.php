<?php
define("_CHEMIN_PETILABO", "../");
define("_CHEMIN_XML", "../../xml/");
require_once "pl3_fonctions_ajax.php";
require_once _CHEMIN_PETILABO."pl3_init.php";

$contenu_id = post("contenu_id");
$bloc_id = post("bloc_id");
$balise_id = post("balise_id");
$nom_balise = post("nom_balise");

if ((strlen($contenu_id) > 0) && (strlen($bloc_id) > 0) && (strlen($balise_id) > 0) && (strlen($nom_balise) > 0)) {
	echo $contenu_id." ".$bloc_id." ".$nom_balise." ".$balise_id;
}