/*
 * JS PL3 mode administration media
 */

/*
 * Adapté de :
 * singleuploadimage - jQuery plugin for upload a image, simple and elegant.
 * Copyright (c) 2014 Langwan Luo
 * Licensed under the MIT license
 * http://www.opensource.org/licenses/mit-license.php
 * Project home:
 * https://github.com/langwan/jquery.singleuploadimage.js
 * version: 1.0.3
 */
(function($) {
    $.fn.singleupload = function(options) {
        var vignette = this;
        var inputfile = null;
        var settings = $.extend({
            action: '#',
            onSuccess: function(html) {},
            onError: function(message){},
            onProgress: function(index, loaded, total) {
                var progression = Math.round(loaded * 100 / total);
				var barre = $("#barre-progression-"+index);
				if (barre) {barre.css("width", progression+"%");}
            },
            taille: 0,
            nom_taille: "",
			largeur_taille: 0,
			hauteur_taille: 0,
			compression: 75,
			page: 'index'
        }, options);

        $('#'+settings.inputId).bind('change', function() {
			var html_barre_progression = "<div class='vignette_container_progression'><div id='barre-progression-"+settings.taille+"' class='vignette_barre_progression'></div></div>";
            vignette = vignette.replaceWithPush(html_barre_progression);
            var fd = new FormData();
            fd.append($('#'+settings.inputId).attr("name"), $('#'+settings.inputId).get(0).files[0]);
            fd.append("taille", settings.taille);
            fd.append("nom_taille", settings.nom_taille);
            fd.append("largeur_taille", settings.largeur_taille);
            fd.append("hauteur_taille", settings.hauteur_taille);
            fd.append("compression", settings.compression);
            fd.append("page", settings.page);

            var xhr = new XMLHttpRequest();
            xhr.addEventListener("load", function(ev) {
                var res = eval("("+ev.target.responseText+")");
                if (!res.code) {
                    settings.onError(res.info);
                }
				/* Ajout de la nouvelle image et/ou du nouveau bouton d'ajout */
				var vignette_parent = vignette.parent().replaceWithPush(res.html);
				/* Le nouveau bouton d'ajout reçoit à son tour le plugin d'upload */
				vignette_parent.find("a.vignette_plus").each(function() {
					installer_single_image_upload($(this));
				});
                if (res.code) {
					settings.onSuccess(res.html);
				}
            },
            false);
            xhr.upload.addEventListener("progress", function(ev) {
                settings.onProgress(settings.taille, ev.loaded, ev.total);
            }, false);

            xhr.open("POST", settings.action, true);
            xhr.send(fd);
        });
    	return this;
    }
}( jQuery ));

/* AJAX */
function editer_image(nom_page, media_id) {
	var editeur_id = "editeur-media-"+media_id;
	var editeur = $("#"+editeur_id);
	if (editeur.length > 0) {
    // shake effect on div for an error event
		editeur.addClass("shake");setTimeout(function(){editeur.removeClass("shake")},1000);
		return;
	}
	$.ajax({
		type: "POST",
		url: "../petilabo/ajax/pl3_editer_image.php",
		data: {nom_page: nom_page, media_id: media_id},
		dataType: "json"
	}).done(function(data) {
		var valide = data["valide"];
		if (valide) {
			var html = data["html"];
			afficher_editeur(media_id, html);
		}
		else {
			alert("ERREUR : Origine du media introuvable");
		}
	}).fail(function() {
		alert("ERREUR : Script AJAX en échec ou introuvable");
	});
}

function editer_galerie(nom_page, galerie_id) {
	$.ajax({
		type: "POST",
		url: "../petilabo/ajax/pl3_editer_galerie.php",
		data: {nom_page: nom_page, galerie_id: galerie_id},
		dataType: "json"
	}).done(function(data) {
		var valide = data["valide"];
		if (valide) {
			var html = data["html"];
			$.featherlight(html, {closeIcon:''});
			retailler_lightbox();
			/* Activation du sortable */
			$(".container_galerie").sortable({
				connectWith: ".container_galerie", 
				placeholder: "deplaceur_galerie",
				start: function (e, ui) {
					var elem = $(ui.item);
					var hauteur = elem.height();
					var largeur = elem.width();
					ui.placeholder.height(hauteur).width(largeur);
				}
			}).disableSelection();
		}
		else {
			alert("ERREUR : Origine de la galerie introuvable");
		}
	}).fail(function() {
		alert("ERREUR : Script AJAX en échec ou introuvable");
	});
}

/* Appel AJAX pour soumission d'un éditeur d'image */
function soumettre_image(nom_page, media_id, parametres) {
	$.ajax({
		type: "POST",
		url: "../petilabo/ajax/pl3_soumettre_image.php",
		data: {nom_page: nom_page, media_id: media_id, parametres: parametres},
		dataType: "json"
	}).done(function(data) {
		var valide = data["valide"];
		if (valide) {
			var maj = data["maj"];
			if (maj) {
				var html = data["html"];
				var balise = $("#media-"+media_id);
				if (balise) {
					var parent_balise = balise.parent();
					if (parent_balise) {
						parent_balise.replaceWith(html);
					}
				}
			}
      $("#editeur-media-"+media_id).fadeOut(200, function() {$(this).remove();});
		}
		else {
			alert("ERREUR : Origine de l'image introuvable");
		}
	}).fail(function() {
		alert("ERREUR : Script AJAX en échec ou introuvable");
	});
}

/* Appel AJAX pour suppression d'une image */
function supprimer_image(nom_page, media_id) {
	$.ajax({
		type: "POST",
		url: "../petilabo/ajax/pl3_supprimer_image.php",
		data: {nom_page: nom_page, media_id: media_id},
		dataType: "json"
	}).done(function(data) {
		var valide = data["valide"];
		if (valide) {
			/* Renumérotation des id des médias */
			var attr_id = "";var no_id = 0;
			$("div.page_media").find("a[id^='media-']").each(function() {
				attr_id = $(this).attr("id");
				no_id = parseInt(attr_id.replace("media-", ""));
				if (no_id == media_id) {
					$(this).parent().remove();
				}
				else if (no_id > media_id) {
					var re_no_id = no_id - 1;
					$(this).attr("id", "media-"+re_no_id);
				}
			});
			$("#editeur-media-"+media_id).remove();
		}
		else {
			alert("NOK");
		}
	});
}

/* Affichage du code attaché à l'éditeur d'image */
function afficher_editeur(media_id, html) {
	var media = $("#media-"+media_id);
	if (media.length > 0) {
		/* Constitution de l'éditeur */
		var style = calculer_coord_editeur(media, false);
		var div_id = "editeur-media-"+media_id;
		var div = "<div id=\""+div_id+"\" class=\"editeur_objet\" style=\""+style+"\" >";
		div += "<p class=\"editeur_objet_barre_outils\">";
		div += "<a class=\"editeur_objet_bouton_agrandir\" href=\"#\" title=\"Agrandir\"><span class=\"fa fa-expand\"></span></a>";
		//div += "<a class=\"editeur_objet_bouton_fermer\" href=\"#\" title=\"Fermer\"><span class=\"fa fa-times\"></span></a>";
		div += "</p>";
		div += html;
		div += "</div>";

    /* Affichage de l'éditeur */
		$(div).hide().appendTo("div.page_media").fadeIn(250);
    }
}

/* Installation du plugin single image upload sur un bouton ajout media */
function installer_single_image_upload(bouton) {
	var plus_id = bouton.attr("id");
	var taille_id = parseInt(plus_id.replace("ajout-", ""));
	var input_taille = $("#titre-taille-"+taille_id);
	if (input_taille !== undefined) {
		var nom_taille = $("#input-"+taille_id).attr("value");
	}
	else {
		var nom_taille = "";
	}
	var titre_taille = $("#titre-taille-"+taille_id);
	if (titre_taille !== undefined) {
		var largeur = parseInt(titre_taille.data("largeur"));
		var hauteur = parseInt(titre_taille.data("hauteur"));
		var compression = parseInt(titre_taille.data("compression"));
	}
	else {
		var largeur = 0;
		var hauteur = 0;
		var compression = 75;
	}
	if ((taille_id > 0) && (nom_taille.length > 0)) {
		bouton.singleupload({
			action: "../petilabo/ajax/pl3_charger_image.php",
			inputId: "input-"+taille_id,
			taille: taille_id,
			nom_taille: nom_taille,
			largeur_taille: largeur,
			hauteur_taille: hauteur,
			compression: compression,
			page: parser_page(),
			onError: function(message) {alert(message);	}
		});
	}
}

function retailler_lightbox() {
	var largeur_fenetre = parseInt(window.innerWidth);
	var largeur_lightbox = parseInt(85 * largeur_fenetre / 100);
	$(".editeur_type_galerie").css("width", largeur_lightbox+"px");
	var hauteur_fenetre = parseInt(window.innerHeight);
	var hauteur_max = parseInt(50 * hauteur_fenetre / 100);
	$(".container_galerie").css("height", hauteur_max+"px");
}

function fermer_lightbox() {
	var current = $.featherlight.current();
	current.close();
}

/* Initialisations */
$(document).ready(function() {
	/* Gestion du clic sur un media */
	$("div.page_media").on("click", ".vignette_apercu_lien", function() {
		var vignette_id = $(this).attr("id");
		var media_id = parseInt(vignette_id.replace("media-", ""));
		if (media_id > 0) {
			var nom_page = parser_page();
			editer_image(nom_page, media_id);
		}
		return false;
	});

	/* Gestion du clic sur un bouton d'ajout media */
	$("div.page_media").on("click", ".vignette_plus", function() {
		var plus_id = $(this).attr("id");
		var taille_id = parseInt(plus_id.replace("ajout-", ""));
		if (taille_id > 0) {
			$("#input-"+taille_id).click();
		}
		return false;
	});

	/* Gestion du clic sur une galerie */
	$("div.page_media").on("click", ".vignette_galerie_lien", function() {
		var vignette_id = $(this).attr("id");
		var galerie_id = parseInt(vignette_id.replace("galerie-", ""));
		if (galerie_id > 0) {
			var nom_page = parser_page();
			editer_galerie(nom_page, galerie_id);
		}
		return false;
	});

	/* Attachement du plugin single image upload aux boutons d'ajout media */
	$("a.vignette_plus").each(function() {
		installer_single_image_upload($(this));
	});

	/* Bouton "soumettre" dans les éditeurs d'images */
	$("div.page").on("submit", "form.editeur_formulaire", function() {
		var form_id = $(this).attr("id");
		var media_id = parseInt(form_id.replace("formulaire-", ""));
		var nom_page = parser_page();
		var parametres = $(this).serialize();
		soumettre_image(nom_page, media_id, parametres);
		return false;
	});

	/* Boutons "supprimer" dans les éditeurs d'images */
	$("div.page").on("click", "form.editeur_formulaire button.supprimer_formulaire", function() {
		var form_id = $(this).attr("id");
		var media_id = parseInt(form_id.replace("supprimer-media-", ""));
		var nom_page = parser_page();
		var confirmation = confirm("Confirmez-vous la suppression de cette image ?");
		if (confirmation) {
			supprimer_image(nom_page, media_id);
		}
		return false;
	});
	
	/* Gestion des éditeurs d'objets lors du retaillage de la fenêtre */
	$(window).on("resize", function() {retailler_lightbox();});

});
