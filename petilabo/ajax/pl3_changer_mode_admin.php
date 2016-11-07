<?php
define("_CHEMIN_BASE_URL", "../../");
require_once(_CHEMIN_BASE_URL."petilabo/pl3_init.php");

/* Ouverture de la session */
$ajax_valide = false;
$id_session = pl3_admin_session::Ouvrir_session();
if (strlen($id_session) > 0) {
	$mode_actuel = pl3_admin_mode::Lire_mode();

	/* Initialisations */
	$mode_admin = pl3_admin_post::Post("mode_admin");
	if ($mode_admin) {
		$nouveau_mode = (int) $mode_admin;
		if (($nouveau_mode > _MODE_NORMAL) && ($nouveau_mode <= _MODE_ADMIN_SITE_MEDIA) && ($nouveau_mode != $mode_actuel)) {
			pl3_admin_mode::Ecrire_mode($nouveau_mode);
			$ajax_valide = true;
		}
	}
}

/* Retour JSON de la requête AJAX */
echo json_encode(array("valide" => $ajax_valide));
