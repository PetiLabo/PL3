<?php
/* Initialisations */
$memoire_avant = memory_get_usage() / 1024;
define("_CHEMIN_BASE_URL", "./");
define("_CHEMIN_BASE_FICHIER", "../");
require_once(_CHEMIN_BASE_URL."petilabo/pl3_init.php");

/* Ouverture de la session */
$id_session = pl3_admin_session::Ouvrir_session();
if (strlen($id_session) == 0) {die("ERREUR : Impossible d'ouvrir la session");}
$mode_admin = pl3_admin_mode::Lire_mode();

/* Chargement de la page et du thème */
$source_page = pl3_outil_source_page::Get();
$source_page->charger_xml();
$source_page->generer_theme($mode_admin);

/* Constitution du code HTML */
$html = "";
$page = $source_page->get_page();
$page->set_mode($mode_admin);
$html .= $page->afficher_head();
$html .= $page->ouvrir_body();
$html .= "<p class=\"admin_barre_outils\">";
$html .= "<a id=\"admin-mode-"._MODE_ADMIN_PAGE."\" class=\"admin_item_barre_outils\" href=\"#\">Page</a>";
$html .= "<a id=\"admin-mode-"._MODE_ADMIN_MEDIA."\" class=\"admin_item_barre_outils\" href=\"#\">Media</a>";
$html .= "<a id=\"admin-mode-"._MODE_ADMIN_GRILLE."\" class=\"admin_item_barre_outils\" href=\"#\">Grille</a>";
$html .= "<a id=\"admin-mode-"._MODE_ADMIN_OBJETS."\" class=\"admin_item_barre_outils\" href=\"#\">Objets</a>";
$html .= "<a id=\"admin-mode-"._MODE_ADMIN_XML."\" class=\"admin_item_barre_outils\" href=\"#\">XML</a>";
$html .= "</p>\n";
$html .= $page->ecrire_body();
$html .= $page->fermer_body();

/* Affichage */
echo $html;

$memoire_apres = memory_get_usage() / 1024;
printf("<br><b>Avant</b> : %.2f ko<br>",$memoire_avant);
printf("<b>Après</b> : %.2f ko<br>",$memoire_apres);
printf("<b>Mémoire utilisée</b> : %.2f ko<br>",$memoire_apres - $memoire_avant);
