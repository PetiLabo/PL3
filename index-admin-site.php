<?php
/* Initialisations */
define("_CHEMIN_BASE_URL", "./");
define("_CHEMIN_BASE_FICHIER", "../");
require_once(_CHEMIN_BASE_URL."petilabo/pl3_init.php");

/* Ouverture de la session */
$id_session = pl3_admin_session::Ouvrir_session();
if (strlen($id_session) == 0) {die("ERREUR : Impossible d'ouvrir la session");}
$admin_interface = pl3_admin_interface::Get();
$mode_admin = $admin_interface->lire_mode_actuel();

/* Chargement du site */
$source_site = pl3_outil_source_site::Get();
$source_site->charger_xml();

/* Récupération des éléments pour affichage */
$source_site->set_mode($mode_admin);

/* Constitution du code HTML */
$html = "";
$html .= $source_site->afficher_head();
$html .= $source_site->ouvrir_body();
$html .= $admin_interface->ecrire_barre_outils($mode_admin);
$html .= $source_site->ecrire_body();
$html .= $source_site->fermer_body();

/* Affichage */
echo $html;
