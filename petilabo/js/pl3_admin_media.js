/*
 * JS PL3 mode administration media
 */
 
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
        var $this = this;
        var inputfile = null;
        var settings = $.extend({
            action: '#',
            onSuccess: function(url, data) {},
            onError: function(code){},
            onProgress: function(loaded, total) {
                var percent = Math.round(loaded * 100 / total);
                $this.html(percent + '%');
            },
            name: 'img'
        }, options);

        $('#'+settings.inputId).bind('change', function() {
            $this.css('backgroundImage', 'none');
            var fd = new FormData();
            fd.append($('#'+settings.inputId).attr('name'), $('#'+settings.inputId).get(0).files[0]);
            fd.append("name", settings.name);

            var xhr = new XMLHttpRequest();
            xhr.addEventListener("load", function(ev) {
                $this.html('');
                var res = eval("("+ev.target.responseText+")");
                if (res.code != 0) {
                    settings.onError(res.code);
                    return;
                }
				var d = new Date();
				var t = d.getTime();
				var src = res.url+"?t="+t;
                var review = ('<img src="'+src+'" style="width:'+$this.width()+'px;height:'+$this.height()+'px;"/>');
                $this.append(review);
                settings.onSuccess(res.url, res.data);
            },
            false);
            xhr.upload.addEventListener("progress", function(ev) {
                settings.onProgress(ev.loaded, ev.total);
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
				name: "img-"+taille_id,
				onError: function(code) {
					// console.debug('error code '+res.code);
					alert("ERREUR : Code "+res.code);
				},
				onSuccess: function(url, data) {
					// $('#return_url_text').val(url);
					alert("URL : "+url);
				},
				onProgress: function(loaded, total) {
					$('#uploadinfo').html(loaded+"/"+total);
				}
			});
		}
	});
});