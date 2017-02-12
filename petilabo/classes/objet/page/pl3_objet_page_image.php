<?php

/**
 * Classe de gestion des images
 */
 
class pl3_objet_page_image extends pl3_outil_objet_simple_xml {
	/* Icone */
	const NOM_ICONE = "fa-picture-o";

	/* Fiche */
	const NOM_FICHE = "page";

	/* Balise */
	const NOM_BALISE = "image";
	public static $Balise = array("nom" => self::NOM_VALEUR, "type" => self::TYPE_REFERENCE, "reference" => "pl3_objet_media_image");
	
	/* Attributs */
	const NOM_ATTRIBUT_LIEN = "lien";
	const NOM_ATTRIBUT_SURVOL = "survol";
	public static $Liste_attributs = array(
		array("nom" => self::NOM_ATTRIBUT_LIEN, "type" => self::TYPE_REFERENCE, "reference" => "pl3_objet_liens_lien"),
		array("nom" => self::NOM_ATTRIBUT_SURVOL, "type" => self::TYPE_REFERENCE, "reference" => "pl3_objet_theme_style_survol")
	);

	/* Initialisation */
	public function construire_nouveau() {
		$this->set_valeur("image_".uniqid());
	}

	/* Affichage */
	public function afficher($mode) {
		$ret = "";
		$source_page = $this->get_source_page();
		$nom_image = $this->get_valeur();
		$image = $source_page->chercher_liste_medias_par_nom(pl3_objet_media_image::NOM_BALISE, $nom_image);
		$html_id = $this->get_html_id();
		if ($image != null) {
			/* SRC */
			$fichier = $image->get_valeur_fichier();
			$src = " src=\""._CHEMIN_IMAGES_XML.$fichier."\"";
			/* ALT */
			$nom_alt = $image->get_valeur_alt();
			if (strlen($nom_alt) > 0) {
				$texte_alt = $source_page->chercher_liste_textes_par_nom(pl3_objet_texte_texte::NOM_BALISE, $nom_alt);
				if ($texte_alt != null) {$nom_alt = $texte_alt->get_valeur();}
			}
			$alt = (strlen($nom_alt) > 0)?" alt=\"".$nom_alt."\"":"";
			/* ANCHOR */
			$pre_img = "";$post_img = "";
			if (($mode & _MODE_ADMIN) == 0) {
				$nom_lien = $this->get_attribut_lien();
				if (strlen($nom_lien) > 0) {
					$obj_lien = $source_page->chercher_liste_liens_par_nom(pl3_objet_liens_lien::NOM_BALISE, $nom_lien);
					if ($obj_lien != null) {
						$pre_img = "<a id=\"lien-".$html_id."\"";
						$nom_survol = $this->get_attribut_survol();
						if (strlen($nom_survol) > 0) {
							$pre_img .= " class=\"survol_".$nom_survol."\"";
						}
						$pre_img .= $obj_lien->afficher($mode);
						$pre_img .= ">";
						$post_img = "</a>";
					}
				}
			}
			/* WIDTH/HEIGHT */
			$taille = "";
			$largeur = $image->get_valeur_largeur_reelle();
			if ($largeur > 0) {$taille .= " width=\"".$largeur."\"";}
			$hauteur = $image->get_valeur_hauteur_reelle();
			if ($hauteur > 0) {$taille .= " height=\"".$hauteur."\"";}
			/* HTML */
			$ret .= "<div class=\"container_image\">";
			$ret .= $pre_img."<img id=\"".$html_id."\" class=\"image_responsive objet_editable\"".$src.$alt.$taille." />".$post_img;
			$ret .= "</div>\n";
		}
		else if (($mode & _MODE_ADMIN) > 0) {
			$ret .= "<div class=\"container_image\">";
			$ret .= "<p id=\"".$html_id."\" class=\"objet_editable\"><span class=\"fa ".self::NOM_ICONE." effet_objet_vide\"></span></p>";
			$ret .= "</div>\n";
		}
		return $ret;
	}
}