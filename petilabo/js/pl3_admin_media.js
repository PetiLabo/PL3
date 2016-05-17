/*
 * JS PL3 mode administration media
 */
 
 $.fn.replaceWithPush = function(a) {
    var $a = $(a);
    this.replaceWith($a);
    return $a;
};
 
/*
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
            onSuccess: function(url, data) {},
            onError: function(code){},
            onProgress: function(index, loaded, total) {
                var progression = Math.round(loaded * 100 / total);
				var barre = $("#barre-progression-"+index);
				if (barre) {barre.css("width", progression+"%");}
            },
            taille: 0
        }, options);

        $('#'+settings.inputId).bind('change', function() {
			var html_barre_progression = "<div class='vignette_container_progression'><div id='barre-progression-"+settings.taille+"' class='vignette_barre_progression'></div></div>";
            vignette = vignette.replaceWithPush(html_barre_progression);
            var fd = new FormData();
            fd.append($('#'+settings.inputId).attr("name"), $('#'+settings.inputId).get(0).files[0]);
            fd.append("taille", settings.taille);

            var xhr = new XMLHttpRequest();
            xhr.addEventListener("load", function(ev) {
                var res = eval("("+ev.target.responseText+")");
                if (!res.code) {
                    settings.onError(res.code);
                    return;
                }
				var d = new Date();
				var t = d.getTime();
				var src = res.url+"?t="+t;
                var html_vignette = "<a class='vignette_apercu_lien' href='#'><img class='image_responsive' src='"+src+"' /></a>";
				vignette = vignette.replaceWithPush(html_vignette);
                settings.onSuccess(res.url, res.data);
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
			$("#input-"+taille_id).click();
		}
		return false;
	});

	/* Attachement du plugin single image upload aux boutons d'ajout media */
	$("a.vignette_plus").each(function() {
		var plus_id = $(this).attr("id");
		var taille_id = parseInt(plus_id.replace("ajout-", ""));
		if (taille_id > 0) {
			$(this).singleupload({
				action: "../petilabo/ajax/pl3_charger_image.php",
				inputId: "input-"+taille_id,
				taille: taille_id,
				onError: function(code) {
					// console.debug('error code '+res.code);
					alert("ERREUR : Code "+res.code);
				},
				onSuccess: function(url, data) {
					// $('#return_url_text').val(url);
					alert("URL : "+url);
				}
			});
		}
	});
});