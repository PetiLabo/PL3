<?php
define("_CHEMIN_BASE_URL", "./");
require_once(_CHEMIN_BASE_URL."petilabo/pl3_init.php");

pl3_outil_racine_page::Init();

$source_page = pl3_outil_racine_page::Get();
$source_page->charger_xml();

$html = $source_page->afficher(_MODE_NORMAL);
echo $html;