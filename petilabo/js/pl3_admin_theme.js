/*
 * JS PL3 administration site thèmes
 */
 
/* Appel AJAX pour l'installation d'un thème */
function installer_theme() {
	var fd = new FormData();
	fd.append("zip-theme", $("#id-nouveau-theme").get(0).files[0]);
	$.ajax({
		type: "POST",
		url: "../petilabo/ajax/pl3_ajouter_theme.php",
		data: fd,
		processData: false,
		contentType: false,
		dataType: "json"
	}).done(function(data) {
		var valide = data["valide"];
		if (valide) {
			alert("Le thème "+data["theme"]+" a été correctement installé");
		}
		else {
			alert(data["msg"]);
		}
	}).fail(function() {
		alert("ERREUR : Script AJAX en échec ou introuvable");
	});
}

/* Appel AJAX pour la désinstallation d'un thème */
function desinstaller_theme(nom_theme) {
	$.ajax({
		type: "POST",
		url: "../petilabo/ajax/pl3_supprimer_theme.php",
		data: {nom_theme: nom_theme},
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
	/* Gestion des boutons de la vignette thème */
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

	/* Soumission du formulaire "nouveau thème" */
	$("div.page_mode_admin").on("submit", "form.formulaire_nouveau_theme", function() {
		var nb_fichiers = $("#id-nouveau-theme").get(0).files.length;
		if (nb_fichiers == 1) {installer_theme();}
		else {alert("ERREUR : Sélectionnez d'abord le thème à installer !");}
		return false;
	});
});
