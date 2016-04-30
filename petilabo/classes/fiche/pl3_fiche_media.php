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
	
	/* Afficher */
	public function afficher() {
		$ret = "";
		$source_page = pl3_outil_source_page::Get();
		$liste_tailles = $source_page->chercher_liste_noms_par_fiche("theme", "pl3_objet_theme_taille_image");
		$liste_medias_par_taille = array();
		foreach($liste_tailles as $nom_taille) {
			$liste_medias_par_taille[$nom_taille] = array();
		}
		
		/* Classement des images selon les tailles */
		$liste_medias = $this->liste_objets["pl3_objet_media_image"];
		foreach($liste_medias as $media) {
			$nom_taille = $media->get_valeur_taille();
			if (in_array($nom_taille, $liste_tailles)) {
				$liste_medias_par_taille[$nom_taille][] = $media;
			}
		}
		
		$classe = "page_media".((($this->mode & _MODE_ADMIN) > 0)?" page_mode_admin":"");
		$ret .= "<div class=\"".$classe."\" name=\""._PAGE_COURANTE."\">\n";
		foreach($liste_tailles as $nom_taille) {
			$ret .= "<h2>".$nom_taille."</h2>\n";
			if (count($liste_medias_par_taille[$nom_taille]) == 0) {
				$ret .= "<p style=\"font-style:italic;\">Pas d'images enregistrées sous cette taille</p>\n";
			}
			foreach($liste_medias_par_taille[$nom_taille] as $media) {
				$ret .= "<div style=\"float:left;text-align:center;\">";
				$ret .= $media->afficher($this->mode)."<br>";
				$ret .= $media->get_attribut_nom()."<br>";
				$ret .= "</div>\n";
			}
			$ret .= "<div style=\"clear:both;\"></div>\n";
		}
		$ret .= "</div>\n";
		return $ret;
	}
}