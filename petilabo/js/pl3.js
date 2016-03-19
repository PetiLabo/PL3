$(document).ready(function() {
	$("div.page").on("click", ".objet_editable", function() {
		var html_id = $(this).attr("id");
		var pos_separateur = html_id.indexOf("-");
		if (pos_separateur >= 0) {
			var nom_balise = html_id.substr(0, pos_separateur);
			var id_balise = html_id.substr(1 + pos_separateur);
			alert(nom_balise+" | id="+id_balise);
		}
	});
	$("div.page").on("mouseenter", ".objet_editable", function() {
		$(this).css("cursor", "pointer").css("border-color", "#a00");
	});
	$("div.page").on("mouseleave", ".objet_editable", function() {
		$(this).css("cursor", "default").css("border-color", "transparent");
	});
});