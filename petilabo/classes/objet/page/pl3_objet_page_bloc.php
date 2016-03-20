<?php

/**
 * Classe de gestion des blocs
 */
 
class pl3_objet_page_bloc extends pl3_outil_objet_xml {
	const NOM_BALISE = "bloc";
	const NOM_ATTRIBUT_STYLE = "style";
	const NOM_ATTRIBUT_TAILLE = "taille";
	public static $Noms_attributs = array(self::NOM_ATTRIBUT_STYLE, self::NOM_ATTRIBUT_TAILLE);

	private $objets = array();

	public function charger_xml() {
		$this->objets = pl3_outil_parser_xml::Parser_toute_balise(pl3_fiche_page::NOM_FICHE, $this, $this->noeud);
	}

	public function ecrire_xml($niveau) {
		$attr_style = $this->get_xml_attribut_chaine(self::NOM_ATTRIBUT_STYLE);
		$attr_taille = $this->get_xml_attribut_entier(self::NOM_ATTRIBUT_TAILLE);
		$xml = $this->ouvrir_xml($niveau, array($attr_style, $attr_taille));
		foreach ($this->objets as $objet) {
			$xml .= $objet->ecrire_xml(1 + $niveau);
		}
		$xml .= $this->fermer_xml($niveau);
		return $xml;
	}
	
	public function afficher() {
		$taille = $this->get_attribut_entier(self::NOM_ATTRIBUT_TAILLE, 1);
		echo "<div id=\"bloc-".$this->lire_id_parent()."-".$this->lire_id()."\" class=\"bloc\" style=\"flex-grow:".$taille.";\">\n";
		echo "<p>".$taille."</p>\n";
		foreach($this->objets as $objet) {
			$objet->afficher();
		}
		echo "</div>\n";
	}
	
	/* Recherches */
	public function chercher_objet_par_id($id) {
		foreach($this->objets as $instance) {
			$valeur_id = $instance->lire_id();
			if (!(strcmp($valeur_id, $id))) {return $instance;}
		}
		return null;
	}
}