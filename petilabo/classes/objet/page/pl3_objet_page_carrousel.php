<?php

/**
 * Classe de gestion des carrousels
 */
 
class pl3_objet_page_carrousel extends pl3_outil_objet_simple_xml {
	/* Icone */
	const NOM_ICONE = "fa-files-o";

	/* Fiche */
	const NOM_FICHE = "page";

	/* Balise */
	const NOM_BALISE = "carrousel";
	public static $Balise = array("nom" => self::NOM_VALEUR, "type" => self::TYPE_REFERENCE, "reference" => "pl3_objet_media_galerie");
	
	/* Attributs */
	public static $Liste_attributs = array();
	
	/* Affichage */
	public function afficher($mode) {
		$ret = "";
		$source_page = $this->get_source_page();
		$nom_galerie = $this->get_valeur();
		$galerie = $source_page->chercher_liste_medias_par_nom(pl3_objet_media_galerie::NOM_BALISE, $nom_galerie);
		if ($galerie != null) {
			$ret .= "<div class=\"container_image\">";
			$ret .= $nom_galerie;
			$ret .= "</div>\n";
		}
		return $ret;
	}
}