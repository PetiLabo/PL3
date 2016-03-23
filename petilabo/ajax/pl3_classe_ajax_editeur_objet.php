<?php

/**
 * Classe de gestion de l'éditeur d'objets
 */
 
class pl3_editeur_objet {
	
	private $objet = null;

	public function __construct(&$objet) {
		$this->objet = $objet;
	}

	/* Fonctions d'affichage pour l'éditeur d'objet */
	public function afficher_ligne_xml() {
		$ret = "<p class=\"editeur_objet_titre_xml\">Ligne XML&nbsp;:</p>\n";
		$ret .= "<p class=\"editeur_objet_ligne_xml\">".$this->objet->ecrire_xml(0)."</p>\n";
		return $ret;
	}

	public function afficher_valeur() {
		$ret = "";
		if ($this->objet->avec_valeur()) {
			$valeur = $this->objet->get_valeur();
			$ret .= "<p class=\"editeur_objet_titre_valeur\">Valeur&nbsp;:</p>\n";
			$ret .= "<p class=\"editeur_objet_valeur\">".$valeur."</p>\n";
		}
		return $ret;
	}

	public function afficher_attributs() {
		$ret = "";
		$nom_classe = get_class($this->objet);
		$liste_attributs = $nom_classe::$Liste_attributs;
		if (count($liste_attributs) > 0) {
			$ret .= "<p class=\"editeur_objet_titre_attributs\">Attributs&nbsp;:</p>\n";
			$ret .= "<ul class=\"editeur_objet_liste_attributs\">\n";
			foreach ($liste_attributs as $attribut) {
				$nom_attribut = $attribut["nom"];
				$nom_type = $this->type_attribut_to_nom_type($attribut);
				$nom_valeur = $this->valeur_attribut_to_nom_valeur($nom_attribut);
				$ret .= "<li>".$nom_attribut." [".$nom_type."] : ".$nom_valeur."</li>\n";
			}
			$ret .= "</ul>\n";
		}
		return $ret;
	}

	public function type_attribut_to_nom_type(&$attribut) {
		$type_attribut = $attribut["type"];
		switch($type_attribut) {
			case pl3_outil_objet_xml::TYPE_ENTIER:
				$ret = "entier";break;
			case pl3_outil_objet_xml::TYPE_CHAINE:
				$ret = "chaîne de caractères";break;
			case pl3_outil_objet_xml::TYPE_ICONE:
				$ret = "icone";break;
			case pl3_outil_objet_xml::TYPE_REFERENCE:
				if (isset($attribut["reference"])) {
					$nom_classe = $attribut["reference"];
					$nom_balise = $nom_classe::NOM_BALISE;
					$ret = "référence à un objet ".$nom_balise;
				}
				else {
					$ret = "ERREUR : Référence à un objet inconnu";
				}
				break;
			case pl3_outil_objet_xml::TYPE_FICHIER:
				$ret = "fichier";break;
			default:
				$ret = "ERREUR : Type d'attribut inexistant";
		}
		return $ret;
	}

	private function valeur_attribut_to_nom_valeur($nom_attribut) {
		$has_attribut = $this->objet->has_attribut($nom_attribut);
		if ($has_attribut) {
			$ret = $this->objet->get_attribut_chaine($nom_attribut);
		}
		else {
			$ret = "Non renseigné";
		}
		return $ret;
	}
}
