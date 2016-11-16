<?php

/**
 * Classe d'objet Vidéo
 */


class pl3_objet_page_video extends pl3_outil_objet_simple_xml {
	/* Icone */
	const NOM_ICONE = "fa-youtube-play";

	/* Fiche */
	const NOM_FICHE = "page";

	/* URLs de l'implementation de l'API oEmbed par les differents hebergeurs */
	const URL_API_YOUTUBE = "https://www.youtube.com/oembed?url=";
	const URL_API_VIMEO = "https://vimeo.com/api/oembed.json?url=";
	const URL_API_DAILYMOTION = "https://www.dailymotion.com/services/oembed?url=";

	/* Balise */
	const NOM_BALISE = "video";
	public static $Balise = array("nom" => self::NOM_VALEUR, "type" => self::TYPE_INDIRECTION, "reference" => "pl3_objet_texte_texte");

	/* Attributs */
	public static $Liste_attributs = array();

	/* Initialisation */
	public function construire_nouveau() {
		/* Création d'une instance de texte */
		$source_page = $this->get_source_page();
		$objet_texte = $source_page->instancier_nouveau(self::$Balise["reference"]);
		if ($objet_texte) {
			$objet_texte->construire_nouveau();
			$objet_texte->set_valeur("https://www.youtube.com/watch?v=aqz-KE-bpKQ");
			$source_page->enregistrer_nouveau($objet_texte);
			$this->set_valeur($objet_texte->get_attribut_nom());
		}
	}

	/* Destruction */
	public function detruire() {
		$source_page = $this->get_source_page();
		$nom_texte = $this->get_valeur();
		$texte = $source_page->chercher_liste_textes_par_nom(pl3_objet_texte_texte::NOM_BALISE, $nom_texte);
		if ($texte != null) {
			$source_page->supprimer($texte);
		}
	}

	/* Affichage */
	public function afficher($mode) {
		$ret = "";
		$source_page = $this->get_source_page();
		$nom_texte = $this->get_valeur();
		$texte = $source_page->chercher_liste_textes_par_nom(pl3_objet_texte_texte::NOM_BALISE, $nom_texte);
		if ($texte != null) {
			$html_id = $this->get_html_id();
			$valeur_texte = $texte->get_valeur();
			if ($mode == _MODE_ADMIN_OBJETS) {
				// Affichage de la miniature à la place de la vidéo
				$ret .= "<div class=\"container_image\">";
				$ret .= "<img id=\"".$html_id."\" class=\"image_responsive objet_editable\" src=\"";
				$ret .= $this->get_thumbnail($valeur_texte);
				$ret .= "\" />";
				$ret .= "</div>\n";
			}
			else {
				// Affichage de la vidéo
				$ret .= $this->get_iframe($valeur_texte);
				$ret .= "\n";
			}
		}
		return $ret;
	}

	/* Extraction du domaine de l'url */
	private function get_host($url_video) {
		$foo = parse_url($url_video);
		return $foo["host"];//array("host" => $foo["host"], "id" => explode('=', $foo["query"])[1]);
	}

	/* Recuperation du code html integrant la video */
	private function get_iframe($url_video) {
		$host = $this->get_host($url_video);
		switch (true) {
			case stristr($host,'youtube'):
					return $this->get_json(self::URL_API_YOUTUBE.$url_video)["html"];
	    case stristr($host,'vimeo'):
	        return $this->get_json(self::URL_API_VIMEO.$url_video)["html"];
	    case stristr($host,'dailymotion'):
	        return $this->get_json(self::URL_API_DAILYMOTION.$url_video)["html"];
		}
	}

	/* Récupération de l'url de la miniature */
	private function get_thumbnail($url_video) {
		$host = $this->get_host($url_video);
		switch (true) {
			case stristr($host,'youtube'):
					return $this->get_json(self::URL_API_YOUTUBE.$url_video)["thumbnail_url"];
			case stristr($host,'vimeo'):
					return $this->get_json(self::URL_API_VIMEO.$url_video)["thumbnail_url"];
			case stristr($host,'dailymotion'):
					return $this->get_json(self::URL_API_DAILYMOTION.$url_video)["thumbnail_url"];
		}
	}

	private function get_json($url) {
		$ch = curl_init($url);//  Initiate curl
		curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);// Disable SSL verification
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);// Will return the response, if false it print the response
		curl_setopt($ch, CURLOPT_URL,$url);// Set the url
		$result=curl_exec($ch);// Execute
		curl_close($ch);// Closing
		return json_decode($result, true);
	}

}
