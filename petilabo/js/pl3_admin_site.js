/*
 * JS PL3 administration site général
 */
 
/* Appel AJAX pour soumission d'un éditeur d'image */
function ajouter_page(nom_page) {
	$.ajax({
		type: "POST",
		url: "../petilabo/ajax/pl3_ajouter_page.php",
		data: {nom_page: nom_page},
		dataType: "json"
	}).done(function(data) {
		var valide = data["valide"];
		if (valide) {
			alert("OK");
		}
		else {
			alert("ERREUR : "+data["msg"]);
		}
	}).fail(function() {
		alert("ERREUR : Script AJAX en échec ou introuvable");
	});
}

/* Initialisations */
$(document).ready(function() {
	/* Items de la barre d'outils "admin" */
	$("a.vignette_icone_admin").click(function() {
		var item_id = $(this).attr("id");
		var mode_id = item_id.replace("admin-mode-", "");
		var mode_admin = parseInt(mode_id);
		var href = $(this).attr("href");
		changer_mode_admin(mode_admin, href);
	});
	
	$("div.page_mode_admin").on("submit", "form.formulaire_nouvelle_page", function() {
		var nom_page = $("#id-nouvelle-page").val().trim();
		if (nom_page.length > 0) {
			ajouter_page(nom_page);
		}
		$("#id-nouvelle-page").val(nom_page);
		return false;
	});
});
