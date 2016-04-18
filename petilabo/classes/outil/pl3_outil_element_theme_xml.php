<?php

/**
 * Classe de gestion des éléments d'un objet composite de la fiche theme
 */
 
abstract class pl3_outil_element_theme_xml extends pl3_outil_objet_xml {
	/* Fiche */
	const NOM_FICHE = "theme";
	
	/* Balise */
	public static $Balise = null;

	/* Attributs */
	public static $Liste_attributs = array();

	/* Constructeur */
	public function __construct($id, &$objet_parent, &$noeud = null) {
		self::$Balise = array("nom" => static::NOM_BALISE, "type" => static::TYPE_BALISE);
		parent::__construct($id, $objet_parent, $noeud);
	}

	/* Méthodes */
	public function ecrire_xml($niveau) {
		$xml = $this->ouvrir_fermer_xml($niveau);
		return $xml;
	}
	
	public function afficher($mode) {
		$ret = static::PROPRIETE_CSS.":".$this->get_valeur().";";
		return $ret;
	}
}