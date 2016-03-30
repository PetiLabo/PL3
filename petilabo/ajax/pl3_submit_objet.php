<?php
define("_CHEMIN_PETILABO", "../");
define("_CHEMIN_XML", "../../xml/");
require_once _CHEMIN_PETILABO."pl3_init.php";

/* Initialisations */
$html = "";
$ajax_objet_maj = false;
$ajax_objet_valide = pl3_ajax_init::Init();

/* Traitement des paramètres */
if ($ajax_objet_valide) {
	$parametres = pl3_ajax_post::Post("parametres");
	if (strlen($parametres) > 0) {
		$objet = pl3_ajax_init::Get_objet();
		parse_str($parametres, $liste_parametres);
		if (count($liste_parametres) > 0) {
			foreach ($liste_parametres as $nom_parametre => $valeur_parametre) {
				$attribut_maj = $objet->set_attribut($nom_parametre, $valeur_parametre);
				$ajax_objet_maj = $ajax_objet_maj || $attribut_maj ;
			}
			if ($ajax_objet_maj) {
				$bloc = pl3_ajax_init::Get_bloc();
				$bloc->remplacer_objet($objet);
				$page = pl3_ajax_init::Get_page();
				$page->enregistrer_xml();
				$html .= $objet->afficher();
			}
		}
	}
}

/* Retour JSON de la requête AJAX */
echo json_encode(array("valide" => $ajax_objet_valide, "maj" => $ajax_objet_maj, "html" => $html));
