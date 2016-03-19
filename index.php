<!doctype html>
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="fr" lang="fr" dir="ltr">
	<head>
		<meta http-equiv="content-type" content="text/html; charset=UTF-8" />
		<meta name="viewport" content="width=device-width,initial-scale=1" />
		<title>PL3</title>
		<link rel="stylesheet" type="text/css" href="petilabo/css/pl3.css" />
	</head>
	<body>

	<?php
	require_once("petilabo/pl3_init.php");

	echo "<h1>OXM PetiLabo V3</h1>\n";

	/* Fichier style */
	$style = pl3_fiche_liste_styles::Nouvelle_fiche();
	$style->charger_xml();
	
	/* Fichier media */
	$media = pl3_fiche_liste_medias::Nouvelle_fiche();
	$media->charger_xml();

	/* Fichier page */
	$page = new pl3_fiche_page();
	$page->charger_xml();
	
	echo "<h3>Méthode afficher</h3>\n";
	$page->afficher();

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
	$page->afficher();
	?>
	<br/><br/><br/>
	</body>
</html>
