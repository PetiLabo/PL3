<?php

function post($name) {
	$ret = null;
	if (strlen($name) > 0) {
		if (isset($_POST[$name])) {
			$param = $_POST[$name];
			if (strlen($param) > 0) {
				$ret = nettoyer_param($param);
			}
		}
	}
	return $ret;
}

function array_post($name) {
	$ret = array();
	if (strlen($name) > 0) {
		if (isset($_POST[$name])) {
			$array = $_POST[$name];
			foreach ($array as $elem) {
				$ret[] = nettoyer_param($elem);
			}
		}
	}
	return $ret;
}

/**
 * Fonction de nettoyage des paramètres GET et POST
 *
 * @portée	publique
 * @param	paramètre à nettoyer
 * @retour	paramètre nettoyé
 */
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