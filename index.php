<?php
define("_CHEMIN_BASE_URL", "./");
require_once(_CHEMIN_BASE_URL."petilabo/pl3_init.php");

$source_page = new pl3_outil_source_page();
$source_page->charger_xml();

/* Exemple de création d'un nouvel objet avec indirection
$paragraphe = $source_page->instancier_nouveau("pl3_objet_page_paragraphe", 1, 1);
$paragraphe->construire_nouveau();
$source_page->enregistrer_nouveau($paragraphe, 1, 1);
*/

$html = $source_page->afficher(_MODE_NORMAL);
echo $html;
