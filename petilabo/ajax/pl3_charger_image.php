<?php
header('Content-type: application/json');

define("_CHEMIN_BASE_URL", "../../");
define("_CHEMIN_BASE_RESSOURCES", "../");
require_once(_CHEMIN_BASE_URL."petilabo/pl3_init.php");

/* Classe de service pour le traitement du $_FILES en post */	
class pl3_telechargement_post {
	private $file_post = array();
	private $file_tmp_name = null;
	
	public function __construct($file_post) {
		$this->file_post = $file_post;
		$this->file_tmp_name = $file_post["tmp_name"];
	}
	
	public function controle_post(&$message) {
		$ret_chargement = $this->file_post["error"];
		if ($ret_chargement == UPLOAD_ERR_OK) {
			return true;
		}
		else {
			$message = $this->traduire_erreur_upload($ret_chargement);
			return false;
		}
	}
	
	public function get_tmp_name() {return $this->file_tmp_name;}
		
	public function effacer() {
		if (@file_exists($this->file_tmp_name)) {return (@unlink($this->file_tmp_name));}
	}

	private function traduire_erreur_upload($erreur) {
		$ret = "ERREUR : ";
		switch($erreur) {
			case UPLOAD_ERR_INI_SIZE:
				$taille_autorisee = ini_get("upload_max_filesize");
				$ret .= "Le fichier sélectionné dépasse la taille maximum (".$taille_autorisee.")";
				break;
			case UPLOAD_ERR_PARTIAL:
				$ret .= "Le fichier n'a pu être que partiellement téléchargé.";
				break;
			case UPLOAD_ERR_NO_FILE:
				$ret .= "Aucun fichier n'a été téléchargé.";
				break;
			case UPLOAD_ERR_NO_TMP_DIR:
				$ret .= "Le dossier temporaire de téléchargement n'a pas été trouvé.";
				break;
			case UPLOAD_ERR_CANT_WRITE:
				$ret .= "Un problème est survenu lors de l'écriture du fichier.";
				break;
			default:
				$ret .= "Le fichier n'a pas pu être téléchargé pour une raison inconnue.";
		}
		return $ret;
	}
}

/* Classe de service pour le traitement du fichier image */
class pl3_telechargement_image {
	const EXTENSION_IMAGE_JPEG = "jpeg";
	const EXTENSION_IMAGE_JPG = "jpg";
	const EXTENSION_IMAGE_PNG = "png";
	const EXTENSION_IMAGE_GIF = "gif";

	private $src = null;
	private $nom_dest = null;
	private $dest = null;
	private $ext = null;
	private $largeur = 0;
	private $hauteur = 0;
	private $compression = 0;

	public function __construct($src, $largeur, $hauteur, $compression) {
		$this->src = $src;
		$this->largeur = $largeur;
		$this->hauteur = $hauteur;
		$this->compression = $compression;
	}

	public function set_destination($nom_origine) {
		$this->nom_dest = $this->nettoyer_nom_fichier($nom_origine);
		$this->dest = _CHEMIN_XML."images/".$this->nom_dest;
		$this->ext = $this->get_extension_fichier($this->nom_dest);
	}

	public function move_and_resize_uploaded_file(&$message) {
		$ret = false;
		if (@file_exists($this->src)) {
			if (@file_exists($this->dest)) {
				$message = "ERREUR : Un fichier portant le même nom existe déjà.";
			}
			else {
				if ($this->is_extension_media($this->ext)) {
					$ret = $this->resize_uploaded_file();
					if (!($ret)) {$message = "ERREUR : Impossible d'installer le fichier téléchargé.";}
				}
				else {
					$message = "ERREUR : Les fichiers au format ".$this->ext." ne peuvent pas être gérés comme des médias.";
				}
			}
			@unlink($this->src);
		}
		else {
			$message = "ERREUR : Le fichier téléchargé est inacessible";
		}
		return $ret;
	}
	
	public function get_image_size() {
		if (@file_exists($this->dest)) {return (@getimagesize($this->dest));}
		else {return array(0, 0);}
	}
	
	public function effacer() {
		if (@file_exists($this->dest)) {return (@unlink($this->dest));}
	}
	
	public function get_nom_image() {return $this->nom_dest;}
	
	private function resize_uploaded_file() {
		$ret = true;
		list($largeur_image, $hauteur_image) = getimagesize($this->src);
		// Taille standard
		if (($this->largeur > 0) && ($this->hauteur > 0)) {
			list($delta_l, $delta_h) = $this->calculer_delta($largeur_image, $hauteur_image);
			$largeur = $this->largeur;$hauteur = $this->hauteur;
		}
		elseif (($this->largeur > 0) && ($this->hauteur <= 0)) {
			$delta_l = 0;$delta_h = 0;$largeur = $this->largeur;
			$hauteur = $hauteur_image * (float) (((float) $this->largeur) / ((float) max($largeur_image,1)));
		}
		elseif (($this->largeur <= 0) && ($this->hauteur > 0)) {
			$delta_l = 0;$delta_h = 0;$hauteur = $this->hauteur;
			$largeur = $largeur_image * (float) (((float) $this->hauteur) / ((float) max($hauteur_image,1)));
		}
		if (($this->largeur > 0) || ($this->hauteur > 0)) {
			$this->retailler($largeur_image, $hauteur_image, $largeur, $hauteur, $delta_l, $delta_h);
		}
		else {
			$ret = move_uploaded_file($this->src, $this->dest);
		}
		return $ret;
	}

	private function calculer_delta($largeur_image, $hauteur_image) {
		if ($hauteur_image == 0) {$rapport_1 = 1;}
		else {$rapport_1 = (float) (((float) $largeur_image) / ((float) $hauteur_image));}
		if ($this->hauteur == 0) {$rapport_0 = 1;}
		else {$rapport_0 = (float) (((float) $this->largeur) / ((float) $this->hauteur));}
		if ($rapport_0 > $rapport_1) {
			$delta_l = 0;
			$delta_h = (int) (($hauteur_image * $this->largeur - $largeur_image * $this->hauteur) / (2 * max($this->largeur, 1)));
		}
		elseif ($rapport_0 < $rapport_1) {
			$delta_l = (int) (($largeur_image * $this->hauteur - $hauteur_image * $this->largeur) / (2 * max($this->hauteur,1)));
			$delta_h = 0;
		}
		else {
			$delta_l = 0;
			$delta_h = 0;
		}
		return array($delta_l, $delta_h);
	}
	
	private function retailler($largeur_image, $hauteur_image, $nouvelle_largeur, $nouvelle_hauteur, $delta_largeur, $delta_hauteur) {
		if (!(strcmp($this->ext, self::EXTENSION_IMAGE_JPG))) {
			$this->retailler_jpg($largeur_image, $hauteur_image, $nouvelle_largeur, $nouvelle_hauteur, $delta_largeur, $delta_hauteur);
		}
		elseif (!(strcmp($this->ext, self::EXTENSION_IMAGE_PNG))) {
			$this->retailler_png($largeur_image, $hauteur_image, $nouvelle_largeur, $nouvelle_hauteur, $delta_largeur, $delta_hauteur);
		}
		elseif (!(strcmp($this->ext, self::EXTENSION_IMAGE_GIF))) {
			$this->retailler_gif($largeur_image, $hauteur_image, $nouvelle_largeur, $nouvelle_hauteur, $delta_largeur, $delta_hauteur);
		}
		@unlink($this->src);
	}
	
	private function retailler_jpg($largeur_image, $hauteur_image, $nouvelle_largeur, $nouvelle_hauteur, $delta_largeur, $delta_hauteur) {
		$src_r = imagecreatefromjpeg($this->src);
		if ($src_r) {
			$dst_r = ImageCreateTrueColor($nouvelle_largeur, $nouvelle_hauteur);
			if ($dst_r) {
				imagecopyresampled($dst_r, $src_r,
									0, 0, 
									$delta_largeur, $delta_hauteur, 
									$nouvelle_largeur, $nouvelle_hauteur, 
									$largeur_image - (2*$delta_largeur), $hauteur_image - (2*$delta_hauteur));
				$qualite = $this->compression;
				$ret = imagejpeg($dst_r, $this->dest, $qualite);
				imagedestroy($dst_r);
			}
			imagedestroy($src_r);
		}
	}
	
	private function retailler_png($largeur_image, $hauteur_image, $nouvelle_largeur, $nouvelle_hauteur, $delta_largeur, $delta_hauteur) {
		$src_r = imagecreatefrompng($this->src);
		if ($src_r) {
			$src_alpha = $this->png_has_transparency($this->src);
			$dst_r = ImageCreateTrueColor($nouvelle_largeur, $nouvelle_hauteur);
			if ($dst_r) {
				if ($src_alpha) {
					imagealphablending($dst_r, false);
					imagesavealpha($dst_r, true);
				}
				imagecopyresampled($dst_r, $src_r,
									0, 0, 
									$delta_largeur, $delta_hauteur, 
									$nouvelle_largeur, $nouvelle_hauteur, 
									$largeur_image - (2*$delta_largeur), $hauteur_image - (2*$delta_hauteur));
				/* En cas d'image non transparente on reduit à une image avec palette (pb de taille) */
				if (!$src_alpha) {
					$tmp = ImageCreateTrueColor($nouvelle_largeur, $nouvelle_hauteur);
					ImageCopyMerge($tmp, $dst_r, 0, 0, 0, 0, $nouvelle_largeur, $nouvelle_hauteur, 100);
					ImageTrueColorToPalette($dst_r, false, 8192);
					ImageColorMatch($tmp, $dst_r);
					ImageDestroy($tmp);
				}
				$qualite = (int) ($this->compression / 10);
				$ret = imagepng($dst_r, $this->dest, $qualite);
				imagedestroy($dst_r);
			}
			imagedestroy($src_r);
		}
	}
	
	private function retailler_gif($largeur_image, $hauteur_image, $nouvelle_largeur, $nouvelle_hauteur, $delta_largeur, $delta_hauteur) {
		$src_r = imagecreatefromgif($this->src);
		if ($src_r) {
			$dst_r = ImageCreateTrueColor($nouvelle_largeur, $nouvelle_hauteur);
			if ($dst_r) {
				imagecopyresampled($dst_r, $src_r,
									0, 0, 
									$delta_largeur, $delta_hauteur, 
									$nouvelle_largeur, $nouvelle_hauteur, 
									$largeur_image - (2*$delta_largeur), $hauteur_image - (2*$delta_hauteur));
				$ret = imagegif($dst_r, $this->dest);
				imagedestroy($dst_r);
			}
			imagedestroy($src_r);
		}
		return $ret;
	}

	// Grand merci à http://www.jonefox.com/ !!!
	private function png_has_transparency($filename) {
		if (strlen($filename) == 0 || !file_exists($filename)) return false;
		if (ord(file_get_contents($filename, false, null, 25, 1)) & 4) return true;
		$contents = file_get_contents($filename);
		if (stripos($contents, 'PLTE') !== false && stripos($contents, 'tRNS') !== false) return true;
		return false;
	}

	private function nettoyer_nom_fichier($nom_fichier) {
		$ret = trim(strtolower($nom_fichier));
		$ret = str_replace(" ", "-", $ret);
		$ret = str_replace(".".self::EXTENSION_IMAGE_JPEG, ".".self::EXTENSION_IMAGE_JPG, $ret);
		return $ret;
	}
	
	private function get_extension_fichier($fichier) {
		$ret = strtolower(pathinfo($fichier, PATHINFO_EXTENSION));
		return $ret;
	}
	
	private function is_extension_media($ext) {
		$ret = (!(strcmp($ext, self::EXTENSION_IMAGE_JPG)));
		$ret = $ret || (!(strcmp($ext, self::EXTENSION_IMAGE_PNG)));
		$ret = $ret || (!(strcmp($ext, self::EXTENSION_IMAGE_GIF)));
		return $ret;
	}
}

/* Préparation des données */
$info_sortie = "";
$retour_valide = false;
$index_taille = (int) pl3_ajax_post::Post("taille");
$nom_taille = pl3_ajax_post::Post("nom_taille");
$largeur_taille = (int) pl3_ajax_post::Post("largeur_taille");
$hauteur_taille = (int) pl3_ajax_post::Post("hauteur_taille");
$compression = (int) pl3_ajax_post::Post("compression");
$taille = htmlspecialchars($nom_taille, ENT_QUOTES, "UTF-8");
$nom_page = pl3_ajax_post::Post("page");
$nom_champ_post = "img-".$index_taille;
$html = pl3_fiche_media::Afficher_ajout_media($index_taille, $taille);

/* Traitement de l'upload */
if (($index_taille > 0) && (strlen($nom_taille) > 0) && (strlen($nom_page) > 0) && (isset($_FILES[$nom_champ_post]))) {
	define("_PAGE_COURANTE", $nom_page);
	define("_CHEMIN_PAGE_COURANTE", _CHEMIN_PAGES_XML.$nom_page."/");
	
	/* Traitement du $_FILES en post */
	$fichier_post = new pl3_telechargement_post($_FILES[$nom_champ_post]);
	$retour_valide = $fichier_post->controle_post($info_sortie);
	if ($retour_valide) {
		$fichier_temporaire = $fichier_post->get_tmp_name();

		/* Chargement de la fiche média locale */
		$fiche_media = new pl3_fiche_media(_CHEMIN_PAGE_COURANTE, 1);
		$retour_valide = $fiche_media->charger_xml();
		
		/* Rapatriement de l'image uploadée si la fiche média est disponible */
		if ($retour_valide) {
			$telechargement = new pl3_telechargement_image($fichier_temporaire, $largeur_taille, $hauteur_taille, $compression);
			$telechargement->set_destination($_FILES[$nom_champ_post]["name"]);
			$retour_valide = $telechargement->move_and_resize_uploaded_file($info_sortie);
			if ($retour_valide) {
				list($largeur, $hauteur) = $telechargement->get_image_size();
				$nom_image = $telechargement->get_nom_image();
				$image = $fiche_media->instancier_image($nom_image, $taille, $largeur, $hauteur);
				if ($image) {
					$fiche_media->ajouter_objet($image);
					$fiche_media->enregistrer_xml();
					$html = ($fiche_media->afficher_vignette_media($image)).$html;
				}
				else {
					$retour_valide = false;
					$telechargement->effacer();
					$info_sortie = "ERREUR : Une image du même nom existe déjà.";
				}
			}
		}
		else {
			$fichier_post->effacer();
			$info_sortie = "ERREUR : Impossible de charger la fiche XML des média.";
		}
	}
}
else {
	$info_sortie = "ERREUR : Les informations envoyées sont incorrectes.";
}

echo json_encode(array("code" => $retour_valide, "info" => $info_sortie, "html" => $html));