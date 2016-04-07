<?php

/**
 * Classe de gestion d'une source page
 */
 
class pl3_outil_source_page {
	private $liste_styles = null;
	private $liste_medias = null;
	private $page = null;

	public function __construct() {
		/* Styles */
		$this->liste_styles = new pl3_outil_liste_fiches_xml($this, "pl3_fiche_style");
		$this->liste_styles->ajouter_source(_NOM_SOURCE_GLOBAL, _CHEMIN_XML);
		$this->liste_styles->ajouter_source(_NOM_SOURCE_LOCAL, _CHEMIN_PAGE_COURANTE);

		/* Media */
		$this->liste_medias = new pl3_outil_liste_fiches_xml($this, "pl3_fiche_media");
		$this->liste_medias->ajouter_source(_NOM_SOURCE_GLOBAL, _CHEMIN_XML);
		$this->liste_medias->ajouter_source(_NOM_SOURCE_LOCAL, _CHEMIN_PAGE_COURANTE);

		/* Fichier page */
		$this->page = new pl3_fiche_page($this, _CHEMIN_PAGE_COURANTE);
	}

	public function charger_xml() {
		$this->liste_styles->charger_xml();
		$this->liste_medias->charger_xml();
		$this->charger_page_xml();
	}

	public function charger_page_xml() {
		$this->page->charger_xml();
	}

	public function afficher($mode) {
		$html = $this->page->afficher($mode);
		return $html;
	}

	/* Accesseurs */
	public function get_liste_styles() {return $this->liste_styles;}
	public function get_liste_medias() {return $this->liste_medias;}
	public function get_page() {return $this->page;}

	/* Méthodes de parsing */
	public function parser_balise($fiche, &$objet_parent, $nom_balise, &$noeud) {
		$ret = array();
		if ($noeud != null) {
			$nom_classe = _PREFIXE_OBJET.$fiche."_".$nom_balise;
			$reflection = new ReflectionClass($nom_classe);
			$balise = $reflection->getConstant("NOM_BALISE");
			$attributs = $reflection->getStaticPropertyValue("Liste_attributs");

			$liste = $noeud->getElementsByTagName($balise);
			foreach($liste as $element) {
				$instance = $reflection->newInstanceArgs(array(&$this, $fiche, 1 + count($ret), &$objet_parent, &$element));	
				foreach($attributs as $attribut) {
					$nom_attribut = $attribut["nom"];
					$avec_attribut = $element->hasAttribute($nom_attribut);
					if ($avec_attribut) {
						$valeur_attribut = $element->getAttribute($nom_attribut);
						$instance->set_attribut($nom_attribut, $valeur_attribut);
					}
				}
				$ret[] = $instance;
			}
		}
		return $ret;
	}
	
	public function parser_balise_fille($fiche, &$objet_parent, $nom_classe, $nom_balise, &$noeud) {
		$ret = array();
		if ($noeud != null) {
			$nom_classe = $nom_classe."_".$nom_balise;
			$reflection = new ReflectionClass($nom_classe);
			$balise = $reflection->getConstant("NOM_BALISE");
			$attributs = $reflection->getStaticPropertyValue("Liste_attributs");

			$liste = $noeud->getElementsByTagName($balise);
			foreach($liste as $element) {
				$instance = $reflection->newInstanceArgs(array(&$this, $fiche, 1 + count($ret), &$objet_parent, &$element));	
				
				/* Traitement des attributs */
				foreach($attributs as $attribut) {
					$nom_attribut = $attribut["nom"];
					$avec_attribut = $element->hasAttribute($nom_attribut);
					if ($avec_attribut) {
						$valeur_attribut = $element->getAttribute($nom_attribut);
						$instance->set_attribut($nom_attribut, $valeur_attribut);
					}
				}
				
				/* Traitement de la valeur si la balise doit en avoir une */
				$balise_avec_valeur = $instance->avec_valeur();
				if ($balise_avec_valeur) {
					$valeur = $element->nodeValue;
					$instance->set_valeur($valeur);
				}
					
				$ret[] = $instance;
			}
		}
		return $ret;
	}
	
	public function parser_toute_balise($fiche, &$objet_parent, &$noeud) {
		$ret = array();
		if ($noeud != null) {
			$liste_objets = $noeud->childNodes;
			foreach ($liste_objets as $objet) {
				if ($objet->nodeType != XML_ELEMENT_NODE) {continue;}
				$nom_balise = $objet->nodeName;
				$nom_classe = _PREFIXE_OBJET.$fiche."_".$nom_balise;
				$nom_fichier = _CHEMIN_OBJET.$fiche."/".$nom_classe.".php";
				/* On teste le fichier et non la classe car l'échec de l'autoload provoque un die */
				$fichier_existe = @file_exists($nom_fichier);
				if ($fichier_existe) {
					$reflection = new ReflectionClass($nom_classe);
					$instance = $reflection->newInstanceArgs(array(&$this, $fiche, 1 + count($ret), &$objet_parent, &$objet));
					/* Traitement des attributs */
					$attributs = $reflection->getStaticPropertyValue("Liste_attributs");
					foreach($attributs as $attribut) {
						$nom_attribut = $attribut["nom"];
						$avec_attribut = $objet->hasAttribute($nom_attribut);
						if ($avec_attribut) {
							$valeur_attribut = $objet->getAttribute($nom_attribut);
							$instance->set_attribut($nom_attribut, $valeur_attribut);
						}
					}
					/* Traitement de la valeur si la balise doit en avoir une */
					$balise_avec_valeur = $instance->avec_valeur();
					if ($balise_avec_valeur) {
						$valeur = $objet->nodeValue;
						$instance->set_valeur($valeur);
					}
					$ret[] = $instance;
				}
				else {
					echo "L'objet ".$nom_balise." n'existe pas.<br>\n";
				}
			}
		}
		return $ret;
	}
}