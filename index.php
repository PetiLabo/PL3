<?php
define("_CHEMIN_BASE_URL", "./");
require_once(_CHEMIN_BASE_URL."petilabo/pl3_init.php");

$source_page = new pl3_outil_source_page();
$source_page->charger_xml();
$html = $source_page->afficher(_MODE_NORMAL);
echo $html;
