<?php

/**
 * Classe de gestion des sources XML
 */
 
abstract class pl3_outil_source_xml {
	const NOM_ATTRIBUT_NOM = "nom";
	
	public function &get_source_page() {return pl3_outil_source_page::Get();}
}