<?php
define("_CHEMIN_PETILABO", "petilabo/");
define("_CHEMIN_XML", "xml/");
require_once("petilabo/pl3_init.php");

echo "Page courante : "._PAGE_COURANTE."<br>\n";
echo "Chemin courant : "._CHEMIN_PAGE_COURANTE."<br>\n";
echo "<br/>\n";

/* Fichier page */
$page = new pl3_fiche_page(_CHEMIN_PAGE_COURANTE);
$page->charger_xml();
echo nl2br(str_replace(" ","&nbsp;", htmlentities($page->ecrire_xml(0))));
