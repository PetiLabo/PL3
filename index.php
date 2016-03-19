<?php
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
$page = new pl3_fiche_page();
$page->charger_xml();

$page->afficher_head();
$page->ouvrir_body();
echo "<h3>Méthode afficher</h3>\n";
$page->ecrire_body();

echo "<h2>Après appel aux méthodes de gestion directe de contenu</h2>\n";

$id_page = $page->lire_id();
$contenu_1 = new pl3_objet_page_contenu(pl3_fiche_page::NOM_FICHE, $id_page);
$id_contenu_1 = $contenu_1->lire_id();
$bloc_1 = new pl3_objet_page_bloc(pl3_fiche_page::NOM_FICHE, $id_contenu_1);
// $bloc_1->set_attribut(pl3_objet_page_bloc::NOM_ATTRIBUT_TAILLE, 2);
$bloc_1->set_attribut_taille(2);
$contenu_1->ajouter_objet($bloc_1);
$bloc_2 = new pl3_objet_page_bloc(pl3_fiche_page::NOM_FICHE, $id_contenu_1);
$contenu_1->ajouter_objet($bloc_2);
$bloc_3 = new pl3_objet_page_bloc(pl3_fiche_page::NOM_FICHE, $id_contenu_1);
// $bloc_3->set_attribut(pl3_objet_page_bloc::NOM_ATTRIBUT_TAILLE, 5);
$bloc_3->set_attribut_taille(5);
$contenu_1->ajouter_objet($bloc_3);
$page->ajouter_objet($contenu_1);

echo "<h3>Méthode afficher</h3>\n";
$page->ecrire_body();

echo "<h3>Méthode ecrire_xml</h3>\n";
echo nl2br($page->ecrire_xml());

$page->fermer_body();
