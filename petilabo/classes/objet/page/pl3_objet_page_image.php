<?php

/**
 * Classe de gestion des images
 */
 
class pl3_objet_page_image extends pl3_outil_objet_xml {
	const NOM_BALISE = "image";
	public static $Noms_attributs = array();

	public function ecrire_xml($niveau) {
		$xml = $this->ouvrir_fermer_xml($niveau);
		return $xml;
	}
	
	public function afficher() {
		$nom_image = $this->get_valeur();
		$image = pl3_fiche_liste_medias::Chercher_instance_balise_par_attribut(pl3_objet_media_image::NOM_BALISE, pl3_objet_media_image::NOM_ATTRIBUT_NOM, $nom_image);
		if ($image != null) {
			$fichier = $image->get_valeur_fichier();
			echo "<img src=\"${fichier}\" alt=\"\" />\n";
		}
	}
}