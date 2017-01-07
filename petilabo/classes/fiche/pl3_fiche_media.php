<?php

/**
 * Classe de gestion des fiches media.xml
 */
 
class pl3_fiche_media extends pl3_outil_fiche_xml {
	const NOM_FICHE = "media";
	
	/* Constructeur */
	public function __construct($chemin, $id) {
		$this->declarer_objet("pl3_objet_media_image");
		$this->declarer_objet("pl3_objet_media_galerie");
		parent::__construct($chemin, $id);
	}
	public function instancier_image($fichier, $taille, $largeur, $hauteur) {
		$fichier_sans_prefixe = substr($fichier, 0, strpos($fichier,  "."));
		$nom_image = htmlspecialchars($fichier_sans_prefixe, ENT_QUOTES, "UTF-8");
		$doublon = $this->chercher_objet_classe_par_attribut("pl3_objet_media_image", self::NOM_ATTRIBUT_NOM, $nom_image);
		if (is_null($doublon)) {
			$objet = parent::instancier_nouveau("pl3_objet_media_image");
			if ($objet) {
				$objet->set_valeur_fichier($fichier);
				$objet->set_valeur_taille_standard($taille);
				$objet->set_valeur_largeur_reelle($largeur);
				$objet->set_valeur_hauteur_reelle($hauteur);
				$fichier_sans_prefixe = substr($fichier, 0, strpos($fichier,  "."));
				$nom_image = htmlspecialchars($fichier_sans_prefixe, ENT_QUOTES, "UTF-8");
				$objet->set_attribut_nom($nom_image);
			}
		}
		else {$objet = null;}
		return $objet;
	}
	
	/* Suppression d'une image */
	public function retirer_image($image_id) {
		$liste_images = array();
		$nb_images = count($this->liste_objets["pl3_objet_media_image"]);
		$id_cpt = 1;
		for ($cpt = 0;$cpt < $nb_images;$cpt ++) {
			$image = &$this->liste_objets["pl3_objet_media_image"][$cpt];
			if ($image != null) {
				if ($image->lire_id() != $image_id) {
					$image->ecrire_id($id_cpt);
					$liste_images[] = $image;
					$id_cpt += 1;
				}
				else {
					$image->detruire();
					unset($image);
				}
			}
		}
		$this->liste_objets["pl3_objet_media_image"] = $liste_images;
	}

	/* Afficher */
	public function afficher() {
		$ret = "";
		$source_page = $this->get_source_page();
		$liste_tailles = $source_page->chercher_liste_noms_par_fiche("theme", "pl3_objet_theme_taille_image");
		$liste_medias_par_taille = array();
		foreach($liste_tailles as $id_taille => $nom_taille) {
			$liste_medias_par_taille[$nom_taille] = array("id" => 1 + $id_taille, "medias" => array());
		}
		
		/* Classement des images selon les tailles */
		$theme = $source_page->get_theme();
		$liste_medias = $this->liste_objets["pl3_objet_media_image"];
		foreach($liste_medias as $media) {
			$nom_taille = $media->get_valeur_taille_standard();
			if (in_array($nom_taille, $liste_tailles)) {
				$liste_medias_par_taille[$nom_taille]["medias"][] = $media;
			}
		}

		$classe = "page page_media".((($this->mode & _MODE_ADMIN) > 0)?" page_mode_admin":"");
		$ret .= "<div class=\"".$classe."\" name=\""._PAGE_COURANTE."\">\n";
		/* Liste des images taille par taille */
		foreach($liste_tailles as $nom_taille) {
			$info_media = $liste_medias_par_taille[$nom_taille];
			$id_taille = $info_media["id"];
			$liste_medias = $info_media["medias"];
			$taille = $theme->chercher_objet_classe_par_attribut("pl3_objet_theme_taille_image", self::NOM_ATTRIBUT_NOM, $nom_taille);
			$largeur = $taille->get_valeur_largeur();
			if (((int) $largeur) == 0) {$largeur = "...";}
			$hauteur = $taille->get_valeur_hauteur();
			if (((int) $hauteur) == 0) {$hauteur = "...";}
			$compression = (int) $taille->get_valeur_compression();
			$ret .= "<h2 id=\"titre-taille-".$id_taille."\" data-largeur=\"".$largeur."\" data-hauteur=\"".$hauteur."\" data-compression=\"".$compression."\" >".$nom_taille." ";
			$ret .= "<span class=\"indication_taille_image\">(".$largeur."x".$hauteur.")</span>";
			$ret .= "</h2>\n";
			$ret .= "<div id=\"taille-".$id_taille."\" class=\"taille_container\">\n";
			foreach($liste_medias as $media) {$ret .= $this->afficher_vignette_media($media);}
			$ret .= self::Afficher_ajout_media($id_taille, $nom_taille);
			$ret .= "<div class=\"clearfix\"></div>\n";
			$ret .= "</div>\n";
		}
		/* Liste des galeries */
		$liste_galeries = $this->liste_objets["pl3_objet_media_galerie"];
		$ret .= "<h2 id=\"titre-galeries\">Galeries</h2>\n";
		$ret .= "<div id=\"galeries\" class=\"taille_container\">\n";
		foreach($liste_galeries as $galerie) {$ret .= $this->afficher_vignette_galerie($galerie);}
		$ret .= self::Afficher_ajout_galerie();
		$ret .= "<div class=\"clearfix\"></div>\n";
		$ret .= "</div>\n";	

		/* Fin de la page */
		$ret .= "</div>\n";
		return $ret;
	}

	public function afficher_vignette_media(&$media) {
		$ret = "";
		$nom = $media->get_attribut_nom();
		$ret .= "<div class=\"vignette_container\">\n";
		$ret .= "<a id=\"media-".$media->lire_id()."\" class=\"vignette_apercu_lien\" href=\"#\" title=\"Editer l'image ".$nom."\">";
		$ret .= $media->afficher($this->mode);
		$ret .= "</a>";
		$ret .= "<p class=\"vignette_legende_image\">".$nom."</p>";
		$ret .= "</div>\n";
		return $ret;
	}

	public function afficher_vignette_galerie(&$galerie) {
		$ret = "";
		$nom = $galerie->get_attribut_nom();
		$ret .= "<div class=\"vignette_container\">\n";
		$ret .= "<a id=\"galerie-".$galerie->lire_id()."\" class=\"vignette_galerie_lien\" href=\"#\" title=\"Editer la galerie ".$nom."\">";
		$ret .= "<span class=\"fa fa-list-alt\" style=\"font-size:120px;\"></span></a>";
		$ret .= "<p class=\"vignette_legende_image\">".$nom."</p>";
		$ret .= "</div>\n";
		return $ret;
	}
	
	public static function Afficher_ajout_media($id_taille, $nom_taille) {
		$ret = "";
		$ret .= "<div class=\"vignette_container\">";
		$ret .= "<a id=\"ajout-".$id_taille."\" class=\"fa fa-plus-circle vignette_plus\" href=\"#\" title=\"Ajouter une image au format ".strtolower($nom_taille)."\"></a>";
		$ret .= "<input type=\"file\" id=\"input-".$id_taille."\" style=\"display:none;\" name=\"img-".$id_taille."\" value=\"".$nom_taille."\"/>\n";
		$ret .= "</div>\n";
		return $ret;
	}
	
	public static function Afficher_ajout_galerie() {
		$ret = "";
		$ret .= "<div class=\"vignette_container\">";
		$ret .= "<a id=\"ajout-galerie\" class=\"fa fa-plus-circle vignette_plus\" href=\"#\" title=\"Ajouter une galerie\"></a>";
		$ret .= "<input type=\"file\" id=\"input-galerie\" style=\"display:none;\" name=\"galerie\" value=\"galerie\"/>\n";
		$ret .= "</div>\n";
		return $ret;
	}
}