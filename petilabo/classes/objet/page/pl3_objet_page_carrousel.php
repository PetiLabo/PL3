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

	/* Initialisation */
	public function construire_nouveau() {
		$this->set_valeur("galerie_".uniqid());
	}

	/* Affichage */
	public function afficher($mode) {
		$ret = "";
		$source_page = $this->get_source_page();
		$nom_galerie = $this->get_valeur();
		$galerie = $source_page->chercher_liste_medias_par_nom(pl3_objet_media_galerie::NOM_BALISE, $nom_galerie);
		if ($galerie != null) {
			$html_id = $this->get_html_id();
			$ret .= "<div class=\"container_carrousel\">";
			$ret .= "<div id=\"".$html_id."\" class=\"objet_editable\">";
			$liste_noms_elements = $galerie->get_elements();
			foreach ($liste_noms_elements as $nom_element) {
				$nom_image = $nom_element->get_valeur();
				$image = $source_page->chercher_liste_medias_par_nom(pl3_objet_media_image::NOM_BALISE, $nom_image);
				$ret .= $this->afficher_element($image);
			}
			$ret .= "</div></div>\n";
		}
		else if (($mode & _MODE_ADMIN) > 0) {
			$html_id = $this->get_html_id();
			$ret .= "<div class=\"container_carrousel\">";
			$ret .= "<p id=\"".$html_id."\" class=\"objet_editable\"><span class=\"fa ".self::NOM_ICONE." effet_objet_vide\"></span></p>";
			$ret .= "</div>\n";
		}
		return $ret;
	}
	
	private function afficher_element(&$image) {
		$ret = "";
		if ($image != null) {
			$source_page = $this->get_source_page();
			$fichier = $image->get_valeur_fichier();
			$src = " src=\""._CHEMIN_IMAGES_XML.$fichier."\"";
			$nom_alt = $image->get_valeur_alt();
			if (strlen($nom_alt) > 0) {
				$texte_alt = $source_page->chercher_liste_textes_par_nom(pl3_objet_texte_texte::NOM_BALISE, $nom_alt);
				if ($texte_alt != null) {$nom_alt = $texte_alt->get_valeur();}
			}
			$alt = (strlen($nom_alt) > 0)?" alt=\"".$nom_alt."\"":"";
			$taille = "";
			$largeur = $image->get_valeur_largeur_reelle();
			if ($largeur > 0) {$taille .= " width=\"".$largeur."\"";}
			$hauteur = $image->get_valeur_hauteur_reelle();
			if ($hauteur > 0) {$taille .= " height=\"".$hauteur."\"";}
			$html_id = $this->get_html_id();
			$ret .= "<img ".$src.$alt.$taille." />";
		}
		return $ret;
	}
}