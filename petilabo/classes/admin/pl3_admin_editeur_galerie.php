<?php

/**
 * Classe de gestion de l'éditeur de galeries
 */

class pl3_admin_editeur_galerie extends pl3_admin_editeur {
	private $fiche_media = null;

	/* Fonctions d'édition */
	public function editer() {
		$galerie = $this->objet;
		$liste_medias = is_null($this->fiche_media)?array():$this->fiche_media->get_liste_objets("pl3_objet_media_image");
		$liste_noms_elements = $galerie->get_elements();
		$liste_elements = array();
		$liste_id_elements = array();
		foreach ($liste_noms_elements as $nom_element) {
			$element = is_null($this->fiche_media)?null:$this->fiche_media->chercher_objet_classe_par_attribut("pl3_objet_media_image", pl3_outil_source_xml::NOM_ATTRIBUT_NOM, $nom_element->get_valeur());
			if ($element) {
				$liste_elements[] = $element;
				$liste_id_elements[] = $element->lire_id();
			}
		}
		
		$ret = "<div id=\"editeur-galerie-".$this->id_objet."\" class=\"".$this->classe_objet."\">\n";
		$ret .= "<h2>Galerie ".$galerie->get_attribut_nom()."</h2>\n";
		$ret .= "<div class=\"editeur_galerie_from\">\n";
		$ret .= "<h3>Images disponibles</h3>\n";
		$ret .= "<div class=\"container_galerie container_galerie_from\">\n";
		if (!(is_null($this->fiche_media))) {
			foreach($liste_medias as $media) {
				$id_media = $media->lire_id();
				// On n'affiche que les médias non présents dans la galerie
				if (!(in_array($id_media, $liste_id_elements))) {
					$ret .= "<p>".$media->get_attribut_nom()."</p>\n";
				}
			}
		}
		$ret .= "</div></div>\n";
		$ret .= "<div class=\"editeur_galerie_to\">\n";
		$ret .= "<h3>Sélection</h3>\n";
		$ret .= "<div class=\"container_galerie container_galerie_to\">\n";
		foreach($liste_elements as $element) {
			$ret .= "<p>".$element->get_attribut_nom()."</p>\n";
		}
		$ret .= "</div></div>\n";
		$ret .= "<div class=\"clearfix\"></div>\n";
		$ret .= "</div>";
		return $ret;
	}
	
	public function set_fiche_media(&$fiche_media) {$this->fiche_media = $fiche_media;}
}
