<?php

/**
 * Classe de gestion des sources XML
 */
 
abstract class pl3_outil_source_xml {
	const NOM_ATTRIBUT_NOM = "nom";
	protected $source_page;
	
	protected function __construct(&$source_page) {
		$this->source_page = $source_page;
	}
	
	public function lire_source_page() {return $this->source_page;}
}