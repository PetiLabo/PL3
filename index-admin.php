<?php
$memoire_avant = memory_get_usage() / 1024;
define("_CHEMIN_BASE_URL", "./");
define("_CHEMIN_BASE_FICHIER", "../");
require_once(_CHEMIN_BASE_URL."petilabo/pl3_init.php");

$source_page = pl3_outil_source_page::Get();
$source_page->charger_xml();

$html = $source_page->afficher(_MODE_ADMIN);
echo $html;

$memoire_apres = memory_get_usage() / 1024;
printf("<br><b>Avant</b> : %.2f ko<br>",$memoire_avant);
printf("<b>Après</b> : %.2f ko<br>",$memoire_apres);
printf("<b>Mémoire utilisée</b> : %.2f ko<br>",$memoire_apres - $memoire_avant);
