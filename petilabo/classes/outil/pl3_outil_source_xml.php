<?php

/**
 * Classe de gestion des sources XML
 */
 
abstract class pl3_outil_source_xml {
	protected $source_page;
	
	protected function __construct(&$source_page) {
		$this->source_page = $source_page;
	}
}