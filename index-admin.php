<?php
define("_CHEMIN_PETILABO", "petilabo/");
define("_CHEMIN_RESSOURCES", "../"._CHEMIN_PETILABO);
define("_CHEMIN_XML", "xml/");
define("_CHEMIN_RESSOURCES_XML", "../"._CHEMIN_XML);
require_once("petilabo/pl3_init.php");

$source_page = new pl3_outil_source_page();
$source_page->charger_xml();
$html = $source_page->afficher(_MODE_ADMIN);
echo $html;
