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
					$nom = $media->get_attribut_nom();
					$src = $media->get_valeur_fichier();
					$taille = $media->get_valeur_taille_standard();
					$fichier = _CHEMIN_IMAGES_XML.html_entity_decode($src, ENT_QUOTES, "UTF-8");
					$ret .= "<div id=\"galerie-".$media->lire_id()."\" class=\"vignette_galerie_to\"><a><img src=\"".$fichier."\" /></a><span>".$nom."</span><span class=\"legende_taille_standard\">".$taille."</span></div>";
				}
			}
		}
		$ret .= "</div></div>\n";
		$ret .= "<div class=\"editeur_galerie_to\">\n";
		$ret .= "<h3>Sélection</h3>\n";
		$ret .= "<div class=\"container_galerie container_galerie_to\">\n";
		foreach($liste_elements as $element) {
			$nom = $element->get_attribut_nom();
			$src = $element->get_valeur_fichier();
			$fichier = _CHEMIN_IMAGES_XML.html_entity_decode($src, ENT_QUOTES, "UTF-8");
			$taille = $element->get_valeur_taille_standard();
			$ret .= "<div id=\"galerie-".$element->lire_id()."\" class=\"vignette_galerie_to\"><a><img src=\"".$fichier."\" /></a><span>".$nom."</span><span class=\"legende_taille_standard\">".$taille."</span></div>";
		}
		$ret .= "</div></div>\n";
		$ret .= "<div class=\"clearfix\"></div>\n";
		$ret .= "</div>";
		return $ret;
	}
	
	public function set_fiche_media(&$fiche_media) {$this->fiche_media = $fiche_media;}
}
