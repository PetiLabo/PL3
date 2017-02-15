/*
 * JS PL3 administration site liens
 */

/* Initialisations */
$(document).ready(function() {
	/* Gestion des boutons plus */
	$("div.container_categorie_liens").on("click", "a.icone_lien_interne_plus", function() {
		alert("Ajouter un lien interne");
		return false;
	});
	$("div.container_categorie_liens").on("click", "a.icone_lien_externe_plus", function() {
		alert("Ajouter un lien externe");
		return false;
	});
	$("div.container_categorie_liens").on("click", "a.icone_menu_plus", function() {
		alert("Ajouter un menu");
		return false;
	});
});
