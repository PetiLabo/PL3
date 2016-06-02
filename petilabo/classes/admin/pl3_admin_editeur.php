<?php

/**
 * Classe de gestion des éditeurs
 */
 
abstract class pl3_admin_editeur {
	protected $objet = null;
	protected $id_objet = null;

	/* Constructeur */
	public function __construct(&$objet, $id_objet) {
		$this->objet = $objet;
		$this->id_objet = $id_objet;
	}
	
	/* Fonctions d'édition */
	abstract public function editer();

	/* Fonctions de service */
	protected function afficher_champ_form($information, $valeur) {
		$ret = "";
		$nom_information = $information["nom"];
		/* Traitement des indirections */
		$this->traiter_indirection($information, $valeur);
		$champ_form = $this->type_to_champ_form($information);
		if (isset($champ_form["balise"])) {
			$balise = $champ_form["balise"];
			$id_form = $nom_information."-".$this->id_objet;
			switch ($balise) {
				case "textarea":
					$ret .= "<textarea id=\"".$id_form."\" class=\"editeur_trumbowyg\">".$valeur."</textarea>\n";
					break;
				case "select":
					$liste_noms = $this->traiter_reference($information, $valeur);
					if (count($liste_noms) > 0) {
						$ret .= "<p class=\"editeur_champ_formulaire\">";
						$ret .= "<label for=\"".$id_form."\">".ucfirst($nom_information)."</label>";
						$ret .= "<select id=\"".$id_form."\" name=\"".$nom_information."\">";
						if (!(in_array(_NOM_STYLE_DEFAUT, $liste_noms))) {$liste_noms = array_merge(array(_NOM_STYLE_DEFAUT),$liste_noms);}
						foreach($liste_noms as $nom) {
							$selected = strcmp($nom, $valeur)?"":" selected=\"selected\"";
							$ret .= "<option value=\"".$nom."\"".$selected.">".$nom."</option>";
						}
						$ret .= "</select>\n";
						$ret .= "</p>\n";
					}
					break;
				case "input":
					$type = isset($champ_form["type"])?(" type=\"".$champ_form["type"]."\""):"";
					$ret .= "<p class=\"editeur_champ_formulaire\">";
					$ret .= "<label for=\"".$id_form."\">".ucfirst($nom_information)."</label>";
					$ret .= "<".$balise." id=\"".$id_form."\"".$type." name=\"".$nom_information."\" value=\"".$valeur."\"".$champ_form["attr"]."/>";
					$ret .= "</p>\n";
					break;
				default:
					break;
			}
		}
		return $ret;
	}

	protected function type_to_champ_form(&$information) {
		$attr = "";
		$type_information = $information["type"];
		switch($type_information) {
			case pl3_outil_objet_xml::TYPE_ENTIER:
				if (isset($information["min"])) {$attr .= " min=\"".(int) $information["min"]."\"";}
				if (isset($information["max"])) {$attr .= " max=\"".(int) $information["max"]."\"";}
				$ret = array("balise" => "input", "type" => "number", "attr" => $attr);
				break;
			case pl3_outil_objet_xml::TYPE_CHAINE:
				$ret = array("balise" => "input", "type" => "text", "attr" => $attr);break;
			case pl3_outil_objet_xml::TYPE_TEXTE:
				$ret = array("balise" => "textarea");break;
			case pl3_outil_objet_xml::TYPE_ICONE:
				$ret = array("balise" => "input", "type" => "text", "attr" => $attr);break;
			case pl3_outil_objet_xml::TYPE_REFERENCE:
				$ret = array("balise" => "select", "type" => "text");break;
			case pl3_outil_objet_xml::TYPE_INDIRECTION:
				$ret = array("balise" => "input", "type" => "text", "attr" => $attr);break;
			case pl3_outil_objet_xml::TYPE_FICHIER:
				$ret = array("balise" => "input", "type" => "file", "attr" => $attr);break;
			default:
				$ret = array();
		}
		return $ret;
	}

	protected function attribut_to_valeur($attribut) {
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
	
	protected function traiter_reference($information, $valeur) {
		$liste_noms = array();
		$type_information = $information["type"];
		if ($type_information == pl3_outil_objet_xml::TYPE_REFERENCE) {
			if (isset($information["reference"])) {
				$nom_classe = $information["reference"];
				if (class_exists($nom_classe)) {
					$nom_fiche = $nom_classe::NOM_FICHE;
					$source_page = pl3_ajax_init::Get_source_page();
					$liste_noms = $source_page->chercher_liste_noms_par_fiche($nom_fiche, $nom_classe);
				}
			}
		}
		return $liste_noms;
	}
	
	protected function traiter_indirection(&$information, &$valeur) {
		$type_information = $information["type"];
		if ($type_information == pl3_outil_objet_xml::TYPE_INDIRECTION) {
			if (isset($information["reference"])) {
				$nom_classe = $information["reference"];
				if (class_exists($nom_classe)) {
					$nom_fiche = $nom_classe::NOM_FICHE;
					$nom_balise = $nom_classe::NOM_BALISE;
					$source_page = pl3_ajax_init::Get_source_page();
					$objet_indirection = $source_page->chercher_liste_fiches_par_nom($nom_fiche, $nom_balise, $valeur);
					if ($objet_indirection) {
						$information = $objet_indirection->get_balise();
						$valeur = $objet_indirection->get_valeur();
					}
				}
			}
		}
	}
}
