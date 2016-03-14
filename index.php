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

	echo "<h2>Parsing d'un fichier style.xml</h2>\n";
	$style = pl3_fiche_liste_styles::Nouvelle_fiche();
	$style->charger_xml();
	
	echo "<h3>Méthode ecrire_xml</h3>\n";
	$xml = $style->ecrire_xml();
	echo nl2br($xml);
	
	echo "<h3>Méthode afficher</h3>\n";
	$style->afficher();

	echo "<h2>Parsing d'un fichier media.xml</h2>\n";
	$media = pl3_fiche_liste_medias::Nouvelle_fiche();
	$media->charger_xml();
	
	echo "<h3>Méthode ecrire_xml</h3>\n";
	$xml = $media->ecrire_xml();
	echo nl2br($xml);
	
	echo "<h3>Méthode afficher</h3>\n";
	$media->afficher();

	echo "<h2>Parsing d'un fichier page.xml</h2>\n";
	$page = new pl3_fiche_page();
	$page->charger_xml();
	
	echo "<h3>Méthode ecrire_xml</h3>\n";
	$xml = $page->ecrire_xml();
	echo nl2br($xml);
	
	echo "<h3>Méthode afficher</h3>\n";
	$page->afficher();


	echo "<h2>Après appel aux méthodes de gestion directe de contenu</h2>\n";

	$id_page = $page->lire_id();
	$contenu_1 = new pl3_objet_page_contenu(pl3_fiche_page::NOM_FICHE, $id_page);
	$bloc_1_1 = $contenu_1->creer_bloc(1);
	$bloc_1_2 = $contenu_1->creer_bloc(8);
	$bloc_1_3 = $contenu_1->creer_bloc(1);
	$page->ajouter_contenu($contenu_1);
	
	$contenu_2 = new pl3_objet_page_contenu(pl3_fiche_page::NOM_FICHE, $id_page);
	$bloc_2_1 = $contenu_2->creer_bloc(3);
	$bloc_2_2 = $contenu_2->creer_bloc(5);
	$bloc_2_3 = $contenu_2->creer_bloc(2);
	$bloc_2_4 = $contenu_2->creer_bloc(5);
	$bloc_2_5 = $contenu_2->creer_bloc(1);
	$page->ajouter_contenu($contenu_2);
	
	
	echo "<h3>Méthode ecrire_xml</h3>\n";
	$xml = $page->ecrire_xml();
	echo nl2br($xml);
	
	
	echo "<h3>Méthode afficher</h3>\n";
	$page->afficher();
	?>
	<br/><br/><br/>
	</body>
</html>
