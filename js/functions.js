
var $ = jQuery.noConflict();

(function($) {
	var imgList = [];
	$.extend({
		preload: function(imgArr, option) {
			var setting = $.extend({
				init: function(loaded, total) {},
				loaded: function(img, loaded, total) {},
				loaded_all: function(loaded, total) {}
			}, option);
			var total = imgArr.length;
			var loaded = 0;
			
			setting.init(0, total);
			for(var i in imgArr) {
				imgList.push($("<img />")
					.attr("src", imgArr[i])
					.load(function() {
						loaded++;
						setting.loaded(this, loaded, total);
						if(loaded == total) {
							setting.loaded_all(loaded, total);
						}
					})
				);
			}
			
		}
	});
})(jQuery);

$(document).ready(function(){
	
	$(window).scroll(function() {
		
		var embaixo= $(document).height()-$(window).height();
		var embaixo2= embaixo-10;
		var embaixo3= embaixo-200;
		
		if  ($(window).scrollTop()==embaixo){
			$(".go-top").fadeIn(300);
			//alert(1);
		}
	}); 
	
	$('.highlight').hover(
		function () {
			$(".highlight-text").fadeIn(300);
		}, 
		function () {
			$(".highlight-text").fadeOut(300);
		}
	);
	
	$("#menu_controls a").click( function() {
   		var estado= $("#header").hasClass("aberto");
		
		if (estado) {
			$("#menu").slideUp(300);
			
			setTimeout(function() {
				$("#header").removeClass("aberto");
			}, 300);
			
			
			/*
			$(".tags_list").slideDown(300);
			$(".link_tags").removeClass("closed");
			$(".link_tags").addClass("open");
			$(".link_tags").attr("title", "open");
			*/
			
		}
		else {
			$("#header").addClass("aberto");
			$("#menu").slideDown(300);
			
			/*
			$(".tags_list").slideUp(300);
			$(".link_tags").removeClass("open");
			$(".link_tags").addClass("closed");
			$(".link_tags").attr("title", "closed");
			*/
		}
		
	});
	
	$(".link_tags").click( function() {
   		var estado= $(".link_tags").attr("title");
		
		if (estado=="closed") {
			$(".tags_list").slideDown(300);
			$(".link_tags").removeClass("closed");
			$(".link_tags").addClass("open");
			$(".link_tags").attr("title", "open");
			
		}
		else {
			$(".tags_list").slideUp(300);
			$(".link_tags").removeClass("open");
			$(".link_tags").addClass("closed");
			$(".link_tags").attr("title", "closed");
		}
		
		$(".link_tags").blur();
	});
	
	var key_count_global = 0;
	var timer; // Global variable
	var r= $("#r").val();
	
	$("#busca").keyup(function(e){
		
		switch(e.keyCode) {
			case 39:
			case 37:
			break;
			
			case 38:
			navigate('up');
			break;
			
			case 40:
			navigate('down');
			break;
			
			case 13:
				if((currentUrl != '') && (currentUrl != undefined)) {
					window.location = currentUrl;
				}
				return false;
			break
			
			default:
				key_count_global++;
				clearTimeout(timer);
				timer= setTimeout(function(){ sugere(r) }, 500);
			break;
		}
	});
	
	$('a[href*=#]').click(function() {
	
	if (location.pathname.replace(/^\//,'') == this.pathname.replace(/^\//,'') 
		&& location.hostname == this.hostname) {
		
			var $target = $(this.hash);
			
			$target = $target.length && $target || $('[name=' + this.hash.slice(1) +']');
			
			if ($target.length) {
			
				var targetOffset = $target.offset().top;
				
				$('html,body').animate({scrollTop: targetOffset}, 750);
					
				return false;
				
			}
			
		}
		
	});
	
});



function carregaDinamico(elemento, r, area, modo, inicio, total_por_pagina, tags, excluir, tipo_projeto, ultimo_id) {
	if (area=="works") chamada= "carregaDinamicoWorks";
	else if (area=="artists") chamada= "carregaDinamicoArtists";
	
	elemento.blur();
	
	$("#link_leva_"+inicio).html("<img src=\""+r+"images/loading.gif\" border=\"0\" src=\"\" />");
	
	$.get(r+'link.php', {chamada: chamada, r: r, modo: modo, inicio: inicio, total_por_pagina: total_por_pagina, tags: tags, excluir: excluir, tipo_projeto: tipo_projeto },

	function(retorno) {
		$("#leva_"+inicio).html(retorno);
		
		if ((ultimo_id!="") && (modo=="2")) {
			setaClasse(ultimo_id, "list-open");
		}
	});
}

currentSelection = 0;
currentUrl = '';

function sugere(r) {
	var busca= $("#busca").val();
	
	
	$("#busca").blur(function(){
	 	$('#sugestoes').fadeOut();
	});
	 
	 // Safely inject CSS3 and give the search results a shadow
	//var cssObj = { 'box-shadow' : '#333 25px 5px 5px', // Added when CSS3 is standard
	//	'-webkit-box-shadow' : '#333 25px 5px 5px', // Safari
	//	'-moz-box-shadow' : '#333 25px 5px 5px'}; // Firefox 3.5+
	//$("#sugestoes").css(cssObj);
	 
	if(busca.length==0) {
		$('#sugestoes').fadeOut(); // Hide the sugestoes box
	}
	else {
		
		$('#busca-area').attr("class", "buscando");
		
		$.post(r+"link.php", {chamada: "buscaSugerida", r: r, busca: busca }, function(retorno) { // Do an AJAX call
			$('#sugestoes').html(retorno); // Fill the sugestoes box
			$('#sugestoes').fadeIn("slow"); // Show the sugestoes box
			
			$('#busca-area').attr("class", "");
			
			// Register keypress events on the whole document
			//$("#busca").keypress(function(e) {
				
				//$("#nada").html(e.keyCode);
				
				/*switch(e.keyCode) { 
					// User pressed "up" arrow
					case 38:
						navigate('up');
						
						//return false;
					break;
					// User pressed "down" arrow
					case 40:
						$("#busca").blur();
						
						navigate('down');
						
						//return false;
					break;
					// User pressed "enter"
					case 13:
						if(currentUrl != '') {
							window.location = currentUrl;
						}
						
						//return false;
					break;
				}*/
				
			//});
			
			// Add data to let the hover know which index they have
			for(var i=0; i<$("ul#resultado li.item a").size(); i++) {
				$("ul#resultado li a").eq(i).data("number", i);
			}
			
			// Simulote the "hover" effect with the mouse
			$("ul#resultado li.item a").hover(
				function () {
					currentSelection= $(this).data("number");
					setSelected(currentSelection);
					
					//$("#nada").html("2: "+currentSelection);
				}, function() {
					//$("ul#resultado li.item a").removeClass("itemhover");
					//currentUrl = '';
				}
			);
		});
	}
}

function navigate(direction) {
	
	
	
	//$("#nada").html("1: "+currentSelection);
	
	// Check if any of the menu items is selected
	if($("ul#resultado li.item .itemhover").size()==0) {
		currentSelection= -1;
		
		//$("#nada").html("nada");
	}
	
	//$("#nada").html($("ul#resultado li.item .itemhover").size());
	
	//$("#nada").html(direction);
	
	if(direction == 'up') {
		//if (currentSelection != -1) {
			//if(currentSelection != 0) {
				//currentSelection--;
			//}
		//}
		
		//$("#nada").html(currentSelection);
		var aux_var= currentSelection;
		
		//$("#nada").html(currentSelection);
		
		if ((currentSelection==-1) || (currentSelection==0)) {
			currentSelection= $("ul#resultado li.item").size()-1;
		}
		else {
			currentSelection--;
		}
		
		//$("#nada").html(aux_var+" -> "+currentSelection);
	}
	else if (direction == 'down') {
		
		var aux_var= currentSelection;
		
		if(currentSelection != $("ul#resultado li.item").size()-1) {
			currentSelection++;
		}
		else currentSelection=0;
		
		//$("#nada").html(aux_var+" -> "+currentSelection);
	}
	
	setSelected(currentSelection);
}

function setSelected(menuitem) {
	$("ul#resultado li.item a").removeClass("itemhover");
	$("ul#resultado li.item a").eq(menuitem).addClass("itemhover");
	currentUrl= $("ul#resultado li.item a").eq(menuitem).attr("href");
	
	//$("#nada").html("current: "+menuitem);
}

function g(quem) {
	return document.getElementById(quem);
}

function setaClasse(campo, classe) {
	try {
		g(campo).className= classe;
	}
	catch (eee) { }
}