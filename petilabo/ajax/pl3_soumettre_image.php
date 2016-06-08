<?php
define("_CHEMIN_BASE_URL", "../../");
define("_CHEMIN_BASE_RESSOURCES", "../");
require_once(_CHEMIN_BASE_URL."petilabo/pl3_init.php");

/* Initialisations */
$html = "";
$ajax_media_maj = false;
$ajax_media_valide = pl3_ajax_init::Init_media();

/* Traitement des paramètres */
if ($ajax_media_valide) {
	$parametres = pl3_admin_post::Post("parametres");
	if (strlen($parametres) > 0) {
		$media = pl3_ajax_init::Get_media();
		parse_str($parametres, $liste_parametres);
		if (($media != null) && (count($liste_parametres) > 0)) {
			$source_page = pl3_ajax_init::Get_source_page();
			foreach ($liste_parametres as $nom_parametre => $valeur_parametre) {
				if (!(strcmp($nom_parametre, pl3_outil_source_xml::NOM_ATTRIBUT_NOM))) {
					$ajax_media_maj = $ajax_media_maj || $media->set_attribut_nom($valeur_parametre);
				}
				else {
					$type_valeur = $media->lire_element_type_valeur($nom_parametre);
					if ($type_valeur == pl3_outil_objet_xml::TYPE_INDIRECTION) {
						$valeur = $media->lire_element_valeur($nom_parametre);
						$nom_classe = $media->lire_element_reference_valeur($nom_parametre);
						$nom_fiche = $nom_classe::NOM_FICHE;
						$nom_balise = $nom_classe::NOM_BALISE;
						$objet_indirection = $source_page->chercher_liste_fiches_par_nom($nom_fiche, $nom_balise, $valeur);
						if ($objet_indirection) {
							$ajax_media_maj = $ajax_media_maj || $objet_indirection->set_valeur($valeur_parametre);
						}
					}
					else {
						$ajax_media_maj = $ajax_media_maj || $media->ecrire_element_valeur($nom_parametre, $valeur_parametre);
					}
				}
			}
			$source_page->enregistrer_xml();
			$fiche_media = pl3_ajax_init::Get_fiche_media();
			$html = "<!-- ".$parametres." -->\n".$fiche_media->afficher_vignette_media($media); // $media->afficher(_MODE_ADMIN_MEDIA);
		}
	}
}

/* Retour JSON de la requête AJAX */
echo json_encode(array("valide" => $ajax_media_valide, "maj" => $ajax_media_maj, "html" => $html));
