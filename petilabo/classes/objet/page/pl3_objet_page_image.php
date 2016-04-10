<?php

/**
 * Classe de gestion des images
 */
 
class pl3_objet_page_image extends pl3_outil_objet_xml {
	/* Fiche */
	const NOM_FICHE = "page";

	/* Balise */
	const NOM_BALISE = "image";
	public static $Balise = array("nom" => self::NOM_VALEUR, "type" => self::TYPE_REFERENCE, "reference" => "pl3_objet_media_image");
	
	/* Attributs */
	public static $Liste_attributs = array();

	/* Méthodes */
	public function ecrire_xml($niveau) {
		$xml = $this->ouvrir_fermer_xml($niveau);
		return $xml;
	}
	
	public function afficher($mode) {
		$ret = "";
		$nom_image = $this->get_valeur();
		$image = $this->source_page->chercher_liste_medias_par_nom(pl3_objet_media_image::NOM_BALISE, $nom_image);
		if ($image != null) {
			$fichier = $image->get_valeur_fichier();
			$alt = $image->get_valeur_alt();
			$html_id = $this->get_html_id();
			$ret .= "<div class=\"container_image\">";
			$ret .= "<img id=\"".$html_id."\" class=\"image objet_editable\" src=\""._CHEMIN_IMAGES_XML.$fichier."\" alt=\"".$alt."\" />";
			$ret .= "</div>\n";
		}
		return $ret;
	}
}