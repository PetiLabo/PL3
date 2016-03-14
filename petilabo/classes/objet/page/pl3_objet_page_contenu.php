<?php

/**
 * Classe de gestion des contenus
 */
 
class pl3_objet_page_contenu extends pl3_outil_objet_xml {
	const NOM_BALISE = "contenu";
	const NOM_ATTRIBUT_STYLE = "style";
	public static $Noms_attributs = array(self::NOM_ATTRIBUT_STYLE);

	private $blocs = array();

	public function charger_xml() {
		$this->blocs = $this->parser_balise(pl3_objet_page_bloc::NOM_BALISE);
		foreach($this->blocs as $bloc) {
			$bloc->charger_xml();
		}
	}
	
	public function ecrire_xml($niveau) {
		$attr_style = $this->get_xml_attribut_chaine(self::NOM_ATTRIBUT_STYLE);
		$xml = $this->ouvrir_xml($niveau, array($attr_style));
		foreach($this->blocs as $bloc) {
			$xml .= $bloc->ecrire_xml(1 + $niveau);
		}
		$xml .= $this->fermer_xml($niveau);
		return $xml;
	}

	public function creer_bloc($taille = 1) {
		$bloc = new pl3_objet_page_bloc($this->nom_fiche, $this->id);
		$bloc->set_attribut(pl3_objet_page_bloc::NOM_ATTRIBUT_TAILLE, $taille);
		$this->ajouter_bloc($bloc);
		return $bloc;
	}

	public function ajouter_bloc(&$bloc) {
		$this->blocs[] = $bloc;
	}
	
	public function afficher() {
		echo "<div class=\"contenu\">\n";
		foreach($this->blocs as $bloc) {
			$bloc->afficher();
		}
		echo "</div>\n";
	}
}