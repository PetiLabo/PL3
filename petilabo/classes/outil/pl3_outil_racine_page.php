<?php

/**
 * Classe de gestion d'une racine de page
 */

class pl3_outil_racine_page {
	private static $Source_page = null;

	public static function Init() {
		self::$Source_page = new pl3_outil_source_page();
	}
	
	public static function Get() {return self::$Source_page;}
}