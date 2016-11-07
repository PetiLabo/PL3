<?php

/**
 * Classe de gestion de l'interface d'administration
 */
 
class pl3_admin_interface {
	/* Singleton */
	private static $Admin_interface = null;
	
	/* Propriétés */
	private $mode_actuel;

	/* Constructeur privé */
	private function __construct() {
		$this->mode_actuel = pl3_admin_mode::Lire_mode();
	}
	public static function &Get() {
		if (is_null(self::$Admin_interface)) {
			self::$Admin_interface = new pl3_admin_interface();
		}
		return self::$Admin_interface;
	}

	/* Accesseur */
	public function lire_mode_actuel() {return $this->mode_actuel;}

	/* Génération du code HTML pour la barre d'outils */
	public function ecrire_barre_outils($mode) {
		$html = "";
		$html .= "<p class=\"admin_barre_outils\">";
		$html .= $this->ecrire_logo_barre_outils();
		if ($mode < _MODE_ADMIN_SITE_GENERAL) {
			$html .= $this->ecrire_item_barre_outils_site(_MODE_ADMIN_SITE_GENERAL, "fa-arrow-up", "Site");
			$html .= $this->ecrire_item_barre_outils_page(_MODE_ADMIN_PAGE, "fa-file-text", "Page");
			$html .= $this->ecrire_item_barre_outils_page(_MODE_ADMIN_MEDIA, "fa-picture-o", "Media");
			$html .= $this->ecrire_item_barre_outils_page(_MODE_ADMIN_GRILLE, "fa-th", "Grille");
			$html .= $this->ecrire_item_barre_outils_page(_MODE_ADMIN_OBJETS, "fa-puzzle-piece", "Objets");
			$html .= $this->ecrire_item_barre_outils_page(_MODE_ADMIN_XML, "fa-code", "XML");
		}
		else {
			$html .= $this->ecrire_item_barre_outils_page(_MODE_ADMIN_PAGE, "fa-arrow-down", "Page");
			$html .= $this->ecrire_item_barre_outils_site(_MODE_ADMIN_SITE_GENERAL, "fa-list", "Général");
			$html .= $this->ecrire_item_barre_outils_site(_MODE_ADMIN_SITE_THEMES, "fa-paint-brush", "Thèmes");
			$html .= $this->ecrire_item_barre_outils_site(_MODE_ADMIN_SITE_EXTENSIONS, "fa-cogs", "Extensions");
			$html .= $this->ecrire_item_barre_outils_site(_MODE_ADMIN_SITE_MEDIA, "fa-picture-o", "Media");
		}
		$html .= "</p>\n";

		return $html;
	}
	
	private function ecrire_logo_barre_outils() {
		$html = "";
		$html .= "<a class=\"admin_item_logo\">";
		$html .= "<span class=\"fa fa-flask admin_item_icone\"></span>";
		$html .= "</a>";
		return $html;
	}
	
	private function ecrire_item_barre_outils_page($mode, $icone, $label) {
		$html = "";
		$href = ($mode == $this->mode_actuel)?"":" href=\"#\"";
		$classe = " class=\"".(($mode == $this->mode_actuel)?"admin_item_mode_page_actuel":"admin_item_barre_outils")."\"";
		$html .= "<a id=\"admin-mode-".$mode."\"".$classe.$href.">";
		$html .= "<span class=\"fa ".$icone." admin_item_icone\"></span>";
		$html .= "<span class=\"admin_item_label\">".$label."</span>";
		$html .= "</a>";
		return $html;
	}
	
	private function ecrire_item_barre_outils_site($mode, $icone, $label) {
		$html = "";
		$href = ($mode == $this->mode_actuel)?"":" href=\"#\"";
		$classe = " class=\"".(($mode == $this->mode_actuel)?"admin_item_mode_site_actuel":"admin_item_barre_outils")."\"";
		$html .= "<a id=\"admin-mode-".$mode."\"".$classe.$href.">";
		$html .= "<span class=\"fa ".$icone." admin_item_icone\"></span>";
		$html .= "<span class=\"admin_item_label\">".$label."</span>";
		$html .= "</a>";
		return $html;
	}
}