<?php
define("_CHEMIN_PETILABO", "../");
define("_CHEMIN_XML", "../../xml/");
require_once _CHEMIN_PETILABO."pl3_init.php";

/* Initialisations */
$html = "";
$ajax_objet_valide = pl3_ajax_init::Init();

/* Traitement des paramètres */
if ($ajax_objet_valide) {
	$parametres = pl3_ajax_post::Post("parametres");
	if (strlen($parametres) > 0) {
		parse_str($parametres, $liste_parametres);
		foreach ($liste_parametres as $nom_parametre => $valeur_parametre) {
			$html .= $nom_parametre."=".$valeur_parametre."\n";
		}
	}
}

/* Retour JSON de la requête AJAX */
echo json_encode(array("valide" => $ajax_objet_valide, "html" => $html));
