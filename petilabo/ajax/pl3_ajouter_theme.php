<?php
define("_CHEMIN_BASE_URL", "../../");
define("_CHEMIN_BASE_RESSOURCES", "../");
require_once(_CHEMIN_BASE_URL."petilabo/pl3_init.php");

$html = "";
$ajax_msg_erreur = "";
$ajax_theme_valide = false;
$nom_champ_post = "zip-theme";

if (isset($_FILES[$nom_champ_post])) {
	/* Traitement du $_FILES en post */
	$fichier_post = new pl3_admin_telechargement($_FILES[$nom_champ_post]);
	$ajax_theme_valide = $fichier_post->controle_post($ajax_msg_erreur);
	if ($ajax_theme_valide) {
		$ajax_theme_valide = false;
		$fichier_temporaire = $fichier_post->get_tmp_name();
		$fichier_cible = $fichier_post->get_name();
		$extension_cible = strtolower(pathinfo($fichier_cible, PATHINFO_EXTENSION));
		if (!(strcmp($extension_cible, "zip"))) {
			$ajax_theme_valide = true;
			$html = $extension_cible;
		}
		else {
			$ajax_msg_erreur = "ERREUR : Le fichier du thème n'est pas une archive ZIP !";
		}					
	}
}
else {
	$ajax_msg_erreur = "ERREUR : Le fichier du thème est introuvable";
}

/* Retour JSON de la requête AJAX */
echo json_encode(array("valide" => $ajax_theme_valide, "msg" => $ajax_msg_erreur, "html" => $html));
