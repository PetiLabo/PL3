<?php

/* Fonctions d'affichage pour l'éditeur d'objet */
function afficher_ligne_xml(&$objet) {
	$ret = "<p class=\"editeur_objet_titre_xml\">Ligne XML&nbsp;:</p>\n";
	$ret .= "<p class=\"editeur_objet_ligne_xml\">".$objet->ecrire_xml(0)."</p>\n";
	return $ret;
}

function afficher_valeur(&$objet) {
	$ret = "";
	if ($objet->avec_valeur()) {
		$valeur = $objet->get_valeur();
		$ret .= "<p class=\"editeur_objet_titre_valeur\">Valeur&nbsp;:</p>\n";
		$ret .= "<p class=\"editeur_objet_valeur\">".$valeur."</p>\n";
	}
	return $ret;
}

function afficher_attributs(&$objet) {
	$ret = "";
	$nom_classe = get_class($objet);
	$liste_attributs = $nom_classe::$Liste_attributs;
	if (count($liste_attributs) > 0) {
		$ret .= "<p class=\"editeur_objet_titre_attributs\">Attributs&nbsp;:</p>\n";
		$ret .= "<ul class=\"editeur_objet_liste_attributs\">\n";
		foreach ($liste_attributs as $attribut) {
			$nom_attribut = $attribut["nom"];
			$nom_type = type_attribut_to_nom_type($attribut);
			$nom_valeur = valeur_attribut_to_nom_valeur($objet, $nom_attribut);
			$ret .= "<li>".$nom_attribut." [".$nom_type."] : ".$nom_valeur."</li>\n";
		}
		$ret .= "</ul>\n";
	}
	return $ret;
}
function type_attribut_to_nom_type(&$attribut) {
	$type_attribut = $attribut["type"];
	switch($type_attribut) {
		case pl3_outil_objet_xml::TYPE_ENTIER:
			$ret = "entier";break;
		case pl3_outil_objet_xml::TYPE_CHAINE:
			$ret = "chaîne de caractères";break;
		case pl3_outil_objet_xml::TYPE_ICONE:
			$ret = "icone";break;
		case pl3_outil_objet_xml::TYPE_REFERENCE:
			if (isset($attribut["reference"])) {
				$nom_classe = $attribut["reference"];
				$nom_balise = $nom_classe::NOM_BALISE;
				$ret = "référence à un objet ".$nom_balise;
			}
			else {
				$ret = "ERREUR : Référence à un objet inconnu";
			}
			break;
		case pl3_outil_objet_xml::TYPE_FICHIER:
			$ret = "fichier";break;
		default:
			$ret = "ERREUR : Type d'attribut inexistant";
	}
	return $ret;
}
function valeur_attribut_to_nom_valeur(&$objet, $nom_attribut) {
	$has_attribut = $objet->has_attribut($nom_attribut);
	if ($has_attribut) {
		$ret = $objet->get_attribut_chaine($nom_attribut);
	}
	else {
		$ret = "Non renseigné";
	}
	return $ret;
}

/* Fonctions d'accès aux paramètres post */
function post($nom_param) {
	$ret = null;
	if (strlen($nom_param) > 0) {
		if (isset($_POST[$nom_param])) {
			$param = $_POST[$nom_param];
			if (strlen($param) > 0) {
				$ret = nettoyer_param($param);
			}
		}
	}
	return $ret;
}
function array_post($nom_param) {
	$ret = array();
	if (strlen($nom_param) > 0) {
		if (isset($_POST[$nom_param])) {
			$array = $_POST[$nom_param];
			foreach ($array as $elem) {
				$ret[] = nettoyer_param($elem);
			}
		}
	}
	return $ret;
}
function nettoyer_param($str) {
	if (!is_null($str)) {
		// Protection contre le null byte poisonning
		$str = str_replace("\0", '', $str);
		// Traitement des magic quotes
		if (get_magic_quotes_gpc()) {$str = stripslashes($str);}
		// Suppression des espaces à gauche et à droite
		$str = trim($str);
	}
	return $str;
}