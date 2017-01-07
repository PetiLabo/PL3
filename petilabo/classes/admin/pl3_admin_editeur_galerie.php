<?php

/**
 * Classe de gestion de l'éditeur de galeries
 */

class pl3_admin_editeur_galerie extends pl3_admin_editeur {
	private $fiche_media = null;

	/* Fonctions d'édition */
	public function editer() {
		$galerie = $this->objet;
		$ret = "<div id=\"editeur-galerie-".$this->id_objet."\" class=\"".$this->classe_objet."\">\n";
		$ret .= "<h2>Galerie ".$galerie->get_attribut_nom()."</h2>\n";
		$ret .= "<div class=\"editeur_galerie_from\">\n";
		$ret .= "<h3>Images à insérer</h3>\n";
		if (!(is_null($this->fiche_media))) {
			$liste_medias = $this->fiche_media->get_liste_objets("pl3_objet_media_image");
			foreach($liste_medias as $media) {
				$ret .= "<p>".$media->get_attribut_nom()."</p>\n";
			}
		}
		$ret .= "</div>\n";
		$ret .= "<div class=\"editeur_galerie_to\">\n";
		$ret .= "<h3>Composition de la galerie</h3>\n";
		$liste_composants = array();
		foreach($liste_composants as $composant) {
			$ret .= "<p>".$composant->get_attribut_nom()."</p>\n";
		}
		$ret .= "</div>\n";
		$ret .= "<div class=\"clearfix\"></div>\n";
		$ret .= "</div>";
		return $ret;
	}
	
	public function set_fiche_media(&$fiche_media) {$this->fiche_media = $fiche_media;}
}
