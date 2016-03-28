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

echo $page->afficher_head();
echo $page->ouvrir_body();

echo "<h1>Tests PL3</h1>\n";

echo "<h2>Méthode afficher</h2>\n";

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

echo $page->ecrire_body();

/*
echo "<h2>Méthode ecrire_xml</h2>\n";
echo nl2br($page->ecrire_xml());
*/
echo $page->fermer_body();
