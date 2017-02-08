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
	const NOM_ATTRIBUT_MIN_SLIDE = "min";
	const NOM_ATTRIBUT_MAX_SLIDE = "max";
	const NOM_ATTRIBUT_PAR_SLIDE = "par";
	const NOM_ATTRIBUT_PAGER = "pager";
	public static $Liste_attributs = array(
		array("nom" => self::NOM_ATTRIBUT_MIN_SLIDE, "type" => self::TYPE_ENTIER, "min" => 0, "max" => 6),
		array("nom" => self::NOM_ATTRIBUT_MAX_SLIDE, "type" => self::TYPE_ENTIER, "min" => 0, "max" => 12),
		array("nom" => self::NOM_ATTRIBUT_PAR_SLIDE, "type" => self::TYPE_ENTIER, "min" => 0, "max" => 6),
		array("nom" => self::NOM_ATTRIBUT_PAGER, "type" => self::TYPE_BOOLEEN),
	);

	/* Initialisation */
	public function construire_nouveau() {
		$this->set_valeur("galerie_".uniqid());
		$this->set_attribut_min(0);
		$this->set_attribut_max(0);
		$this->set_attribut_par(0);
		$this->set_attribut_pager(self::VALEUR_BOOLEEN_VRAI);
	}

	/* Affichage */
	public function afficher($mode) {
		$ret = "";
		$max_width = 0;
		$source_page = $this->get_source_page();
		$nom_galerie = $this->get_valeur();
		$galerie = $source_page->chercher_liste_medias_par_nom(pl3_objet_media_galerie::NOM_BALISE, $nom_galerie);
		if ($galerie != null) {
			$html_id = $this->get_html_id();
			$ret .= "<div class=\"container_carrousel\">";
			$ret .= "<ul id=\"".$html_id."\" class=\"bxslider\">";
			$liste_noms_elements = $galerie->get_elements();
			foreach ($liste_noms_elements as $nom_element) {
				$nom_image = $nom_element->get_valeur();
				$image = $source_page->chercher_liste_medias_par_nom(pl3_objet_media_image::NOM_BALISE, $nom_image);
				$ret .= $this->afficher_element($image, $max_width);
			}
			$ret .= "</ul></div>\n";
			$min = $this->get_attribut_entier(self::NOM_ATTRIBUT_MIN_SLIDE);
			$max = $this->get_attribut_entier(self::NOM_ATTRIBUT_MAX_SLIDE);
			$par = $this->get_attribut_entier(self::NOM_ATTRIBUT_PAR_SLIDE);
			$pager = $this->get_attribut_booleen(self::NOM_ATTRIBUT_PAGER);
			
			/* Attachement de bxslider au carrousel */
			$ret .= "<script type=\"text/javascript\">\n";
			$ret .= "$(document).ready(function(){";
			$ret .= "$('#".$html_id."').bxSlider({";
			/* A la fin du chargement on transfère l'id et la classe éditable au wrapper */
			$ret .= "onSliderLoad: function(){";
			$ret .= "var ul=$('#".$html_id."');";
			$ret .= "ul.removeAttr('id');";
			$ret .= "ul.closest('.bx-wrapper').attr('id', '".$html_id."').addClass('objet_editable');";
			$ret .= "},";
			if ($min > 0) {$ret .= "minSlides: ".$min.",";}
			if ($max > 0) {$ret .= "maxSlides: ".$max.",";}
			if ($par > 0) {$ret .= "moveSlides: ".$par.",";}
			if (($min > 0) || ($max > 0) || ($par > 0)) {
				$ret .= "slideWidth: ".$max_width.",slideMargin: 10,";
			}
		
			$ret .= "pager: ".($pager?"true":"false")."});";
			$ret .= "});\n";
			$ret .= "</script>\n";
		}
		else if (($mode & _MODE_ADMIN) > 0) {
			$html_id = $this->get_html_id();
			$ret .= "<div class=\"container_carrousel\">";
			$ret .= "<p id=\"".$html_id."\" class=\"objet_editable\"><span class=\"fa ".self::NOM_ICONE." effet_objet_vide\"></span></p>";
			$ret .= "</div>\n";
		}
		return $ret;
	}
	
	private function afficher_element(&$image, &$max_width) {
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
			if ($largeur > $max_width) {$max_width = $largeur;}
			$hauteur = $image->get_valeur_hauteur_reelle();
			if ($hauteur > 0) {$taille .= " height=\"".$hauteur."\"";}
			$html_id = $this->get_html_id();
			$ret .= "<li><img ".$src.$alt.$taille." /></li>";
		}
		return $ret;
	}
}