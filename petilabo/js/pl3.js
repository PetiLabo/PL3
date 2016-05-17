/*
 * JS PL3 général
 */
 
/* Blocage des mises en cache */
$.ajaxSetup ({cache: false});

/* Appel AJAX pour le changement de mode d'administration */
function changer_mode_admin(mode_admin) {
	$.ajax({
		type: "POST",
		url: "../petilabo/ajax/pl3_changer_mode_admin.php",
		data: {mode_admin: mode_admin},
		dataType: "json"
	}).done(function(data) {
		var valide = data["valide"];
		if (valide) {location.reload();}
		else {alert("ERREUR : Changement de mode d'administration impossible");}
	}).fail(function() {
		alert("ERREUR : Script AJAX en échec ou introuvable");
	});
}

/* Initialisations */
$(document).ready(function() {
	/* Items de la barre d'outils "admin" */
	$("a.admin_item_barre_outils").click(function() {
		var item_id = $(this).attr("id");
		var mode_id = item_id.replace("admin-mode-", "");
		var mode_admin = parseInt(mode_id);
		changer_mode_admin(mode_admin);
	});
});
