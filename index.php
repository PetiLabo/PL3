<?php
define("_CHEMIN_PETILABO", "petilabo/");
define("_CHEMIN_XML", "xml/");
require_once("petilabo/pl3_init.php");

/* Fichier style */
pl3_fiche_liste_styles::Nouvelle_fiche(_CHEMIN_XML);
pl3_fiche_liste_styles::Nouvelle_fiche(_CHEMIN_PAGE_COURANTE);
pl3_fiche_liste_styles::Charger_xml();

/* Fichier media */
pl3_fiche_liste_medias::Nouvelle_fiche(_CHEMIN_XML);
pl3_fiche_liste_medias::Nouvelle_fiche(_CHEMIN_PAGE_COURANTE);
pl3_fiche_liste_medias::Charger_xml();

/* Fichier page */
$page = new pl3_fiche_page(_CHEMIN_PAGE_COURANTE);
$page->charger_xml();
/*
$contenu = $page->charger_objet_xml("pl3_objet_page_contenu", 2);
var_dump(get_class($contenu));echo "<br>\n";
echo "ID *".(int) $contenu->lire_id()."*<br>\n";
$bloc = $contenu->chercher_objet_classe_par_id("pl3_objet_page_bloc", 4);
var_dump(get_class($bloc));echo "<br>\n";
echo "ID *".(int) $bloc->lire_id()."*<br>\n";
die();
*/

$page->afficher_head();
$page->ouvrir_body();

echo "<h3>Méthode afficher</h3>\n";
$page->ecrire_body();

echo "<h2>Après appel aux méthodes de gestion directe de contenu</h2>\n";

$contenu_1 = $page->nouvel_objet("pl3_objet_page_contenu");
$bloc_1 = $contenu_1->nouvel_objet("pl3_objet_page_bloc");
$bloc_1->set_attribut_taille(2);
$contenu_1->ajouter_objet($bloc_1);
$bloc_2 = $contenu_1->nouvel_objet("pl3_objet_page_bloc");
$contenu_1->ajouter_objet($bloc_2);
$bloc_3 = $contenu_1->nouvel_objet("pl3_objet_page_bloc");
$bloc_3->set_attribut_taille(5);
$contenu_1->ajouter_objet($bloc_3);
$page->ajouter_objet($contenu_1);

echo "<h3>Méthode afficher</h3>\n";
$page->ecrire_body();

echo "<h3>Méthode ecrire_xml</h3>\n";
echo nl2br($page->ecrire_xml());

$page->fermer_body();
