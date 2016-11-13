/*
 * JS PL3 administration site général
 */
 
/* Appel AJAX pour soumission d'un éditeur d'image */
function ajouter_page(nom_page, nom_page_courante) {
	$.ajax({
		type: "POST",
		url: "../petilabo/ajax/pl3_ajouter_page.php",
		data: {nom_page: nom_page, nom_page_courante: nom_page_courante},
		dataType: "json"
	}).done(function(data) {
		var valide = data["valide"];
		if (valide) {
			var html = data["html"];
			$("#liste-pages").html(html);
			$("#id-nouvelle-page").val("");
		}
		else {
			alert("ERREUR : "+data["msg"]);
		}
	}).fail(function() {
		alert("ERREUR : Script AJAX en échec ou introuvable");
	});
}

function supprimer_page(nom_page, nom_page_courante) {
	$.ajax({
		type: "POST",
		url: "../petilabo/ajax/pl3_supprimer_page.php",
		data: {nom_page: nom_page, nom_page_courante: nom_page_courante},
		dataType: "json"
	}).done(function(data) {
		var valide = data["valide"];
		if (valide) {
			if (nom_page == nom_page_courante) {
				alert("ATTENTION : La page courante ayant été supprimée, la nouvelle page courante sera la page index.");
				window.location = "index.php";
			}
			else {
				var html = data["html"];
				$("#liste-pages").html(html);
			}
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
	$("div.container_vignettes_page").on("click", "a.vignette_icone_admin", function() {
		var item_id = $(this).attr("id");
		var mode_id = item_id.replace("admin-mode-", "");
		var mode_admin = parseInt(mode_id);
		var href = $(this).attr("href");
		changer_mode_admin(mode_admin, href);
		return false;
	});
	
	$("div.container_vignettes_page").on("click", "a.vignette_icone_supprimer", function() {
		var item_id = $(this).attr("id");
		var nom_page = item_id.replace("supprimer-", "");
		var nom_page_courante = $("#id-nom-page-courante").val().trim();
		var confirmation = confirm("Confirmez-vous la suppression de la page "+nom_page+" ?");
		if (confirmation) {
			supprimer_page(nom_page, nom_page_courante);
		}
		return false;
	});

	$("div.page_mode_admin").on("submit", "form.formulaire_nouvelle_page", function() {
		var nom_page = $("#id-nouvelle-page").val().trim();
		var nom_page_courante = $("#id-nom-page-courante").val().trim();
		if (nom_page.length > 0) {
			ajouter_page(nom_page, nom_page_courante);
		}
		$("#id-nouvelle-page").val(nom_page);
		return false;
	});
});
