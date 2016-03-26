<?php

/**
 * Classe de gestion de l'éditeur d'objets
 */
 
class pl3_ajax_editeur_objet {
	
	private $objet = null;
	private $id_objet = null;

	/* Constructeur */
	public function __construct(&$objet, $id_objet) {
		$this->objet = $objet;
		$this->id_objet = $id_objet;
	}

	/* Fonctions d'affichage */
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
	
	/* Fonctions d'édition */
	public function editer() {
		$ret = "<form id=\"formulaire-".$this->id_objet."\" class=\"editeur_formulaire\" method=\"post\">\n";
		$ret .= $this->afficher_valeur();
		$ret .= $this->editer_attributs();	
		$ret .= "<input type=\"submit\" value=\"OK\" />\n";
		$ret .= "</form>\n";
		return $ret;
	}

	public function editer_attributs() {
		$ret = "";
		$nom_classe = get_class($this->objet);
		$liste_attributs = $nom_classe::$Liste_attributs;
		if (count($liste_attributs) > 0) {
			$ret .= "<p class=\"editeur_objet_titre_attributs\">Attributs&nbsp;:</p>\n";
			foreach ($liste_attributs as $attribut) {
				$nom_attribut = $attribut["nom"];
				$champ_form = $this->type_attribut_to_champ_form($attribut);
				$nom_valeur = $this->valeur_attribut_to_nom_valeur($nom_attribut);
				if (isset($champ_form["balise"])) {
					$balise = $champ_form["balise"];
					if (strcmp($balise, "textarea")) {
						$id_form = $nom_attribut."-".$this->id_objet;
						$type = isset($champ_form["type"])?(" type=\"".$champ_form["type"]."\""):"";
						$ret .= "<p class=\"editeur_champ_formulaire\">";
						$ret .= "<label for=\"".$id_form."\">".ucfirst($nom_attribut)."</label>";
						$ret .= "<".$balise." id=\"".$id_form."\"".$type." name=\"".$nom_attribut."\" value=\"".$nom_valeur."\" />";
						$ret .= "</p>\n";
					}
					else {
						$ret .= "<textarea>".$nom_valeur."</textarea>\n";
					}
				}
			}
		}
		return $ret;
	}

	/* Fonctions de service */
	private function type_attribut_to_nom_type(&$attribut) {
		$type_attribut = $attribut["type"];
		switch($type_attribut) {
			case pl3_outil_objet_xml::TYPE_ENTIER:
				$ret = "entier";break;
			case pl3_outil_objet_xml::TYPE_CHAINE:
				$ret = "chaîne de caractères";break;
			case pl3_outil_objet_xml::TYPE_TEXTE:
				$ret = "texte";break;
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

	private function type_attribut_to_champ_form(&$attribut) {
		$type_attribut = $attribut["type"];
		switch($type_attribut) {
			case pl3_outil_objet_xml::TYPE_ENTIER:
				$ret = array("balise" => "input", "type" => "number");break;
			case pl3_outil_objet_xml::TYPE_CHAINE:
				$ret = array("balise" => "input", "type" => "text");break;
			case pl3_outil_objet_xml::TYPE_TEXTE:
				$ret = array("balise" => "textarea");break;
			case pl3_outil_objet_xml::TYPE_ICONE:
				$ret = array("balise" => "input", "type" => "text");break;
			case pl3_outil_objet_xml::TYPE_REFERENCE:
				$ret = array("balise" => "input", "type" => "text");break;
			case pl3_outil_objet_xml::TYPE_FICHIER:
				$ret = array("balise" => "input", "type" => "file");break;
			default:
				$ret = array();
		}
		return $ret;
	}

	private function valeur_attribut_to_nom_valeur($nom_attribut) {
		$has_attribut = $this->objet->has_attribut($nom_attribut);
		if ($has_attribut) {
			$ret = $this->objet->get_attribut_chaine($nom_attribut);
		}
		else {
			$ret = null;
		}
		return $ret;
	}
}
