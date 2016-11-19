<?php

/**
 * Classe de service pour le traitement des archives ZIP
 */

class pl3_admin_zip {
	const ZIP_FICHIER = 1;
	const ZIP_DOSSIER = 2;

	private $nom_archive = null;
	private $archive = null;
	private $liste_entrees = array();
	
	public function __construct($nom_archive) {
		$this->nom_archive = $nom_archive;
		$this->archive = new ZipArchive();
		if ($this->archive) {
			$this->archive->open($nom_archive);
			for($cpt = 0; $cpt < $this->archive->numFiles; $cpt++ ) { 
				$entree = $this->archive->statIndex($cpt);
				$entree_est_fichier = ($entree["size"] > 0)?true:false;
				$chemin_entree = $entree["name"];
				if ($entree_est_fichier) {
					$this->liste_entrees[] = array("type" => self::ZIP_FICHIER, "dossier" => dirname($chemin_entree), "fichier" => basename($chemin_entree));
				}
				else {
					$this->liste_entrees[] = array("type" => self::ZIP_DOSSIER, "dossier" => dirname($chemin_entree), "fichier" => null);
				}
			}
		}
	}
	
	public function copier_dossier($source, $cible) {
		$ret = "";
		if ($this->archive) {
			foreach($this->liste_entrees as $entree) {
				$type = $entree["type"];
				if ($type == self::ZIP_FICHIER) {
					$dossier = $entree["dossier"];
					if (!(strcmp($dossier, $source))) {
						$fichier = $entree["fichier"];
						$entree_archive = $dossier."/".$fichier;
						$ret .= $entree_archive." ";
						$this->archive->extractTo($cible, $entree_archive);
					}
				}
			}
		}
		return $ret;
	}
	
	public function lire_racine_commune() {
		$ret = null;
		$racine_en_cours = null;
		foreach($this->liste_entrees as $entree) {
			$dossier = $entree["dossier"];
			$liste_dossiers = explode("/", $dossier);
			$racine = $liste_dossiers[0];
			if (is_null($racine_en_cours)) {
				$racine_en_cours = $racine;
				$ret = $racine_en_cours;
			}
			else {
				if (strcmp($racine, $racine_en_cours)) {
					$ret = null;
				}
			}
			
		}
		return $ret;
	}
}