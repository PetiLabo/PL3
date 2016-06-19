<?php
define("_CHEMIN_BASE_URL", "../../");
define("_CHEMIN_BASE_RESSOURCES", "../");
require_once(_CHEMIN_BASE_URL."petilabo/pl3_init.php");

/* Initialisations */
$ajax_contenu_valide = pl3_ajax_init::Init_contenu();

/* Traitement des paramètres */
$html = "";
if ($ajax_contenu_valide) {
	$ajax_contenu_valide = false;
	$source_page = pl3_ajax_init::Get_source_page();
	$contenu = pl3_ajax_init::Get_contenu();
	$id_contenu = $contenu->lire_id();
	$bloc = $contenu->instancier_nouveau("pl3_objet_page_bloc");
	$contenu->ajouter_bloc($bloc);
	$source_page->enregistrer_xml();
	$html .= $contenu->afficher(_MODE_ADMIN_MAJ_GRILLE);
	$ajax_contenu_valide = true;

}

/* Retour JSON de la requête AJAX */
echo json_encode(array("valide" => $ajax_contenu_valide, "html" => $html));
