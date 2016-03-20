/* Blocage des mises en cache */
$.ajaxSetup ({cache: false});

/* Appel AJAX pour édition d'un objet */
function editer_objet(nom_page, balise_id, nom_balise) {
	$.ajax({
		type: "POST",
		url: "petilabo/ajax/pl3_edit_objet.php",
		data: {nom_page: nom_page, balise_id: balise_id, nom_balise: nom_balise},
		dataType: "json"
	}).done(function(data) {
		var valide = data["valide"];
		if (valide) {
			alert(data["html"]);
		}
		else {
			alert("ERREUR : Origine de l'objet éditable introuvable");
		}
	}).fail(function() {
		alert("ERREUR : Script AJAX introuvable");
	});
}

/* Initialisations */
$(document).ready(function() {
	$("div.page").on("click", ".objet_editable", function() {
		var html_id = $(this).attr("id");
		if (html_id.length > 0) {
			var pos_separateur = html_id.indexOf("-");
			if (pos_separateur >= 0) {
				var nom_page = $("div.page").attr("name");
				var nom_balise = html_id.substr(0, pos_separateur);
				var balise_id = html_id.substr(1 + pos_separateur);
				editer_objet(nom_page, balise_id, nom_balise);
			}
		}
	});
	$("div.page").on("mouseenter", ".objet_editable", function() {
		$(this).css({"cursor": "pointer", "border-color": "#f00"});
	});
	$("div.page").on("mouseleave", ".objet_editable", function() {
		$(this).css({"cursor": "default", "border-color": "transparent"});
	});
});