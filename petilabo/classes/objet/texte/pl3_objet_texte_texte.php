<?php

/**
 * Classe de gestion des textes
 */

class pl3_objet_texte_texte extends pl3_outil_objet_xml {
	const NOM_FICHE  = "texte";
	const NOM_BALISE = "texte";
	const TYPE       = self::TYPE_CHAINE;
	const ATTRIBUTS  = array(array("nom" => "nom", "type" => self::TYPE_CHAINE));

	/* Valeur par défaut */
	protected $valeur = "[...]";

	/* Affichage */
	public function afficher($mode) {
		echo "<!-- Texte -->\n";
	}
}