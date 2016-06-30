<?php
define("_CHEMIN_BASE_URL", "../../");
define("_CHEMIN_BASE_RESSOURCES", "../");
require_once(_CHEMIN_BASE_URL."petilabo/pl3_init.php");

/* Initialisations */
$html = "";
$ajax_contenu_maj = false;
$ajax_contenu_valide = pl3_ajax_init::Init_contenu();

/* Traitement des paramètres */
if ($ajax_contenu_valide) {
	$parametres = pl3_admin_post::Post("parametres");
	if (strlen($parametres) > 0) {
		$contenu = pl3_ajax_init::Get_contenu();
		parse_str($parametres, $liste_parametres);
		if (($contenu != null) && (count($liste_parametres) > 0)) {
			$source_page = pl3_ajax_init::Get_source_page();
			foreach ($liste_parametres as $nom_parametre => $valeur_parametre) {
				$attribut_maj = $contenu->set_attribut($nom_parametre, $valeur_parametre);
				$ajax_contenu_maj = $ajax_contenu_maj || $attribut_maj ;
			}
			if ($ajax_contenu_maj) {
				$source_page->enregistrer_xml();
				$contenu->maj_cardinal_et_largeur();
				$html .= $contenu->afficher(_MODE_ADMIN_GRILLE);
			}
		}
	}
}

/* Retour JSON de la requête AJAX */
echo json_encode(array("valide" => $ajax_contenu_valide, "maj" => $ajax_contenu_maj, "html" => $html));
