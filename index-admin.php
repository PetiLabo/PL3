<?php
define("_CHEMIN_BASE_URL", "./");
define("_CHEMIN_BASE_FICHIER", "../");
require_once(_CHEMIN_BASE_URL."petilabo/pl3_init.php");

$source_page = pl3_outil_source_page::Get();
$source_page->charger_xml();

$html = $source_page->afficher(_MODE_ADMIN);
echo $html;