<?php

/**
 * Classe de gestion des objets simples
 */
 
abstract class pl3_outil_objet_simple_xml extends pl3_outil_objet_xml {
	public function ecrire_xml($niveau) {
		$attr = array();
		foreach (static::$Liste_attributs as $attribut) {
			$attr[] = $this->get_xml_attribut($attribut["nom"]);
		}
		$xml = $this->ouvrir_fermer_xml($niveau, $attr);
		return $xml;
	}
}