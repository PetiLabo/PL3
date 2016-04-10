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
	
	/* Fonctions d'édition */
	public function editer() {
		$ret = "<form id=\"formulaire-".$this->id_objet."\" class=\"editeur_formulaire\" method=\"post\">\n";
		$ret .= $this->editer_valeur();
		$ret .= $this->editer_attributs();	
		$ret .= "<input type=\"submit\" value=\"OK\" title=\"Enregistrer et fermer\" />\n";
		$ret .= "</form>\n";
		return $ret;
	}

	public function editer_valeur() {
		$ret = "";
		if ($this->objet->avec_valeur()) {
			$nom_classe = get_class($this->objet);
			$nom_balise = $nom_classe::NOM_BALISE;
			$ret .= "<p class=\"editeur_objet_titre_valeur\">Balise &lt;".$nom_balise."&gt;</p>\n";
			$valeur = $this->objet->get_valeur();
			$balise = $this->objet->get_balise();
			$ret .= $this->afficher_champ_form($balise, $valeur);
		}
		return $ret;
	}

	public function editer_attributs() {
		$ret = "";
		$liste_attributs = $this->objet->get_liste_attributs();
		if (count($liste_attributs) > 0) {
			$ret .= "<p class=\"editeur_objet_titre_attributs\">Attributs&nbsp;:</p>\n";
			foreach ($liste_attributs as $attribut) {
				$valeur = $this->attribut_to_valeur($attribut);
				$ret .= $this->afficher_champ_form($attribut, $valeur);
			}
		}
		return $ret;
	}

	/* Fonctions de service */
	private function afficher_champ_form($information, $valeur) {
		$ret = "";
		$nom_information = $information["nom"];
		$champ_form = $this->type_to_champ_form($information);
		if (isset($champ_form["balise"])) {
			$balise = $champ_form["balise"];
			$id_form = $nom_information."-".$this->id_objet;
			if (strcmp($balise, "textarea")) {
				$type = isset($champ_form["type"])?(" type=\"".$champ_form["type"]."\""):"";
				$ret .= "<p class=\"editeur_champ_formulaire\">";
				$ret .= "<label for=\"".$id_form."\">".ucfirst($nom_information)."</label>";
				$ret .= "<".$balise." id=\"".$id_form."\"".$type." name=\"".$nom_information."\" value=\"".$valeur."\" />";
				$ret .= "</p>\n";
			}
			else {
				$ret .= "<textarea id=\"".$id_form."\" class=\"editeur_trumbowyg\">".$valeur."</textarea>\n";
			}
		}
		return $ret;
	}

	private function type_to_nom_type(&$information) {
		$type_information = $information["type"];
		switch($type_information) {
			case pl3_outil_objet_xml::TYPE_ENTIER:
				$ret = "entier";break;
			case pl3_outil_objet_xml::TYPE_CHAINE:
				$ret = "chaîne de caractères";break;
			case pl3_outil_objet_xml::TYPE_TEXTE:
				$ret = "texte";break;
			case pl3_outil_objet_xml::TYPE_ICONE:
				$ret = "icone";break;
			case pl3_outil_objet_xml::TYPE_REFERENCE:
				if (isset($information["reference"])) {
					$nom_classe = $information["reference"];
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
				$ret = "ERREUR : Type d'information inexistant";
		}
		return $ret;
	}

	private function type_to_champ_form(&$information) {
		$type_information = $information["type"];
		switch($type_information) {
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

	private function attribut_to_valeur($attribut) {
		$nom_attribut = $attribut["nom"];
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
