<?php
define("_CHEMIN_BASE_URL", "../../");
require_once(_CHEMIN_BASE_URL."petilabo/pl3_init.php");

/* Initialisations */
$ret = "";
$ajax_galerie_valide = pl3_ajax_init::Init_galerie();

/* Traitement des paramètres */
if ($ajax_galerie_valide) {
	$fiche_media = pl3_ajax_init::Get_fiche_media();
	$tab_elements = pl3_admin_post::Array_post("tab_elements");
	$galerie = pl3_ajax_init::Get_galerie();
	$tab_nom_elements = array();
	foreach($tab_elements as $media_id) {
		$element = is_null($fiche_media)?null:$fiche_media->chercher_objet_classe_par_id("pl3_objet_media_image", $media_id);
		if ($element) {
			$tab_nom_elements[] = $element->get_attribut_nom();
			$ret .= $element->get_attribut_nom()." ";
		}
		
	}
	$galerie->set_elements($tab_nom_elements);
	$fiche_media->enregistrer_xml();
}

/* Retour JSON de la requête AJAX */
echo json_encode(array("valide" => $ajax_galerie_valide, "ret" => $ret));
