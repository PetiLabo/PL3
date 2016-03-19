/* Blocage des mises en cache */
$.ajaxSetup ({cache: false});

/* Appel AJAX pour édition d'un objet */
function editer_objet(contenu_id, bloc_id, balise_id, nom_balise) {
	$.ajax({
		type: "POST",
		url: "petilabo/ajax/pl3_edit_objet.php",
		data: {contenu_id: contenu_id, bloc_id: bloc_id, balise_id: balise_id, nom_balise: nom_balise},
		dataType: "html"
	}).done(function(data) {
		alert(data);
	}).fail(function() {
		alert("ERREUR : Script AJAX introuvable");
	});
}

/* Initialisations */
$(document).ready(function() {
	$("div.page").on("click", ".objet_editable", function() {
		var html_id = $(this).attr("id");
		if (html_id.length > 0) {
			var bloc = $(this).closest(".bloc");
			if (bloc) {
				var bloc_id = bloc.attr("id");
				var contenu = bloc.closest(".contenu");
				if (contenu) {
					var contenu_id = contenu.attr("id");
					var html_id = $(this).attr("id");
					var pos_separateur = html_id.indexOf("-");
					if (pos_separateur >= 0) {
						var nom_balise = html_id.substr(0, pos_separateur);
						var balise_id = html_id.substr(1 + pos_separateur);
						editer_objet(contenu_id, bloc_id, balise_id, nom_balise);
					}
				}
			}
		}
	});
	$("div.page").on("mouseenter", ".objet_editable", function() {
		$(this).css("cursor", "pointer").css("border-color", "#a00");
	});
	$("div.page").on("mouseleave", ".objet_editable", function() {
		$(this).css("cursor", "default").css("border-color", "transparent");
	});
});