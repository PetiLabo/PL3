/*
 * JS PL3 mode administration media
 */
 
/* Initialisations */
$(document).ready(function() {

	/* Gestion du clic sur un media */
	$("div.page_media").on("click", ".vignette_apercu_lien", function() {
		var vignette_id = $(this).attr("id");
		var media_id = parseInt(vignette_id.replace("media-", ""));
		if (media_id > 0) {
			alert("Edition de l'image index "+media_id);
		}
		return false;
	});
	
	/* Gestion du clic sur un bouton d'ajout media */
	$("div.page_media").on("click", ".vignette_plus", function() {
		var plus_id = $(this).attr("id");
		var taille_id = parseInt(plus_id.replace("ajout-", ""));
		if (taille_id > 0) {
			alert("Ajout d'une image dans la taille index "+taille_id);
		}
		return false;
	});

});