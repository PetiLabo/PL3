<?php
define("_CHEMIN_BASE_URL", "../../");
require_once(_CHEMIN_BASE_URL."petilabo/pl3_init.php");

/* Initialisations */
$html = "";
$ajax_galerie_valide = pl3_ajax_init::Init_galerie();

/* Traitement des paramètres */
if ($ajax_galerie_valide) {
	$galerie = pl3_ajax_init::Get_galerie();
	$galerie_id = $galerie->lire_id();
	$editeur_galerie = new pl3_admin_editeur_galerie($galerie, "editeur_type_galerie", $galerie_id);
	$fiche_media = pl3_ajax_init::Get_fiche_media();
	$editeur_galerie->set_fiche_media($fiche_media);
	$html = $editeur_galerie->editer();
}

/* Retour JSON de la requête AJAX */
echo json_encode(array("valide" => $ajax_galerie_valide, "html" => $html));
