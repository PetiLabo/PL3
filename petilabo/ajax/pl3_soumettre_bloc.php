<?php
define("_CHEMIN_BASE_URL", "../../");
define("_CHEMIN_BASE_RESSOURCES", "../");
require_once(_CHEMIN_BASE_URL."petilabo/pl3_init.php");

/* Initialisations */
$html = "";
$ajax_bloc_maj = false;
$ajax_bloc_valide = pl3_ajax_init::Init_bloc();

/* Traitement des paramètres */
if ($ajax_bloc_valide) {
	$parametres = pl3_admin_post::Post("parametres");
	if (strlen($parametres) > 0) {
		$bloc = pl3_ajax_init::Get_bloc();
		parse_str($parametres, $liste_parametres);
		if (($bloc != null) && (count($liste_parametres) > 0)) {
			$source_page = pl3_ajax_init::Get_source_page();
			foreach ($liste_parametres as $nom_parametre => $valeur_parametre) {
				$attribut_maj = $bloc->set_attribut($nom_parametre, $valeur_parametre);
				$ajax_bloc_maj = $ajax_bloc_maj || $attribut_maj ;
			}
			if ($ajax_bloc_maj) {
				$source_page->enregistrer_xml();
				$contenu = pl3_ajax_init::Get_contenu();
				$contenu->maj_cardinal_et_largeur();
				$html .= $contenu->afficher(_MODE_ADMIN_MAJ_GRILLE);
			}
		}
	}
}

/* Retour JSON de la requête AJAX */
echo json_encode(array("valide" => $ajax_bloc_valide, "maj" => $ajax_bloc_maj, "html" => $html));
