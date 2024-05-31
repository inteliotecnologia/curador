/* FUNCOES PARA VALIDAR VALORES GENÉRICOSSSS */

$(document).ready(function() {

	$("legend a").click(function() {
		var idf= $(this).attr("rel");
		
		if ($("#"+idf).hasClass("aberto")) {
			$("#"+idf).removeClass("aberto");
			$("#"+idf).addClass("fechado");
			$("#"+idf).slideUp();
		}
		else {
			$("#"+idf).removeClass("fechado");
			$("#"+idf).addClass("aberto");
			$("#"+idf).slideDown();
		}
	});
	
	/*
	if ($("legend a #bloco_"+pai_id[2]).hasClass("aberto")) {
		$("#bloco_"+pai_id[2]).removeClass("aberto");
		$("#bloco_"+pai_id[2]).slideUp("fast");
		
		$(".bloco_oculto_fecha").fadeOut("fast");
	}
	else {
		$(".blocos_ocultos .bloco_oculto").attr("style", "display:none;");
		$(".blocos_ocultos .bloco_oculto").removeClass("aberto");
		
		$("#bloco_"+pai_id[2]).addClass("aberto");
		$("#bloco_"+pai_id[2]).slideDown("fast");
		
		$(".bloco_oculto_fecha").fadeIn("fast");
	}
*/
});

function g(quem) {
	return document.getElementById(quem);
}

function apagaLinha(chamada, id, tipo) {
	$.get('link.php', {chamada: chamada, id: id, tipo: tipo },
	
	function(retorno) {
		if (retorno=="0") {
			
			$("#linha_"+id).addClass("bg_excluido");
			$("#linha_"+id).fadeOut("fast");
			//alert("Excluído com sucesso!");
			
			//$("#tela_mensagens_acoes").html("Excluído com sucesso!");
			//$("#tela_mensagens_acoes").fadeIn("slow");
			//$("#tela_mensagens_acoes").delay(3000).fadeOut(500);
			
		}
		else alert("Não foi possível excluir!"+ retorno);
	});

}

function apagaArquivo(id_pessoa, src) {

	$.get('link.php', {chamada: "arquivoExcluir", src: src, id_pessoa: id_pessoa },

	function(retorno) {
		if (retorno!="0") alert("Não foi possível excluir o arquivo!");
		else {
			$("#foto_area").html("Foto excluída.");
		}
	});
}

function meuFadeOut(campo) {
	$("#"+campo).fadeOut("fast");
}

function meuFadeIn(campo) {
	$("#"+campo).fadeIn("fast");
}

function ajustaLinkPdf(id_curadoria, i) {
	$("#gera_pdf").attr("href", "index2.php?pagina=financeiro/curadoria_pdf&id_curadoria="+id_curadoria+"&parte="+i);
}

function zoomImagem(zoom) {
	
	var campo= $("#jcrop_tamanho");
	var largura= parseInt(campo.attr("width"));
	var altura= parseInt(campo.attr("height"));
	
	zoom= parseFloat(zoom);
	
	var nova_largura= largura*zoom;
	var nova_altura= altura*zoom;
	
	var campo1= $("#cropbox_area img");
	campo1.attr("width", nova_largura);
	campo1.attr("height", nova_altura);
	campo1.css("width", nova_largura);
	campo1.css("height", nova_altura);
	
	var campo2= $(".jcrop-holder");
	campo2.css("width", nova_largura);
	campo2.css("height", nova_altura);
	
	var campo3= $(".jcrop-tracker");
	campo3.css("width", nova_largura+16);
	campo3.css("height", nova_altura+16);
}

function adicionaTagCampo(check, campo_str, tag, replicar) {
	if (replicar=="0") {
		
		var campo= g(campo_str);
		var campo_valor= campo.value;
		var campo_novo_valor= campo_valor;
		
		var posicao= campo_valor.indexOf(tag+", ");
	
		//está checando para inserir e a tag não está na lista
		if ((check.checked) && (posicao==-1)) {
			campo_novo_valor= campo.value+tag+", ";
		}
		else {
			//está deschecando
			if (!check.checked) {
				campo_novo_valor= campo_novo_valor.replace(tag+", ", "");
			}
		}
		
		campo.value= campo_novo_valor;
	}
	else {
		var campo1= g(campo_str);
		var campo2= g(campo_str.replace("_pt", "_en"));
		
		var campo1_valor= campo1.value;
		var campo2_valor= campo2.value;
		
		var campo1_novo_valor= campo1_valor;
		var campo2_novo_valor= campo2_valor;
		
		var tag_nova= tag.split("|");
		
		var posicao1= campo1_valor.indexOf(tag_nova[0]+", ");
		var posicao2= campo2_valor.indexOf(tag_nova[1]+", ");
		
		//está checando para inserir e a tag não está na lista
		if ((check.checked) && (posicao1==-1)) {
			campo1_novo_valor= campo1.value+tag_nova[0]+", ";
		}
		else {
			//está deschecando
			if (!check.checked) {
				campo1_novo_valor= campo1_novo_valor.replace(tag_nova[0]+", ", "");
			}
		}
		
		campo1.value= campo1_novo_valor;
		
		//está checando para inserir e a tag não está na lista
		if ((check.checked) && (posicao2==-1)) {
			campo2_novo_valor= campo2.value+tag_nova[1]+", ";
		}
		else {
			//está deschecando
			if (!check.checked) {
				campo2_novo_valor= campo2_novo_valor.replace(tag_nova[1]+", ", "");
			}
		}
		
		campo2.value= campo2_novo_valor;
		
	}
	
	
}

function inverte_1_0(num) {
	if (num=="1") return(0);
	else return(1);
}

function marcaImagemCuradoria(elemento, id_imagem, id_pessoa, id_projeto, id_curadoria) {
	var rotina;
	if (elemento.checked) rotina=1;
	else rotina=0;
	
	$.get('link.php', {chamada: "marcaImagemCuradoria", id_imagem: id_imagem, id_pessoa: id_pessoa, id_projeto: id_projeto, id_curadoria: id_curadoria, rotina: rotina },

	function(retorno) {
		if (retorno!="0") alert("Não foi possível marcar a imagem!");
	});
}

function atualizaProjetoNovaPessoa(div, comando) {
	$.get('link.php', {chamada: comando },

	function(retorno) {
		$("#"+div).html(retorno);
	});
}

function abreFechaArtistasObrasCuradoria(id_pessoa, id_projeto, id_curadoria) {
	var seletor= $("#artista_"+id_pessoa).attr("title");
	
	if (seletor=="fechado") {
		$("#artista_obras_"+id_pessoa).slideDown(300);
		$("#artista_"+id_pessoa).attr("title", "aberto");
		
		$("#artistas_selecionados").sortable("option", "cancel", "#artista_"+id_pessoa);
		
		$("#artista_obras_"+id_pessoa).html("<img src='images/loading_cinza_claro.gif' alt='' />");
		
		$.get('link.php', {chamada: "carregaImagensArtistaCuradoria", id_pessoa: id_pessoa, id_projeto: id_projeto, id_curadoria: id_curadoria },
	
		function(retorno) {
			$("#artista_obras_"+id_pessoa).html(retorno+"<br />");
		});
		
	}
	else {
		$("#artista_obras_"+id_pessoa).attr("style", "display:none");
		
		$("#artistas_selecionados").sortable("option", "cancel", "");
		
		$("#artista_"+id_pessoa).attr("title", "fechado");
	}
}

function excluiArtistaCuradoria(id_pessoa, id_projeto, id_curadoria) {
	
	$.get('link.php', {chamada: "excluiArtistaCuradoria", id_pessoa: id_pessoa, id_projeto: id_projeto, id_curadoria: id_curadoria },
	
	function(retorno) {
		if (retorno=="0") {
			removeDiv('artistas_selecionados', 'artista_'+id_pessoa);
		}
		else alert("Não foi possível excluir!");
	});
	
	
}

function adicionaArtistaCuradoria(id_pessoa, nome, id_projeto, id_curadoria) {
	var busca2= $("#busca").val();
	
	$.get('link.php', {chamada: "adicionaArtistaCuradoria", id_pessoa: id_pessoa, id_projeto: id_projeto, id_curadoria: id_curadoria },
	
	function(retorno) {
		if (retorno=="0") {
			$("#artistas_selecionados").append("<div title='fechado' id='artista_"+id_pessoa+"'><div class='artista_linha'><div class='parte80'><a href=\"javascript:void(0);\" onclick=\"abreFechaArtistasObrasCuradoria('"+id_pessoa+"', '"+id_projeto+"', '"+id_curadoria+"');\">"+nome+"</a></div><div class='parte20 alinhar_direita'><a href=\"javascript:void(0);\" onclick=\"excluiArtistaCuradoria('"+id_pessoa+"', '"+id_projeto+"', '"+id_curadoria+"');\"><img src=\"images/ico_lixeira.png\" /></a></div><br /></div><div id='artista_obras_"+id_pessoa+"' class='artista_obras' style='display:none;'></div></div>");
		}
		else alert("Não foi possível adicionar!");
	});
}

function carregaObrasArtistaTamanho(id, id_pessoa, tamanho_imagem, id_projeto, id_curadoria) {
	$("#imagens_curadoria_atualiza_"+id_pessoa).html("<img src='images/loading_cinza_claro.gif' alt='' />");
	
	atribuiAbaAtual(id, "aba_imagens_tamanhos_"+id_pessoa);
	
	$.get('link.php', {chamada: "carregaObrasArtistaTamanho", id_pessoa: id_pessoa, tamanho_imagem: tamanho_imagem, id_projeto: id_projeto, id_curadoria: id_curadoria },
	
	function(retorno) {
		$("#imagens_curadoria_atualiza_"+id_pessoa).html(retorno);
	});
}

function buscaArtistaCuradoria(id_projeto, id_curadoria) {
	var busca2= $("#busca").val();
	var curadoria_tags= document.getElementById("curadoria_tags");
	var campos= curadoria_tags.getElementsByTagName("input");
	var url_tags="";
	
	for (i=0; i<campos.length; i++) {
		if ((campos[i].type=="checkbox") && (campos[i].checked)) {
			url_tags+=campos[i].value+",";
		}
	}
	
	$("#artistas_lista").html("<img src='images/loading_cinza.gif' alt='' />");
	
	$.get('link.php', {chamada: "buscaArtistaCuradoria", busca: busca2, id_projeto: id_projeto, id_curadoria: id_curadoria, tags: url_tags },
	
	function(retorno) {
		$("#artistas_lista").html(retorno);
	});
}

function situacaoLinha(chamada, id, status, tipo) {
	$.get('link.php', {chamada: chamada, id: id, status: status, tipo: tipo },
	
	function(retorno) {
		if (retorno=="0") {
			alert("Situação alterada com sucesso!");
			$("#situacao_link_"+id).attr("src", "images/ico_"+inverte_1_0(status)+".png");
			//$("#linha_"+id).fadeOut("slow");
			//alert("Excluído com sucesso!");
			
			//$("#tela_mensagens_acoes").html("Excluído com sucesso!");
			//$("#tela_mensagens_acoes").fadeIn("slow");
			//$("#tela_mensagens_acoes").delay(3000).fadeOut(500);
		}
		else alert("Não foi possível alterar a situação!");
	});

}

function formata_saida(valor, tamanho_saida) {
	valor+="";
	var tamanho= valor.length;
	var saida="";
	
	for (var i=tamanho; i<tamanho_saida; i++)
		saida+='0';
	
	return(saida+valor);
}

function pegaClienteMultiplo(local, cont, codigo) {
	try {
		var id_remessa= g("id_remessa").value;
	}
	catch ( eee ) {
		var id_remessa= 0;
	}
	
	if (codigo!="") {
		ajaxLink("cliente_atualiza_"+local+"_"+cont, "pegaClienteMultiplo&local="+local+"&cont="+cont+"&codigo="+codigo+"&id_remessa="+id_remessa);
		
		if ((local==1) && (cont==1)) {
			atribuiValor("cliente_2_"+cont, codigo);
			ajaxLink("cliente_atualiza_2_"+cont, "pegaClienteMultiplo&local="+local+"&cont="+cont+"&codigo="+codigo+"&id_remessa="+id_remessa);
		}
	}
	else {
		habilitaCampo("data_remessa");
		habilitaCampo("num_remessa");
	}
}

function pegaCliente(codigo) {
	//alert(tecla);
	try {
		var id_remessa= g("id_remessa").value;
	}
	catch ( eee ) {
		var id_remessa= 0;
	}
	
	if (codigo!="")
		ajaxLink("cliente_atualiza", "pegaCliente&codigo="+codigo+"&id_remessa="+id_remessa);
	else {
		habilitaCampo("data_remessa");
		habilitaCampo("num_remessa");
	}
}

function ajeitaMascaraTelefone(campo) {
	var valor= campo.value;
	
	if ((valor.length==3) && (valor=="(08")) {
		campo.value= "08";
		campo.setAttribute("onkeypress", "return formataCampoVarios(form, this.id, '9999-999-9999', event);");
		campo.setAttribute("maxlength", "13");
	}
	else {
		if ((valor.length==3) && (valor=="(40")) {
			campo.value= "40";
			campo.setAttribute("onkeypress", "return formataCampoVarios(form, this.id, '9999-9999', event);");
			campo.setAttribute("maxlength", "9");
		}
		else {
			if (valor=="") {
				campo.setAttribute("onkeypress", "return formataCampoVarios(form, this.id, '(99) 9999-9999', event);");
				campo.setAttribute("maxlength", "14");
			}
		}
	}
}

function removeDiv(dpai, did) {
	var pai= g(dpai);
	var filho= g(did);
	pai.removeChild(filho);
}

function zoomPaginasCuradoria(id_projeto, id_curadoria, zoom) {
	window.top.location.href="./?pagina=financeiro/curadoria_passo2&id_projeto="+id_projeto+"&id_curadoria="+id_curadoria+"&zoom="+zoom;
}

function pulaParaPagina(parametros, campo) {
	if ((sohNumeros(campo.value)) && (campo.value>0)) {
		var pagina_real= campo.value-1;
		
		window.top.location.href=parametros+pagina_real;
	}
	else alert("Digite uma página válida!");
}

function alteraTipoContatoPessoa(tipo_pessoa) {
	if (tipo_pessoa!="o") ajaxLink("div_id_pessoa", "chamada=alteraTipoContatoPessoa&tipo_pessoa="+tipo_pessoa);
	else $("#div_id_pessoa").html("");
}

function alteraContatos(div, id, label, id_pessoa) {
	if (id_pessoa!="") ajaxLink(div, "chamada=alteraContatos&id="+id+"&label="+label+"&id_pessoa="+id_pessoa);
	else g(div).innerHTML= "";
}

function criaEspacoVideo(tipo_imagem, id_externo) {
	var videos= g("videos");
	var num_divs = document.getElementsByTagName("code");
	var cont= parseInt(num_divs.length+1);
	var largura, altura;
	
	var div_video= document.createElement("div");
	div_video.className= "form_alternativo form_drag";
	
	if (tipo_imagem=='a') {
		largura=620;
		altura=350;	
	}
	else {
		largura=940;
		altura=530;
	}
	
	$.get('link.php', {chamada: "criaEspacoVideoAjax", tipo_imagem: tipo_imagem, id_externo: id_externo },
	
	function(retorno) {
		var parte= retorno.split("@@@");
		
		if (parte[0]=="0") {
			div_video.id= "div_video_"+parte[1];
			div_video.innerHTML= '<code class="escondido"></code>'
								+'<div class="parte50">'
								
								+'<label class="tamanho40" for="url_'+parte[1]+'">URL:</label>'
								+'<input title="URL" name="url[]" id="url_'+parte[1]+'" value="" /><br />'
								
								+'<div class="parte25"><label class="tamanho40" for="largura_'+parte[1]+'">Largura:</label>'
								+'<input class="tamanho70" name="largura[]" id="largura_'+parte[1]+'" value="'+largura+'" /></div>'
								+'<div class="parte25"><label class="tamanho40" for="altura_'+parte[1]+'">Altura:</label><input class="tamanho70" name="altura[]" id="altura_'+parte[1]+'" value="'+altura+'" /></div><br />'
								
								+'<label class="tamanho40" for="site_'+parte[1]+'">Site:</label>'
								+'<input type="checkbox" class="tamanho30" title="Site" name="site[]" id="site_'+parte[1]+'" value="1" />'
								
								+'<br /><br /><a class="telefone_remover link_remover" href="javascript:void(0);" onclick="removeDiv(\'videos\', \'div_video_'+parte[1]+'\');">remover</a><br />'
								
								+'</div>'
								+'<div class="parte50">'
								+''
								+'</div><br />';
			
			videos.appendChild(div_video);
			
			$(function(){
				$("#div_video_"+cont+" input:text, #div_video_"+cont+" input:checkbox").uniform();
			  });
			
			daFoco("url_"+cont);
		}
		else alert("Não foi possível adicionar!"+ retorno);
	});
}

function criaEspacoTelefone() {
	var telefones= g("telefones");
	var num_divs = document.getElementsByTagName("code");
	var cont= parseInt(num_divs.length+1);
	
	var div_telefone= document.createElement("div");
	div_telefone.id= "div_telefone_"+cont;
	
	div_telefone.innerHTML= '<code class="escondido"></code>'
						
						+'<a class="telefone_remover" href="javascript:void(0);" onclick="removeDiv(\'telefones\', \'div_telefone_'+cont+'\');">remover</a>'
						
						+'<label class="tamanho80" for="telefone_'+cont+'">Telefone '+cont+':</label>'
						+'<input class="tamanho25p" title="Telefone" name="telefone[]" id="telefone_'+cont+'" value="" />'
						
						+'<select class="tamanho25p" name="tipo[]" id="tipo_'+cont+'">'
							+'<option value="1">Casa</option>'
							+'<option value="2" class="cor_sim">Trabalho</option>'
							+'<option value="3">Celular</option>'
							+'<option value="4" class="cor_sim">Fax</option>'
							+'<option value="6">Estúdio</option>'
							+'<option value="5" class="cor_sim">Outros</option>'
						+'</select>';
						
						//+'<label class="tamanho80" for="obs_'+cont+'">OBS:</label>'
						//+'<input class="tamanho25p espaco_dir" title="Observação" name="obs[]" id="obs_'+cont+'" />'
							
	telefones.appendChild(div_telefone);
	
	$(function(){
        $("#div_telefone_"+cont+" input:text, #div_telefone_"+cont+" select").uniform();
      });
	
	daFoco("telefone_"+cont);
}

function criaEspacoProjetoContato() {
	var projeto_contatos= g("projeto_contatos");
	var num_divs = document.getElementsByTagName("code");
	var cont= parseInt(num_divs.length+1);
	
	var div_contato= document.createElement("div");
	div_contato.id= "div_projeto_contato_"+cont;
	
	div_contato.innerHTML= '<code class="escondido"></code>'
						+'<label for="contato_'+cont+'">Contato/função '+cont+':</label>'
						+'<input class="tamanho25p" title="Contato" name="contato[]" id="contato_'+cont+'" value="" />'
						+'<input class="tamanho25p" title="Função" name="funcao[]" id="funcao_'+cont+'" value="" />'
						
						+'<br /><label>&nbsp;</label><a href="javascript:void(0);" onclick="removeDiv(\'projeto_contatos\', \'div_projeto_contato_'+cont+'\');">remover</a><br />';
	
	projeto_contatos.appendChild(div_contato);
	
	$(function(){
        $("#div_projeto_contato_"+cont+" input:text").uniform();
      });
	
	daFoco("contato_"+cont);
}

function criaEspacoNotaCuradoria(id_pessoa) {
	var filhos= g("notas_curadoria_"+id_pessoa);
	var num_divs = document.getElementsByTagName("code");
	var cont= parseInt(num_divs.length+1);
	
	var div_filho= document.createElement("div");
	div_filho.id= "div_nota_"+id_pessoa+"_"+cont;
	
	div_filho.innerHTML= '<code class="escondido"></code>'
						+'<input name="id_pessoa[]" type="hidden" class="escondido" value="'+id_pessoa+'" />'
						+'<label class="tamanho100 alinhar_esquerda" for="nota_curadoria_pessoa_'+id_pessoa+'_'+cont+'">Nota:</label>'
						+'<input name="nota_curadoria_pessoa[]" id="nota_curadoria_pessoa_'+id_pessoa+'_'+cont+'" />'
						+'<a href="javascript:void(0);" onclick="removeDiv(\'notas_curadoria_'+id_pessoa+'\', \'div_nota_'+id_pessoa+'_'+cont+'\');">remover</a><br />';
						
	
	filhos.appendChild(div_filho);
	
	daFoco("nota_curadoria_pessoa_"+id_pessoa+"_"+cont);
}

function criaEspacoFilho() {
	var filhos= g("filhos");
	var num_divs = document.getElementsByTagName("code");
	var cont= parseInt(num_divs.length+1);
	
	var div_filho= document.createElement("div");
	div_filho.id= "div_filho_"+cont;
	
	div_filho.innerHTML= '<div class="parte33"><code class="escondido"></code>'
						+'<label for="nome_filho_'+cont+'">Nome:</label>'
						+'<input class="" title="Filho" name="nome_filho[]" id="nome_filho_'+cont+'" value="" /><br />'
						
						+'<label for="sexo_filho_'+cont+'">Sexo:</label>'
						+'<select class="" name="sexo_filho[]" id="sexo_filho_'+cont+'">'
							+'<option value="m">Masculino</option>'
							+'<option value="f" class="cor_sim">Feminino</option>'
						+'</select><br />'
						
						+'<label for="data_nasc_filho_'+cont+'">Data de nascimento:</label>'
						+'<input class="" title="Data de nascimento" onkeyup="formataData(this);" maxlength="10" name="data_nasc_filho[]" id="data_nasc_filho_'+cont+'" /><br />'
						
						+'<label>&nbsp;</label>'
						+'<a href="javascript:void(0);" onclick="removeDiv(\'filhos\', \'div_filho_'+cont+'\');">remover</a><br /><br /></div>';
	
	filhos.appendChild(div_filho);
	
	daFoco("nome_filho_"+cont);
}

function itemBusca(cont) {
	var campo= g("item_"+cont).value;
	
	if (campo.length>=3) {
		var div_atualiza= "item_atualiza_"+cont;
		
		if ((campo!="") && (campo.length>=3)) ajaxLink(div_atualiza, "itemPesquisar&origem=e&modo=select&pesquisa="+campo+"&cont="+cont);
		else {
			alert("Entre com pelo menos 3 caracteres para fazer a busca!");
			daFoco("item_"+cont);
		}
	}
}

function processaDecimalUnico() {
	var id_item= g("id_item");
	var indice= id_item.selectedIndex;
	var texto= id_item[indice].text;
	var apres= texto.substr(-3);
	
	if (apres=="lt.") habilitaFormatacaoDecimal(1, "qtde");
	else habilitaFormatacaoDecimal(0, "qtde");
}

function processaDecimal(cont) {
	var id_item= g("id_item_"+cont);
	var indice= id_item.selectedIndex;
	var texto= id_item[indice].text;
	var apres= texto.substr(-3);
	
	if (apres=="lt.") habilitaFormatacaoDecimal(1, "qtde_"+cont);
	else habilitaFormatacaoDecimal(0, "qtde_"+cont);
}

function habilitaFormatacaoDecimal(opcao, campo) {
	var campo= g(campo);
	
	if (opcao==1) campo.setAttribute("onkeydown", "formataValor(this,event)");
	else campo.setAttribute("onkeydown", "");
}

function checarDeschecarTudo(modo, local) {
	if (local=="tudo")
		var campos= document.getElementsByTagName("input");
	else {
		var local= g(local);
		var campos= local.getElementsByTagName("input");
	}
	
	for (i=0; i<campos.length; i++) {
		//alert(campos[i].type);
		if (campos[i].type=="checkbox") {
			if (modo=="1") {
				campos[i].click();
				//campos[i].checked= true;
			}
			else campos[i].checked= false;
		}
	}
}

function excluiImagensSelecionadas(local) {
	var cont=0;
	
	if (local=="tudo")
		var campos= document.getElementsByTagName("input");
	else {
		var local= g(local);
		var campos= local.getElementsByTagName("input");
	}
	
	for (i=0; i<campos.length; i++) {
		if ((campos[i].type=="checkbox") && (campos[i].checked==true)) {
			cont++;
		}
	}
	
	if (cont>0) {
		var confirma= confirm("Tem certeza que deseja apagar as imagens selecionadas?");
		if (confirma) {
			for (i=0; i<campos.length; i++) {
				if ((campos[i].type=="checkbox") && (campos[i].checked==true)) {
					apagaLinha('imagemExcluir', campos[i].value);
				}
			}
		}
	}
	else alert("Nenhuma imagem selecionada!");
}

function removeImagemSite(chamada, id) {
	$.get('link.php', {chamada: chamada, id: id, site: "0" },
	
	function(retorno) {
		var parte= retorno.split("@@@");
		
		if (parte[0]=="0") {
			
			$("#div_imagem_site_"+id).html('');
			$("#div_imagem_site_"+id).fadeOut("slow");
			
		}
		else alert("Não foi possível excluir!"+ retorno);
	});

}

function marcaImagensParaSite(local, modo) {
	var cont=0;
	
	if (local=="tudo")
		var campos= document.getElementsByTagName("input");
	else {
		var local= g(local);
		var campos= local.getElementsByTagName("input");
	}
	
	for (i=0; i<campos.length; i++) {
		if ((campos[i].type=="checkbox") && (campos[i].checked==true)) {
			cont++;
		}
	}
	
	if (cont>0) {
		var confirma= 1;//confirm("Tem certeza que deseja remover as imagens do site?");
		if ((confirma==1) && (modo=="0") || (modo=="1")) {
			for (i=0; i<campos.length; i++) {
				if ((campos[i].type=="checkbox") && (campos[i].checked==true)) {
					//apagaLinha('imagemExcluir', campos[i].value);
					
					
					$.get('link.php', {chamada: 'imagemSite', id: campos[i].value, site: modo },
	
					function(retorno) {
						var parte= retorno.split("@@@");
						
						if (parte[0]=="0") {
							if (modo=="1") {
								$("#div_imagem_site_"+parte[1]).html("<a class=\"imagem_site\" id=\"link_site_"+parte[1]+"\" href=\"javascript:removeImagemSite('imagemSite', '"+parte[1]+"');\">site</a>");
								$("#div_imagem_site_"+parte[1]).fadeIn("fast");							
							}
							else {
								$("#div_imagem_site_"+parte[1]).html('');
								$("#div_imagem_site_"+parte[1]).fadeOut("slow");
							}
						}
						else alert("Não foi possível alterar!"+ retorno);						
							
					});
					
					
				}
			}
		}
	}
	else alert("Nenhuma imagem selecionada!");
}

function enviaImagensParaTopo(local, id_pessoa, id_projeto) {
	var cont=0;
	
	var local= g(local);
	var campos= local.getElementsByTagName("input");
	var qtde= campos.length;
	var j=0;
	
	for (i=0; i<qtde; i++) {
		if ((campos[i].type=="checkbox") && (campos[i].checked==true)) {
			cont++;
		}
	}
	
	var ordem= cont+1;
	var ordem_str;
	
	if (cont>0) {
		var confirma= 1;//confirm("Tem certeza que deseja remover as imagens do site?");
		if (confirma==1) {
			for (i=0; i<qtde; i++) {
				
				
				if ((campos[i].type=="checkbox") && (campos[i].checked==true)) {
					//apagaLinha('imagemExcluir', campos[i].value);
					
					ordem--;
					ordem_str= '-'+ordem;
					
					$.get('link.php', {chamada: 'atualizaOrdemImagens2', id: campos[i].value, ordem: ordem_str },
	
					function(retorno) {
						
						j++;
						
						//alert(j+" - "+cont);
						
						if (j==cont) {
							if (id_pessoa!="") window.top.location.href="./?pagina=financeiro/imagens2&tipo_pessoa=r&id_pessoa="+id_pessoa;
							else window.top.location.href="./?pagina=financeiro/imagens2&id_projeto="+id_projeto;
						}
						
					});
				}
			}
		}
	}
	else alert("Nenhuma imagem selecionada!");
	
	
}

function alteraTipoPessoa(tipo_pessoa, acao) {
	ajaxLink("tipo_pessoa_atualiza", "alteraTipoPessoa&tipo_pessoa="+tipo_pessoa+"&acao="+acao);
}

function desabilitaTudo() {
	var campos_select= document.getElementsByTagName("select");
	for (i=0; i<campos_select.length; i++) {
		if (campos_select[i].className!="escondido") {
			campos_select[i].className= campos_select[i].className+" desativado campo_rel";
			campos_select[i].disabled= true;
		}
	}
	var campos_input= document.getElementsByTagName("input");
	for (i=0; i<campos_input.length; i++) {
		if (campos_input[i].className!="escondido") {
			campos_input[i].className= campos_input[i].className+" desativado campo_rel";
			campos_input[i].disabled= true;
		}
	}
	var campos_textarea= document.getElementsByTagName("textarea");
	for (i=0; i<campos_textarea.length; i++) {
		if (campos_textarea[i].className!="escondido") {
			campos_textarea[i].className= campos_textarea[i].className+" desativado campo_rel";
			campos_textarea[i].disabled= true;
		}
	}
}

function setaClasse(campo, classe) {
	try {
		g(campo).className= classe;
	}
	catch (eee) { }
}

function cadastraNovoTipoPessoa(id_pessoa, tipo_pessoa) {
	var confirma= confirm("Tem certeza que deseja associar\nesta pessoa nesta nova categoria?");
	
	if (confirma)
		ajaxLink("conteudo", "cadastraNovoTipoPessoa&id_pessoa="+id_pessoa+"&tipo_pessoa="+tipo_pessoa);
}

function ajustaPais(id_pais) {
	if (id_pais==32) {
		abreDiv("brasil");
		fechaDiv("internacional");
	}
	else {
		abreDiv("internacional");
		fechaDiv("brasil");
	}
}

function atribuiAbaAtual(id_elemento, local) {
	//alert(id_elemento+"|"+local);
	var menu= g(local);
	var itens= menu.getElementsByTagName("li");
	
	for (i=0; i<itens.length; i++) {
		if (itens[i].id==id_elemento) {
			itens[i].className= "atual";
			
			var link_dentro= itens[i].getElementsByTagName("a");
			link_dentro[0].blur();
		}
		else itens[i].className= "";
	}
}

function ajeitaTecla(evtKeyPress) {
	if (document.all) { // Internet Explorer
		nTecla = evtKeyPress.keyCode;
		} else if(document.layers) { // Nestcape
			nTecla = evtKeyPress.which;
		} else {
			nTecla = evtKeyPress.which;
			if (nTecla == 8) {
				return true;
			}
		}
	
	if (((nTecla > 47) && (nTecla < 58)) || (nTecla==0) || (nTecla==8))
		return(true);
	else
		return(false);
}

function sohNumeros(numero) {
	var nonNumbers = /\D/;
	if (nonNumbers.test(numero))
		return(false);
	else
		return(true);
}

function limpaValor(valor, validos) {
	var result = "";
	var aux;
	for (var i=0; i < valor.length; i++) {
		aux = validos.indexOf(valor.substring(i, i+1));
		if (aux>=0)
			result += aux;
	}
	return result;
}

//onkeydown="formataValor(this,event);"
function formataValor(campo, teclapres) {
	var tammax = 200;
	var decimal = 2;
	var tecla = teclapres.keyCode;
	vr = limpaValor(campo.value,"0123456789");
	tam = vr.length;
	dec=decimal
	
	if (tam < tammax && tecla != 8){ tam = vr.length + 1 ; }
	
	if (tecla == 8 )
	{ tam = tam - 1 ; }
	
	if ( tecla == 8 || tecla >= 48 && tecla <= 57 || tecla >= 96 && tecla <= 105 )
	{
	
	if ( tam <= dec )
	{ campo.value = vr ; }
	
	if ( (tam > dec) && (tam <= 5) ){
	campo.value = vr.substr( 0, tam - 2 ) + "," + vr.substr( tam - dec, tam ) ; }
	if ( (tam >= 6) && (tam <= 8) ){
	campo.value = vr.substr( 0, tam - 5 ) + "." + vr.substr( tam - 5, 3 ) + "," + vr.substr( tam - dec, tam ) ;
	}
	if ( (tam >= 9) && (tam <= 11) ){
	campo.value = vr.substr( 0, tam - 8 ) + "." + vr.substr( tam - 8, 3 ) + "." + vr.substr( tam - 5, 3 ) + "," + vr.substr( tam - dec, tam ) ; }
	if ( (tam >= 12) && (tam <= 14) ){
	campo.value = vr.substr( 0, tam - 11 ) + "." + vr.substr( tam - 11, 3 ) + "." + vr.substr( tam - 8, 3 ) + "." + vr.substr( tam - 5, 3 ) + "," + vr.substr( tam - dec, tam ) ; }
	if ( (tam >= 15) && (tam <= 17) ){
	campo.value = vr.substr( 0, tam - 14 ) + "." + vr.substr( tam - 14, 3 ) + "." + vr.substr( tam - 11, 3 ) + "." + vr.substr( tam - 8, 3 ) + "." + vr.substr( tam - 5, 3 ) + "," + vr.substr( tam - 2, tam ) ;}
	}
} 

//onkeyup="formataData(this);"
function formataData(val) {
	var pass = val.value;
	var expr = /[0123456789]/;
		
	for(i=0; i<pass.length; i++){
		var lchar = val.value.charAt(i);
		var nchar = val.value.charAt(i+1);
	
		if(i==0) {
		   if ((lchar.search(expr) != 0) || (lchar>3)){
			  val.value = "";
		   }
		   
		} else if(i==1){
			   
			   if(lchar.search(expr) != 0){
				  var tst1 = val.value.substring(0,(i));
				  val.value = tst1;				
				  continue;			
			   }
			   
			   if ((nchar != '/') && (nchar != '')){
					var tst1 = val.value.substring(0, (i)+1);
				
					if(nchar.search(expr) != 0) 
						var tst2 = val.value.substring(i+2, pass.length);
					else
						var tst2 = val.value.substring(i+1, pass.length);
	
					val.value = tst1 + '/' + tst2;
			   }

		 }else if(i==4){
			
				if(lchar.search(expr) != 0){
					var tst1 = val.value.substring(0, (i));
					val.value = tst1;
					continue;			
				}
		
				if	((nchar != '/') && (nchar != '')){
					var tst1 = val.value.substring(0, (i)+1);

					if(nchar.search(expr) != 0) 
						var tst2 = val.value.substring(i+2, pass.length);
					else
						var tst2 = val.value.substring(i+1, pass.length);
	
					val.value = tst1 + '/' + tst2;
				}
		  }
		
		  if(i>=6) {
			  if(lchar.search(expr) != 0) {
					var tst1 = val.value.substring(0, (i));
					val.value = tst1;			
			  }
		  }
	 }
	
	 if(pass.length>10)
		val.value = val.value.substring(0, 10);
		return true;
}

function formataHora(val) {
	var pass = val.value;
	var expr = /[0123456789]/;
		
	for(i=0; i<pass.length; i++){
		var lchar = val.value.charAt(i);
		var nchar = val.value.charAt(i+1);
	
		if(i==0) {
		   if ((lchar.search(expr) != 0) || (lchar>3)){
			  val.value = "";
		   }
		   
		} else if(i==1){
			   
			   if(lchar.search(expr) != 0){
				  var tst1 = val.value.substring(0,(i));
				  val.value = tst1;				
				  continue;			
			   }
			   
			   if ((nchar != ':') && (nchar != '')){
					var tst1 = val.value.substring(0, (i)+1);
				
					if(nchar.search(expr) != 0) 
						var tst2 = val.value.substring(i+2, pass.length);
					else
						var tst2 = val.value.substring(i+1, pass.length);
	
					val.value = tst1 + ':' + tst2;
			   }

		 }else if(i==4){
			
				if(lchar.search(expr) != 0){
					var tst1 = val.value.substring(0, (i));
					val.value = tst1;
					continue;			
				}
		
				if	((nchar != ':') && (nchar != '')){
					var tst1 = val.value.substring(0, (i)+1);

					if(nchar.search(expr) != 0) 
						var tst2 = val.value.substring(i+2, pass.length);
					else
						var tst2 = val.value.substring(i+1, pass.length);
	
					val.value = tst1 + ':' + tst2;
				}
		  }
		
		  if(i>=6) {
			  if(lchar.search(expr) != 0) {
					var tst1 = val.value.substring(0, (i));
					val.value = tst1;			
			  }
		  }
	 }
	
	 if(pass.length>10)
		val.value = val.value.substring(0, 10);
		return true;
}


/* ------------------------------------------------------------------------------------------------ */

function retornaDataFinal(data_inicial, qtde_dias) {
	var data_inicial= g("data_inicial_abono").value;
	var qtde_dias= g("qtde_dias").value;
	
	if ( (data_inicial!="") && (qtde_dias!="") )
		ajaxLink('resultado_data', 'retornaDataFinal&data_inicial='+data_inicial+'&qtde_dias='+qtde_dias);
}

function alteraCidade(div, id_uf, nome_campo) {
	desabilitaCampo("enviar");
	var id_uf = g(id_uf);
	ajaxLink(div, "alteraCidade&id_uf="+id_uf.value+"&nome_campo="+nome_campo);
}

function alteraPessoas() {
	desabilitaCampo("enviar");
	var id_empresa = g("id_empresa");
	ajaxLink("id_pessoa_atualiza", "alteraPessoas&id_empresa="+id_empresa.value);
}


function alteraCargos() {
	desabilitaCampo("enviar");
	var id_departamento = g("id_departamento");
	ajaxLink("id_cargo_atualiza", "alteraCargos&id_departamento="+id_departamento.value);
}

function verificaCnpj(acao) {
	var cnpj= g("cnpj");
	var cnpj_teste= validaCnpj(cnpj.value);
	var tipo_pessoa= g("tipo_pessoa");
	
	if (cnpj_teste.length==0) {
		//inserção
		if (acao=='i')
			ajaxLink("cnpj_testa", "verificaCnpj&cnpj="+cnpj.value+"&tipo_pessoa="+tipo_pessoa.value);
		//edicao
		else {
			var id_pessoa= g("id_pessoa");
			ajaxLink("cnpj_testa", "verificaCnpj&cnpj="+cnpj.value+"&id_pessoa="+id_pessoa.value+"&tipo_pessoa="+tipo_pessoa.value);
		}
	}
	else {
		var span_cnpj_testa= g("span_cnpj_testa");
		span_cnpj_testa.className= "vermelho";
		span_cnpj_testa.innerHTML= cnpj_teste;
	}
}

function verificaCpf(acao, local) {
	var cpf= g("cpf");
	var cpf_teste= validaCpf(cpf.value);
	var tipo_pessoa= g("tipo_pessoa");
	
	if (cpf_teste.length==0) {
		//inserção
		if (acao=='i')
			ajaxLink("cpf_testa", "verificaCpf&cpf="+cpf.value+"&tipo_pessoa="+tipo_pessoa.value);
		//edicao
		else {
			var id_pessoa= g("id_pessoa");
			ajaxLink("cpf_testa", "verificaCpf&cpf="+cpf.value+"&id_pessoa="+id_pessoa.value+"&tipo_pessoa="+tipo_pessoa.value);
		}
	}
	else {
		var span_cpf_testa= g("span_cpf_testa");
		span_cpf_testa.className= "vermelho";
		span_cpf_testa.innerHTML= cpf_teste;
	}
}

function bloqueaCampos(div) {
	var area= g(div);
	
	var campos= area.getElementsByTagName("input");
	for (i=0; i<campos.length; i++) campos[i].disabled= true;
	
	var campos= area.getElementsByTagName("select");
	for (i=0; i<campos.length; i++) campos[i].disabled= true;
	
	var campos= area.getElementsByTagName("textarea");
	for (i=0; i<campos.length; i++) campos[i].disabled= true;
}

function ativaDesativa(id_campo) {
	var campo= g(id_campo);
	
	if (campo.className=="desativado") {
		//campo.disabled=false;
		campo.className= campo.className+" ativado";
	}
	else {
		//campo.disabled=true;
		campo.value= "";
		campo.className=  campo.className+" desativado";
	}
}

function ativaDesativaData(valor, id_campo) {
	var campo= g(id_campo);
	
	if (parseInt(valor)==0) {
		campo.disabled=true;
		campo.value= "";
		campo.className= "desativado";
	}
	else {
		campo.disabled=false;
		campo.className= "ativado";
	}
}

function alteraTipoTecnico(tipo_tecnico) {
	if (tipo_tecnico==1) outro=2;
	else outro=1;
	
	abreDiv("tipo_tecnico_"+tipo_tecnico);
	fechaDiv("tipo_tecnico_"+outro);
}

function abreDiv(div) {
	var div_mesmo= g(div);
	div_mesmo.style.display="block";
}

function abreFechaDiv(div) {
	var div_mesmo= g(div);
	
	if ((div_mesmo.className=="nao_mostra") || (div_mesmo.className=="escondido")) {
		div_mesmo.style.display=="block";
		div_mesmo.className= "mostra";
	}
	else {
		div_mesmo.style.display=="none";
		div_mesmo.className= "nao_mostra";
	}
}

function fechaDiv(div) {
	var div_mesmo= g(div);
	div_mesmo.style.display="none";
}

function preencheDiv(div, conteudo) {
	var div_mesmo= g(div);
	div_mesmo.innerHTML=conteudo;
}

function checaCampo(campo) {
	var campo_dest= g(campo);
	campo_dest.checked= true;
}

function atribuiValor(campo, valor) {
	var campo_dest= g(campo);
	campo_dest.value= valor;
}

function daFoco(campo) {
	try {
		g(campo).focus();
	} catch (eee) { }
}

function daBlur(campo) {
	g(campo).blur();
}

/* ------------------------------------------------------------------------------------------------ */

function validaCpf(cpf) {
	 var strcpf = cpf;
	 var str_aux = "";
	 var erros= "";
	 
	 for (i = 0; i <= strcpf.length - 1; i++)
	   if ((strcpf.charAt(i)).match(/\d/))
		 str_aux += strcpf.charAt(i);
	   else if (!(strcpf.charAt(i)).match(/[\.\-]/)) {
		 erros += "Apenas números no campo CPF!\n";
		 break;
		 //return false;
	   }

	 if (str_aux.length < 11) {
	   erros += "O campo CPF deve conter 11 dígitos!\n";
	   //return false;
	 }
	 else {
		 soma1 = soma2 = 0;
		 for (i = 0; i <= 8; i++) {
		   soma1 += str_aux.charAt(i) * (10-i);
		   soma2 += str_aux.charAt(i) * (11-i);
		 }
		 d1 = ((soma1 * 10) % 11) % 10;
		 d2 = (((soma2 + (d1 * 2)) * 10) % 11) % 10;
		 if ((d1 != str_aux.charAt(9)) || (d2 != str_aux.charAt(10))) {
		   erros += "O CPF digitado é inválido!\n";
		   //return false;
		 }
		  if ((cpf=="00000000000") || (cpf=="11111111111") || (cpf=="22222222222") || (cpf=="33333333333") || 
		  (cpf=="44444444444") || (cpf=="55555555555") || (cpf=="66666666666") || (cpf=="77777777777") || 
		  (cpf=="88888888888") || (cpf=="99999999999") ) {
		   erros += "O CPF digitado é inválido!!\n";
		   //return false;
		 }
	 }
	 return (erros);
}

function validaCnpj(CNPJ) {
	 erro = new String;
	 if (CNPJ.length < 18)
	 	erro = "CNPJ inválido!";
	 if ((CNPJ.charAt(2) != ".") || (CNPJ.charAt(6) != ".") || (CNPJ.charAt(10) != "/") || (CNPJ.charAt(15) != "-")){
	 if (erro.length == 0)
	 	erro = "CNPJ inválido!";
	 }
	 //substituir os caracteres que não são números
   if(document.layers && parseInt(navigator.appVersion) == 4){
		   x = CNPJ.substring(0,2);
		   x += CNPJ. substring (3,6);
		   x += CNPJ. substring (7,10);
		   x += CNPJ. substring (11,15);
		   x += CNPJ. substring (16,18);
		   CNPJ = x;
   } else {
		   CNPJ = CNPJ. replace (".","");
		   CNPJ = CNPJ. replace (".","");
		   CNPJ = CNPJ. replace ("-","");
		   CNPJ = CNPJ. replace ("/","");
   }
   var nonNumbers = /\D/;
   if (nonNumbers.test(CNPJ))
   	  if (erro.length == 0)	
		erro += "O campo CNPJ suporta apenas números!";
	
   var a = [];
   var b = new Number;
   var c = [6,5,4,3,2,9,8,7,6,5,4,3,2];
   for (i=0; i<12; i++){
		   a[i] = CNPJ.charAt(i);
		   b += a[i] * c[i+1];
   }
   if ((x = b % 11) < 2) { a[12] = 0 } else { a[12] = 11-x }
   b = 0;
   for (y=0; y<13; y++) {
		   b += (a[y] * c[y]);
   }
   if ((x = b % 11) < 2) { a[13] = 0; } else { a[13] = 11-x; }
   if ((CNPJ.charAt(12) != a[12]) || (CNPJ.charAt(13) != a[13])){
	   if (erro.length == 0)
		   erro = "CNPJ inválido!";
   }
   return(erro);
}

function validaData(data, tipo) {
	var retorno=true;
	if (data=="") {
		retorno=false;
	}
	else {
		var dia= data.substring(0, 2);
		var mes= data.substring(3, 5);
		var ano= data.substring(6, 10);
		
		var barra1= data.substring(2, 3);
		var barra2= data.substring(5, 6);
		
		if ((barra1=="/") && (barra2=="/")) {
			var nonNumbers = /\D/;
						
			if ( (dia<=0) || (dia>31)  || (nonNumbers.test(dia)) )
				retorno=false;
			/*else {
				if ( ((mes=="02") || (mes=="04") || (mes=="06") || (mes=="09") || (mes=="11")) && (dia=="31") )
					retorno=false;
			}*/
			
			if ( (mes<=0) || (mes>12)  || (nonNumbers.test(mes)) )
				retorno=false;
			
			if (tipo==2) {
				var dataAtual= new Date();
				var anoAtual= dataAtual.getFullYear();
				
				if ( (ano<=0) || (ano>anoAtual) || (nonNumbers.test(ano)) )
					retorno=false;
			}
			
			//ano bissexto
			if ((ano%4!=0) && (mes==2) && (dia>28))
				retorno=false;
		}
		else
			retorno=false;
	}
	return(retorno);
}

/***
* Descrição.: formata um campo do formulário de
* acordo com a máscara informada...
* Parâmetros: - objForm (o Objeto Form)
* - strField (string contendo o nome
* do textbox)
* - sMask (mascara que define o
* formato que o dado será apresentado,
* usando o algarismo "9" para
* definir números e o símbolo "!" para
* qualquer caracter...
* - evtKeyPress (evento)
* Uso.......: <input type="textbox"
* name="xxx".....
* onkeypress="return txtBoxFormat(document.rcfDownload, 'str_cep', '99999-999', event);">
* Observação: As máscaras podem ser representadas como os exemplos abaixo:
* CEP -> 99.999-999
* CPF -> 999.999.999-99
* CNPJ -> 99.999.999/9999-99
* Data -> 99/99/9999
* Tel Resid -> (99) 999-9999
* Tel Cel -> (99) 9999-9999
* Processo -> 99.999999999/999-99
* C/C -> 999999-!
* E por aí vai...
***/

function formataCampo(objForm, strField, sMask, evtKeyPress) {
  var i, nCount, sValue, fldLen, mskLen,bolMask, sCod, nTecla;

  if(document.all) { // Internet Explorer
    nTecla = evtKeyPress.keyCode;
	} else if(document.layers) { // Nestcape
		nTecla = evtKeyPress.which;
	} else {
		nTecla = evtKeyPress.which;
		//alert(nTecla);
		if ((nTecla==8) || (nTecla==0))
			return true;
	}

  sValue = objForm[strField].value;

  // Limpa todos os caracteres de formatação que
  // já estiverem no campo.
  sValue = sValue.toString().replace( "-", "" );
  sValue = sValue.toString().replace( "-", "" );
  sValue = sValue.toString().replace( ".", "" );
  sValue = sValue.toString().replace( ".", "" );
  sValue = sValue.toString().replace( "/", "" );
  sValue = sValue.toString().replace( "/", "" );
  sValue = sValue.toString().replace( "(", "" );
  sValue = sValue.toString().replace( "(", "" );
  sValue = sValue.toString().replace( ")", "" );
  sValue = sValue.toString().replace( ")", "" );
  sValue = sValue.toString().replace( " ", "" );
  sValue = sValue.toString().replace( " ", "" );
  fldLen = sValue.length;
  mskLen = sMask.length;

  i = 0;
  nCount = 0;
  sCod = "";
  mskLen = fldLen;

  while (i <= mskLen) {
	bolMask = ((sMask.charAt(i) == "-") || (sMask.charAt(i) == ".") || (sMask.charAt(i) == "/"))
	bolMask = bolMask || ((sMask.charAt(i) == "(") || (sMask.charAt(i) == ")") || (sMask.charAt(i) == " "))

	if (bolMask) {
	  sCod += sMask.charAt(i);
	  mskLen++; }
	else {
	  sCod += sValue.charAt(nCount);
	  nCount++;
	}

	i++;
  }

  objForm[strField].value = sCod;

  if (nTecla != 8) { // backspace
	if (sMask.charAt(i-1) == "9") { // apenas números...
	  return ((nTecla > 47) && (nTecla < 58)); } // números de 0 a 9
	else { // qualquer caracter...
	  return true;
	} }
  else {
	return true;
  }
}

function formataCampoVarios(objForm, idCampo, sMask, evtKeyPress) {
  var i, nCount, sValue, fldLen, mskLen,bolMask, sCod, nTecla;

  if(document.all) { // Internet Explorer
    nTecla = evtKeyPress.keyCode;
	} else if(document.layers) { // Nestcape
		nTecla = evtKeyPress.which;
	} else {
		nTecla = evtKeyPress.which;
		//alert(nTecla);
		if ((nTecla==8) || (nTecla==0))
			return true;
	}
  
  var campo = g(idCampo);
  var sValue = campo.value;

  // Limpa todos os caracteres de formatação que
  // já estiverem no campo.
  sValue = sValue.toString().replace( "-", "" );
  sValue = sValue.toString().replace( "-", "" );
  sValue = sValue.toString().replace( ".", "" );
  sValue = sValue.toString().replace( ".", "" );
  sValue = sValue.toString().replace( "/", "" );
  sValue = sValue.toString().replace( "/", "" );
  sValue = sValue.toString().replace( "(", "" );
  sValue = sValue.toString().replace( "(", "" );
  sValue = sValue.toString().replace( ")", "" );
  sValue = sValue.toString().replace( ")", "" );
  sValue = sValue.toString().replace( " ", "" );
  sValue = sValue.toString().replace( " ", "" );
  fldLen = sValue.length;
  mskLen = sMask.length;

  i = 0;
  nCount = 0;
  sCod = "";
  mskLen = fldLen;

  while (i <= mskLen) {
	bolMask = ((sMask.charAt(i) == "-") || (sMask.charAt(i) == ".") || (sMask.charAt(i) == "/"))
	bolMask = bolMask || ((sMask.charAt(i) == "(") || (sMask.charAt(i) == ")") || (sMask.charAt(i) == " "))

	if (bolMask) {
	  sCod += sMask.charAt(i);
	  mskLen++; }
	else {
	  sCod += sValue.charAt(nCount);
	  nCount++;
	}

	i++;
  }

  campo.value = sCod;

  if (nTecla != 8) { // backspace
	if (sMask.charAt(i-1) == "9") { // apenas números...
	  return ((nTecla > 47) && (nTecla < 58)); } // números de 0 a 9
	else { // qualquer caracter...
	  return true;
	} }
  else {
	return true;
  }
}


function validaCnpj(CNPJ) {
	 erro = new String;
	 if (CNPJ.length < 18)
	 	erro = "CNPJ inválido!";
	 if ((CNPJ.charAt(2) != ".") || (CNPJ.charAt(6) != ".") || (CNPJ.charAt(10) != "/") || (CNPJ.charAt(15) != "-")){
	 if (erro.length == 0)
	 	erro = "CNPJ inválido!";
	 }
	 //substituir os caracteres que não são números
   if(document.layers && parseInt(navigator.appVersion) == 4){
	   x = CNPJ.substring(0,2);
	   x += CNPJ. substring (3,6);
	   x += CNPJ. substring (7,10);
	   x += CNPJ. substring (11,15);
	   x += CNPJ. substring (16,18);
	   CNPJ = x;
   }
   else {
	   CNPJ = CNPJ. replace (".","");
	   CNPJ = CNPJ. replace (".","");
	   CNPJ = CNPJ. replace ("-","");
	   CNPJ = CNPJ. replace ("/","");
   }
   var nonNumbers = /\D/;
   if (nonNumbers.test(CNPJ))
   	  if (erro.length == 0)	
		erro += "O campo CNPJ suporta apenas números!";
	
   var a = [];
   var b = new Number;
   var c = [6,5,4,3,2,9,8,7,6,5,4,3,2];
   for (i=0; i<12; i++){
		   a[i] = CNPJ.charAt(i);
		   b += a[i] * c[i+1];
   }
   if ((x = b % 11) < 2) { a[12] = 0 } else { a[12] = 11-x }
   b = 0;
   for (y=0; y<13; y++) {
		   b += (a[y] * c[y]);
   }
   if ((x = b % 11) < 2) { a[13] = 0; } else { a[13] = 11-x; }
   if ((CNPJ.charAt(12) != a[12]) || (CNPJ.charAt(13) != a[13])){
	   if (erro.length == 0)
		   erro = "CNPJ inválido!";
   }
   return(erro);
}

function validaEmail(email) {
	var retorno= true;
	
	if (email=="")
		retorno= false;
	if (email.indexOf("@") < 2)
		retorno= false;
	if (email.indexOf(".") < 1)
		retorno= false;
	
	return(retorno);
}

function desabilitaCampo(id_elemento) {
	g(id_elemento).disabled=true;
}

function habilitaCampo(id_elemento) {
	g(id_elemento).disabled=false;
}

function pegaTitle(campo) {
	var titulo= g(campo).title;
	
	if (titulo=="") titulo= g(campo).id;
	return(titulo);
}

function pegaValor(campo) {
	return(g(campo).value);
}

/*
---------------------------------------------------------------------
---------------------------------------------------------------------
------------- FUNCOES PARA VALIDAR FORMULARIOS ---------------------
---------------------------------------------------------------------
---------------------------------------------------------------------
*/

function estaVazio(nome_elemento) {
	var valor= g(nome_elemento).value;
	if (valor=="") return true;
	else return false;
}

function ehIgual(campo1, campo2) {
	if (campo1==campo2) return true;
	else return false;
}

function validaFormNormal(campo, pedir_confirmacao, desabilitar_campo) {
	var permissao=true;
	var passa=true;
	var desabilita;
	
	//alert(campo);
	
	if (desabilitar_campo=="1") desabilita= true;
	else desabilita= false;
	
	try {
		var validacoes= g(campo).value;
		permissao= validaForm(validacoes);
	}
	catch (eee) {
		
	}
	
	if (permissao) {
		if (pedir_confirmacao) passa= confirm("Tem certeza que deseja submeter o formulário?\n\nConfira se todas as informações estão corretas!");
		
		if (passa) {
			if (desabilita) {
				
				var desabilita_qual_campo;
				var esconde_campo;
				
				//respostas do livro
				if (campo.indexOf("id_livro")!=-1) {
					desabilita_qual_campo= campo.replace("validacoes_resposta", "enviar");
					
					//alert(campo);
					
					esconde_campo= campo.replace("validacoes_resposta", "cancela");
					fechaDiv(esconde_campo);
				}
				else {
					desabilita_qual_campo= "enviar";
				}
					
				//alert(desabilita_qual_campo);
				
				desabilitaCampo(desabilita_qual_campo);
				preencheDiv(desabilita_qual_campo, "aguarde...");	
			}
			return(true);
		}
		else
			return(false);
	}
	else
		return(false);
	
}

function validaForm(validacoes) {
	
	var parte= validacoes.split("|");
	var i, aqui, retorno=true, foco, campo, tipo_validacao, mensagem="", campo_foco="", outro_campo;
	
	for (i=0; i<parte.length; i++) {
		aqui= parte[i].split("@", 4);
		campo= aqui[0];
		tipo_validacao= aqui[1];
		campo_foco= aqui[2];
		outro_campo= aqui[3];
		
		switch (tipo_validacao) {
			case "igual": retorno= ehIgual(pegaValor(campo), pegaValor(outro_campo)); break;
			case "vazio": retorno= !estaVazio(campo); break;
			case "data": retorno= validaData(pegaValor(campo), 1); break;
			case "data_passada": retorno= validaData(pegaValor(campo), 2); break;
			case "email": retorno= validaEmail(pegaValor(campo)); break;
			case "numeros": retorno= sohNumeros(pegaValor(campo)); break;
		}

		if (!retorno) {
			if (campo_foco!=undefined) foco= campo_foco;
			else foco= campo;
			
			i=9999;
			mensagem= "Preencha corretamente o campo \""+pegaTitle(campo)+"\"!";
		}
	}
	
	if (foco!="") daFoco(foco);
	if (mensagem!="") alert("ATENÇÃO:\n\n"+mensagem);
	
	return(retorno);
}

