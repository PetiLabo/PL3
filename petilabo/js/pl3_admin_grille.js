/*
 * JS PL3 mode administration objets
 */

function ajouter_contenu(nom_page) {
	alert("Ajout d'un contenu dans la page "+nom_page);
}

function ajouter_bloc(nom_page, contenu_id) {
	alert("Ajout d'un bloc dans le contenu "+contenu_id+" de la page "+nom_page);
}

/* Application de plugins */
function appliquer_sortable(selecteur, items) {
	$(selecteur).sortable({
		placeholder: "deplaceur_"+items,
		items: "div[class^='"+items+"']",
		update: function() {
			/* Gestion du déplacement d'un objet */
		},
		start: function (e, ui) {
			ui.placeholder.height(ui.item.children().height());
			ui.placeholder.width(100);
		},
		opacity: 0.7
	});
	$(selecteur).disableSelection();
}


/* Initialisations */
$(document).ready(function() {
	/* Gestion de la bordure au survol d'une légende de bloc */
	$("div.page").on("mouseenter", ".bloc_legende_nom", function() {
		$(this).addClass("bloc_legende_survol");
	});
	$("div.page").on("mouseleave", ".bloc_legende_nom", function() {
		$(this).removeClass("bloc_legende_survol");
	});
	
	/* Gestion du bouton pour l'édition de contenu */
	$("div.page").on("mouseenter", "p.contenu_poignee_edit a", function() {
		var contenu_attr_id = $(this).parent().attr("id");
		var contenu_id = contenu_attr_id.replace("poignee-", "");
		$("#"+contenu_id).addClass("contenu_survol");
	});
	$("div.page").on("mouseleave", "p.contenu_poignee_edit a", function() {
		var contenu_attr_id = $(this).parent().attr("id");
		var contenu_id = contenu_attr_id.replace("poignee-", "");
		$("#"+contenu_id).removeClass("contenu_survol");
	});
	$("div.page").on("click", "p.contenu_poignee_ajout a", function() {
		var nom_page = parser_page();
		ajouter_contenu(nom_page);
		return false;
	});

	/* Gestion du bouton pour l'ajout de contenu */
	$("div.page").on("click", "p.contenu_poignee_ajout a", function() {
		var nom_page = parser_page();
		ajouter_contenu(nom_page);
		return false;
	});
	
	/* Gestion des boutons pour l'ajout de bloc */
	$("div.page").on("click", "p.bloc_poignee_ajout a", function() {
		var bloc_attr_id = $(this).parent().attr("id");
		var contenu_id = bloc_attr_id.replace("poignee-bloc-", "");
		var nom_page = parser_page();
		ajouter_bloc(nom_page, contenu_id);
		return false;
	});
	
	/* Possibilité de changer l'ordre des éléments */
	$(".page").sortable({
		items: ".contenu_flex",
		handle: ".contenu_poignee_edit",
		placeholder: "deplaceur_contenu",
		update: function() {
			/* Gestion du déplacement d'un objet */
		},
		start: function (e, ui) {
			ui.placeholder.height(ui.item.children().height());
		},
		opacity: 0.7
	});
	$(".page").disableSelection();
	$(".contenu_grille").sortable({
		items: ".bloc_grille",
		placeholder: "deplaceur_bloc",
		update: function() {
			/* Gestion du déplacement d'un objet */
		},
		start: function (e, ui) {
			var elem = $(ui.item);
			var hauteur = elem.height();
			var largeur = elem.width();
			ui.placeholder.height(hauteur).width(largeur);
		},
		opacity: 0.7
	});
	$(".contenu_grille").disableSelection();
});