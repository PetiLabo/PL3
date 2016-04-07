<?php
define("_CHEMIN_PETILABO", "petilabo/");
define("_CHEMIN_XML", "xml/");
require_once("petilabo/pl3_init.php");

$source_page = new pl3_outil_source_page();
$source_page->charger_xml();
$html = $source_page->afficher(_MODE_NORMAL);
echo $html;
