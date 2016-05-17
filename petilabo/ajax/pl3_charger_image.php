<?php
header('Content-type: application/json');

define("_CHEMIN_BASE_URL", "../../");
define("_CHEMIN_BASE_RESSOURCES", "../");
require_once(_CHEMIN_BASE_URL."petilabo/pl3_init.php");

function nettoyer_nom_fichier($nom_fichier) {
	$ret = trim(strtolower($nom_fichier));
	$ret = str_replace(" ", "-", $ret);
	$ret = str_replace(".jpeg", ".jpg", $ret);
	return $ret;
}
$ret_media = false;
$retour_valide = false;
$index_taille = (int) (isset($_POST["taille"])?$_POST["taille"]:0);
	
/* Chargement de la fiche média locale */
$nom_page = isset($_POST["page"])?$_POST["page"]:_PAGE_PRINCIPALE;
define("_PAGE_COURANTE", $nom_page);
define("_CHEMIN_PAGE_COURANTE", _CHEMIN_PAGES_XML.$nom_page."/");
$fiche_media = new pl3_fiche_media(_CHEMIN_PAGE_COURANTE, 1);
$retour_valide = $fiche_media->charger_xml();
	
$nom_champ_post = "img-".$index_taille;
if (isset($_FILES[$nom_champ_post])) {
	$fichier_temporaire = $_FILES[$nom_champ_post]["tmp_name"];
	if ($retour_valide) {
		$nom_origine = $_FILES[$nom_champ_post]["name"];
		$nom_destination = nettoyer_nom_fichier($nom_origine);
		$cible = _CHEMIN_XML."images/".$nom_destination;
		$retour_valide = move_uploaded_file($fichier_temporaire, $cible);
	}
	else {
		@unlink($fichier_temporaire);
	}
}
echo json_encode(array("code" => $retour_valide, "url" => _CHEMIN_IMAGES_XML.$nom_destination));