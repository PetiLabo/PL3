<?php

/**
 * Classe de gestion de l'éditeur d'objets
 */

class pl3_admin_editeur_objet extends pl3_admin_editeur {

	/* Fonctions d'édition */
	public function editer() {
		$ret = "<form id=\"formulaire-".$this->id_objet."\" class=\"editeur_formulaire ".$this->classe_objet."\" method=\"post\">\n";
		$ret .= $this->editer_valeur();
		$ret .= $this->editer_attributs();
		$ret .= "<p class=\"boutons_formulaire\">\n";
		$ret .= "<button id=\"soumettre-".$this->id_objet."\" class=\"soumettre_formulaire\" type=\"submit\" value=\"ok\" title=\"Enregistrer et fermer\">";
		$ret .= "<span class=\"fa fa-check editeur_formulaire_icone_bouton\"></span>";
		$ret .= "</button>";
		$ret .= "<button id=\"annuler-".$this->id_objet."\" class=\"annuler_formulaire\" value=\"annuler\" title=\"Annuler et fermer\">";
		$ret .= "<span class=\"fa fa-times editeur_formulaire_icone_bouton\"></span>";
		$ret .= "</button>";
		$ret .= "<button id=\"supprimer-".$this->id_objet."\" class=\"supprimer_formulaire\" value=\"supprimer\" title=\"Supprimer cet objet\">";
		$ret .= "<span class=\"fa fa-trash editeur_formulaire_icone_bouton\"></span>";
		$ret .= "</button>";
		$ret .= "</p>\n";
		$ret .= "</form>\n";
		return $ret;
	}

	private function editer_valeur() {
		$ret = "";
		if ($this->objet->avec_valeur()) {
			$nom_classe = get_class($this->objet);
			$nom_balise = $nom_classe::NOM_BALISE;
			$ret .= "<p class=\"editeur_objet_titre_valeur\">Objet ".$nom_balise."</p>\n";
			$valeur = $this->objet->get_valeur();
			$balise = $this->objet->get_balise();
			$ret .= $this->afficher_champ_form($balise, $valeur);
		}
		return $ret;
	}

	private function editer_attributs() {
		$ret = "";
		$liste_attributs = $this->objet->get_liste_attributs();
		if (count($liste_attributs) > 0) {
			$ret .= "<p class=\"editeur_objet_titre_attributs\">Attributs&nbsp;:</p>\n";
			$groupe_actuel = "";
			foreach ($liste_attributs as $attribut) {
				$valeur = $this->attribut_to_valeur($attribut);
				$groupe = isset($attribut["groupe"])?$attribut["groupe"]:"";
				if (strcmp($groupe_actuel, $groupe)) {
					if (strlen($groupe_actuel) > 0) {
						$ret .= "<div style=\"clear:both;\"></div></div><div style=\"clear:both;\"></div>\n";
					}
					if (strlen($groupe) > 0) {
						$ret .= "<div class=\"editeur_objet_groupe\">\n";
						$ret .= "<p class=\"editeur_objet_groupe_titre\">".$groupe."</p>\n";
						$groupe_actuel = $groupe;
					}
				}
				$ret .= $this->afficher_champ_form($attribut, $valeur);
			}
			if (strlen($groupe_actuel) > 0) {
				$ret .= "<div style=\"clear:both;\"></div></div><div style=\"clear:both;\"></div>\n";
			}
		}
		return $ret;
	}
}
