<?php
define("_CHEMIN_BASE_URL", "../../");
define("_CHEMIN_BASE_RESSOURCES", "../");
require_once(_CHEMIN_BASE_URL."petilabo/pl3_init.php");

$html = "";
$nom_theme = "";
$ajax_msg_erreur = "";
$ajax_theme_valide = false;
$nom_champ_post = "zip-theme";

$nom_page_courante = pl3_admin_post::Post("nom_page_courante");
define("_PAGE_COURANTE", $nom_page_courante);
define("_CHEMIN_PAGE_COURANTE", _CHEMIN_PAGES_XML.$nom_page_courante."/");

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
			$archive = new pl3_admin_zip($fichier_temporaire);
			$nom_theme = $archive->lire_racine_commune();
			if ((strlen($nom_theme) > 0) && (strcmp($nom_theme, "."))) {
				$chemin_theme = _CHEMIN_THEMES_XML.$nom_theme;
				if (!(@is_dir($chemin_theme))) {			
					@mkdir($chemin_theme);
					$archive->copier_dossier($nom_theme, _CHEMIN_THEMES_XML);
					$archive->copier_dossier($nom_theme."/images", _CHEMIN_THEMES_XML);
					/* Déplacement du dossier images */
					$chemin_images = _CHEMIN_THEMES_XML.$nom_theme."/images";
					if (is_dir($chemin_images)) {@rename($chemin_images, _CHEMIN_THEMES_IMAGES.$nom_theme);}
					$ajax_theme_valide = true;
				}
				else {
					$ajax_msg_erreur = "ERREUR : Le thème ".$nom_theme." est déjà installé !";
				}
			}
			else {
				$ajax_msg_erreur = "ERREUR : L'archive ZIP est erronée.";
			}
		}
		else {
			$ajax_msg_erreur = "ERREUR : Le fichier du thème n'est pas une archive ZIP !";
		}					
	}
}
else {
	$ajax_msg_erreur = "ERREUR : Le fichier du thème est introuvable";
}

/* Génération du HTML */
if ($ajax_theme_valide) {
	$source_site = pl3_outil_source_site::Get();
	$site = $source_site->get_site();
	$html = $site->ecrire_liste_vignettes_theme();
}

/* Retour JSON de la requête AJAX */
echo json_encode(array("valide" => $ajax_theme_valide, "msg" => $ajax_msg_erreur, "theme" => $nom_theme, "html" => $html));
