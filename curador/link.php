<?
require_once("includes/funcoes.php");

if (!$conexao)
	require_once("includes/conexao.php");

header("Content-type: text/html; charset=utf-8", true);
header("Cache-Control: no-store, no-cache, must-revalidate");
header("Cache-Control: post-check=0, pre-check=0", false);
header("Pragma: no-cache");

/*echo "<!DOCTYPE html PUBLIC \"-//W3C//DTD XHTML 1.0 Strict//EN\"
			\"http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd\">
			<html xmlns=\"http://www.w3.org/1999/xhtml\" xml:lang=\"en\" lang=\"en\">
			<head>
			<title>Sige</title>
			</head>
			<body>";
*/
// ############################################### TODOS ###############################################

if (isset($_GET["carregaPagina"])) {
	require_once("index2.php");
}
if (isset($_GET["carregaPaginaInterna"])) {
	require_once("index2.php");
}

if (isset($_GET["alteraCidade"])) {
	$result= mysql_query("select * from cidades where id_uf = '". $_GET["id_uf"] ."' order by cidade asc ");
	
	$str= "<select name=\"". $_GET["nome_campo"] ."\" id=\"". $_GET["nome_campo"] ."\">
			<option value=\"\">---</option>";
	
	$i=1;
	while ($rs= mysql_fetch_object($result)) {
		if ($i==1) $classe= " class=\"cor_sim\"";
		else $classe= " ";
		$i++;
		$str .= "<option ". $classe ." value=\"". $rs->id_cidade ."\">". $rs->cidade ."</option>";
		if ($i==2) $i=0;
	}
	
	$str .= "</select>";
	echo $str;
	echo "<script language=\"javascript\">habilitaCampo('enviar');</script>";
}

if (isset($_GET["retornaDataFinal"])) {
	$data= explode('/', $_GET["data_inicial"]);
	echo date("d/m/Y", mktime(0, 0, 0, $data[1], $data[0]+($_GET["qtde_dias"]-1), $data[2]));
}

// ############################################### ADMIN GERAL ###############################################

if (pode("a", $_SESSION["permissao"])) {
	
	if ($_GET["chamada"]=="arquivoExcluir") {
		
		$apagar= @unlink($_GET["src"]);
		
		if ($apagar) {
			echo "0";
			
			$var=0;
			inicia_transacao();
		
			$result= mysql_query("update pessoas set foto= ''
								where id_pessoa= '". $_GET["id_pessoa"] ."'
								");
			if (!$result) $var++;
			finaliza_transacao($var);
		}
		else echo "1";
		
		//echo $var;
	}
	
	/*if ($_GET["chamada"]=="usuarioStatus") {
		$var=0;
		inicia_transacao();
		
		$result= mysql_query("update usuarios set status_usuario= '". $_GET["status"] ."'
								where id_usuario= '". $_GET["id"] ."'
								");
		if (!$result) $var++;
			
		finaliza_transacao($var);
		echo $var;
	}
	
	if ($_GET["chamada"]=="usuarioExcluir") {
		
		$var=0;
		inicia_transacao();
		
		$result= mysql_query("update usuarios
							  	set status_usuario = '2'
								where id_usuario = '". $_GET["id"] ."'
								");
		if (!$result) $var++;
		
		finaliza_transacao($var);
		echo $var;
	}
	*/
}//fim empresa admin

if (pode("vrm", $_SESSION["permissao"])) {
	
	if ($_GET["chamada"]=="imagemExcluir") {
		
		$var=0;
		inicia_transacao();
		
		$result_pre= mysql_query("select * from imagens
									where id_imagem = '". $_GET["id"] ."'
									");
		$rs_pre= mysql_fetch_object($result_pre);
		
		//@unlink(CAMINHO_CDN . $rs_pre->tipo_imagem ."_". $rs_pre->id_externo ."/". $rs_pre->nome_arquivo);
		
		$result= mysql_query("delete from imagens
								where id_imagem = '". $_GET["id"] ."'
								and   id_empresa = '". $_SESSION["id_empresa"] ."'
								limit 1
								");
		if (!$result) $var++;
		
		
		
		finaliza_transacao($var);
		echo $var;
	}
	
	if ($_GET["chamada"]=="imagemSite") {
		
		$var=0;
		inicia_transacao();
		
		$result= mysql_query("update imagens
								set site = '". $_GET["site"] ."'
								where id_imagem = '". $_GET["id"] ."'
								and   id_empresa = '". $_SESSION["id_empresa"] ."'
								limit 1
								") or die(mysql_error());
		if (!$result) $var++;
		
		cria_imagem_site($_GET["site"], $_GET["id"], 95);
		
		finaliza_transacao($var);
		
		echo $var ."@@@". $_GET["id"];
	}
	
	if ($_GET["chamada"]=="criaEspacoVideoAjax") {
		
		$var=0;
		inicia_transacao();
		
		$result_pre= mysql_query("select * from videos
									where tipo_video = '". $_GET["tipo_imagem"] ."'
									and   id_externo = '". $_GET["id_externo"] ."'
									order by ordem desc limit 1
									");
		$rs_pre= mysql_fetch_object($result_pre);
		
		$ordem= $rs_pre->ordem+1;
		
		$result= mysql_query("insert into videos
									(id_empresa, tipo_video, id_externo, ordem, url, site, id_usuario) values
									('". $_SESSION["id_empresa"] ."', '". $_GET["tipo_imagem"] ."',
									'". $_GET["id_externo"] ."', '". $ordem ."', '', '', '". $_SESSION["id_usuario"] ."'
									)
									") or die(mysql_error());
		if (!$result) $var++;
		$id_video= mysql_insert_id();
		
		finaliza_transacao($var);
		
		echo $var ."@@@". $id_video;
	}
	
	if ($_GET["chamada"]=="atualizaOrdemProjetos") {
		
		$chamada= mysql_real_escape_string($_GET['chamada']); 
		$ordemAtualizada= $_GET['linha'];
			
		$i= 0;
		
		foreach ($ordemAtualizada as $id_projeto) {	
			$query = "update projetos set ordem = " . $i . " where id_projeto = " . $id_projeto;
			mysql_query($query) or die('Error, insert query failed');
			
			$i++;
		}
	}
	
	if ($_GET["chamada"]=="atualizaOrdemImagensCuradoria") {
		
		$chamada= mysql_real_escape_string($_GET['chamada']); 
		$ordemAtualizada= $_GET['linha'];
			
		$i= 0;
		
		foreach ($ordemAtualizada as $id_imagem) {	
			$query = "update imagens set ordem_curadoria = " . $i . " where id_imagem = " . $id_imagem;
			mysql_query($query) or die('Error, insert query failed');
			
			$i++;
		}
	}
	
	if ($_GET["chamada"]=="atualizaOrdemPaginasCuradoria") {
		
		$chamada= mysql_real_escape_string($_GET['chamada']); 
		$ordemAtualizada= $_GET['linha'];
			
		$i= 1;
		
		foreach ($ordemAtualizada as $id_curadoria_pagina) {	
			$query = "update curadoria_paginas set num_pagina = " . $i . " where id_curadoria_pagina = '" . $id_curadoria_pagina ."'  and   tipo_pagina = '6' ";
			mysql_query($query) or die('Error, insert query failed');
			
			$i++;
		}
	}
	
	if ($_GET["chamada"]=="atualizaOrdemImagens") {
		
		$chamada= mysql_real_escape_string($_GET['chamada']); 
		$ordemAtualizada= $_GET['linha'];
			
		$i= 0;
		
		foreach ($ordemAtualizada as $id_imagem) {	
			$query = "update imagens set ordem = " . $i . " where id_imagem = " . $id_imagem;
			mysql_query($query) or die('Error, insert query failed');
			
			//echo $query ."<br>";
			
			$i++;
		}
	}
	
	if ($_GET["chamada"]=="atualizaOrdemVideos") {
		
		$chamada= mysql_real_escape_string($_GET['chamada']); 
		$ordemAtualizada= $_GET['div_video'];
			
		$i= 0;
		
		foreach ($ordemAtualizada as $id_video) {	
			mysql_query("update videos set ordem = " . $i . " where id_video = " . $id_video) or die(mysql_error());		
			$i++;
		}
	}
	
	if ($_GET["chamada"]=="atualizaOrdemImagens2") {
		
		$result= mysql_query("update imagens set ordem = " . $_GET["ordem"] . " where id_imagem = '" . $_GET["id"] ."' ") or die('Error, insert query failed');
	
	}
	
	if ($_GET["chamada"]=="atualizaOrdemArtistas") {
		
		$chamada= mysql_real_escape_string($_GET['chamada']); 
		$ordemAtualizada= $_GET['artista'];
			
		$i= 0;
		foreach ($ordemAtualizada as $id_pessoa) {	
			$query = "update curadorias_pessoas set ordem = ". $i ."
						where id_pessoa = '". $id_pessoa ."'
						and   id_projeto = '". $_GET["id_projeto"] ."'
						and   id_curadoria = '". $_GET["id_curadoria"] ."'
						";
			mysql_query($query) or die(mysql_error());
			
			$i++;
		}
	}
	
	if ($_GET["chamada"]=="duplicaCuradoria") {
		
		$var=0;
		
		inicia_transacao();
		
		$result_curadoria= mysql_query("select * from curadorias
										where id_curadoria = '". $_GET["id_curadoria"] ."'
										and   id_projeto = '". $_GET["id_projeto"] ."'
										");
		$rs_curadoria= mysql_fetch_object($result_curadoria);
		
		$result_nova_curadoria= mysql_query("insert into curadorias
												(id_projeto, id_empresa, num_curadoria, titulo_curadoria, data_curadoria, data_curadoria_mod, hora_curadoria_mod, id_usuario_mod,
												mostrar, auth, status_curadoria, id_usuario)
												values
												('". $rs_curadoria->id_projeto ."', '". $rs_curadoria->id_empresa ."', '". $rs_curadoria->num_curadoria ."', '". $rs_curadoria->titulo_curadoria ."',
												'". $rs_curadoria->data_curadoria ."', '". date("Y-m-d") ."', '". date("H:i:s") ."', '". $_SESSION["id_usuario"] ."',
												'". $rs_curadoria->mostrar ."', '". gera_auth() ."', '". $rs_curadoria->status_curadoria ."', '". $_SESSION["id_usuario"] ."' )
												") or die(mysql_error());
		if (!$result_nova_curadoria) $var++;
		
		$id_nova_curadoria= mysql_insert_id();
		
		
		//----------------------------------------------------------------
		
		$result_curadoria_pessoa= mysql_query("select * from curadorias_pessoas
												where id_curadoria = '". $_GET["id_curadoria"] ."'
												and   id_projeto = '". $_GET["id_projeto"] ."'
												");
												
		while ($rs_curadoria_pessoa= mysql_fetch_object($result_curadoria_pessoa)) {
			
			$result_nova_curadoria_pessoa= mysql_query("insert into curadorias_pessoas
														(id_projeto, id_curadoria, id_pessoa, ordem, notas, id_usuario)
														values
														('". $rs_curadoria_pessoa->id_projeto ."', '". $id_nova_curadoria ."', '". $rs_curadoria_pessoa->id_pessoa ."',
														'". $rs_curadoria_pessoa->ordem ."', '". $rs_curadoria_pessoa->notas ."', '". $_SESSION["id_usuario"] ."' )
														") or die(mysql_error());
			if (!$result_nova_curadoria_pessoa) $var++;
			
		}
		
		//----------------------------------------------------------------
		
		$result_curadoria_pessoa_imagens= mysql_query("select * from curadorias_pessoas_imagens
														where id_curadoria = '". $_GET["id_curadoria"] ."'
														and   id_projeto = '". $_GET["id_projeto"] ."'
														");
		while ($rs_curadoria_pessoa_imagens= mysql_fetch_object($result_curadoria_pessoa_imagens)) {
			
			$result_nova_curadoria_pessoa_imagens= mysql_query("insert into curadorias_pessoas_imagens
																(id_projeto, id_curadoria, id_pessoa, id_imagem, id_usuario)
																values
																('". $rs_curadoria_pessoa_imagens->id_projeto ."', '". $id_nova_curadoria ."', '". $rs_curadoria_pessoa_imagens->id_pessoa ."',
																'". $rs_curadoria_pessoa_imagens->id_imagem ."', '". $_SESSION["id_usuario"] ."' )
																") or die(mysql_error());
			if (!$result_nova_curadoria_pessoa_imagens) $var++;
			
		}
		
		//----------------------------------------------------------------
		
		$result_curadoria_pessoa_paginas= mysql_query("select * from curadoria_paginas
														where id_curadoria = '". $_GET["id_curadoria"] ."'
														and   id_projeto = '". $_GET["id_projeto"] ."'
														");
		while ($rs_curadoria_pessoa_paginas= mysql_fetch_object($result_curadoria_pessoa_paginas)) {
			
			$result_nova_curadoria_pessoa_paginas= mysql_query("insert into curadoria_paginas
																(id_empresa, id_projeto, id_curadoria, num_pagina, tipo_pagina, id_artista, id_usuario)
																values
																('". $rs_curadoria_pessoa_paginas->id_empresa ."', '". $rs_curadoria_pessoa_paginas->id_projeto ."', '". $id_nova_curadoria ."',
																'". $rs_curadoria_pessoa_paginas->num_pagina ."', '". $rs_curadoria_pessoa_paginas->tipo_pagina ."',
																'". $rs_curadoria_pessoa_paginas->id_artista ."', '". $_SESSION["id_usuario"] ."' )
																") or die(mysql_error());
			if (!$result_nova_curadoria_pessoa_paginas) $var++;
			
		}
		
		//----------------------------------------------------------------
		
		$result_curadoria_pessoa_paginas_imagens= mysql_query("select * from curadoria_paginas_imagens
																where id_curadoria = '". $_GET["id_curadoria"] ."'
																");
		while ($rs_curadoria_pessoa_paginas_imagens= mysql_fetch_object($result_curadoria_pessoa_paginas_imagens)) {
			
			$result_nova_curadoria_pessoa_paginas_imagens= mysql_query("insert into curadoria_paginas_imagens
																(id_empresa, id_curadoria, id_curadoria_pagina, id_imagem, nome_arquivo, dimensao_imagem, id_usuario)
																values
																('". $rs_curadoria_pessoa_paginas_imagens->id_empresa ."', '". $id_nova_curadoria ."', '". $rs_curadoria_pessoa_paginas_imagens->id_curadoria_pagina ."',
																'". $rs_curadoria_pessoa_paginas_imagens->id_imagem ."', '". $rs_curadoria_pessoa_paginas_imagens->nome_arquivo ."',
																'". $rs_curadoria_pessoa_paginas_imagens->dimensao_imagem ."', '". $_SESSION["id_usuario"] ."' )
																") or die(mysql_error());
			if (!$result_nova_curadoria_pessoa_paginas_imagens) $var++;
			
		}
		
		finaliza_transacao($var);
		
		header("location: ./?pagina=financeiro/curadoria_projeto_listar&id_projeto=". $_GET["id_projeto"]);
		
	}
	
	if ($_GET["chamada"]=="atualizaProjetoNovaAgencia") {
	
	?>
    
    <select name="id_agencia" id="id_agencia" onchange="alteraContatos('div_responsavel_agencia', 'id_contato_agencia', 'Contato (agência):', this.value); alteraContatos('div_diretor_arte', 'id_contato_agencia_diretor_arte', 'Diretor de arte:', this.value);">
        <option selected="selected" value="">-</option>
        <?
        $result_age= mysql_query("select * from pessoas, pessoas_tipos
                                    where pessoas.id_pessoa = pessoas_tipos.id_pessoa
                                    and   pessoas_tipos.tipo_pessoa = 'g'
                                    order by pessoas.apelido_fantasia asc");
        $i=0;
        while ($rs_age = mysql_fetch_object($result_age)) {
        ?>
        <option  <? if ($i%2==0) echo "class=\"cor_sim\""; ?> value="<?= $rs_age->id_pessoa; ?>"<? if ($rs_age->id_pessoa==$rs->id_agencia) echo "selected=\"selected\""; ?>><?= $rs_age->apelido_fantasia; ?></option>
        <? $i++; } ?>
    </select>
    
    <script type="text/javascript" charset="utf-8">
      $(function(){
        $("#id_agencia_atualiza select").uniform();
      });
    </script>
    
    <?
	}
	
	if ($_GET["chamada"]=="atualizaProjetoNovoCliente") {
	
	?>
    
    <select name="id_cliente" id="id_cliente">
        <option selected="selected" value="">-</option>
        <?
        $result_cli= mysql_query("select * from pessoas, pessoas_tipos
                                    where pessoas.id_pessoa = pessoas_tipos.id_pessoa
                                    and   pessoas_tipos.tipo_pessoa = 'c'
                                    order by pessoas.apelido_fantasia asc");
        $i=0;
        while ($rs_cli = mysql_fetch_object($result_cli)) {
        ?>
        <option  <? if ($i%2==0) echo "class=\"cor_sim\""; ?> value="<?= $rs_cli->id_pessoa; ?>"<? if ($rs_cli->id_pessoa==$rs->id_cliente) echo "selected=\"selected\""; ?>><?= $rs_cli->apelido_fantasia; ?></option>
        <? $i++; } ?>
    </select>
    
    <script type="text/javascript" charset="utf-8">
      $(function(){
        $("#id_cliente_atualiza select").uniform();
      });
    </script>
    
    <?
	}
	
	if ($_GET["chamada"]=="carregaObrasArtistaTamanho") {
		
		$tamanho_imagem= $_GET["tamanho_imagem"];
		
		if ($tamanho_imagem=="") $tamanho_imagem=1;
		
		switch($_GET["tamanho_imagem"]) {
			case 1:
				$largura_imagem= 165;
				$altura_imagem= 150;
				$div_nome= "bloco_imagem1";
			break;
			case 2:
				$largura_imagem= 300;
				$altura_imagem= 280;
				$div_nome= "bloco_imagem2";
			break;
			case 3:
				$largura_imagem= 600;
				$altura_imagem= 450;
				$div_nome= "bloco_imagem3";
			break;
		}
	?>
    
		<?
        $result_enviados= mysql_query("select * from imagens
                                        where id_externo = '". $_GET["id_pessoa"] ."'
                                        and   tipo_imagem = 'a'
                                        and   id_empresa = '". $_SESSION["id_empresa"] ."'
                                        and   ( miniatura_destaque is NULL or miniatura_destaque = '0')
                                        order by ordem_curadoria asc, ordem asc
                                        ") or die(mysql_error());
        $linhas_enviados= mysql_num_rows($result_enviados);
        
        if ($linhas_enviados==0) {
        ?>
        <div id="enviados_nenhum">Nenhuma imagem enviada.</div>
        <?
        }
        else {
            while ($rs_enviados= mysql_fetch_object($result_enviados)) {
                
                $result_curadoria= mysql_query("select * from curadorias_pessoas_imagens
                                                where id_projeto = '". $_GET["id_projeto"] ."'
                                                and   id_curadoria = '". $_GET["id_curadoria"] ."'
                                                and   id_imagem = '". $rs_enviados->id_imagem ."'
                                                ") or die(mysql_error());
                $linhas_curadoria= mysql_num_rows($result_curadoria);
                
                if ($linhas_curadoria>0) $checado=1;
                else $checado=0;
                
        ?>
        <div class="parte25 miniatura33 bloco_imagem <?= $div_nome; ?> <? if ($rs_enviados->site=="1") { ?>bloco_imagem_site<? } ?>" id="linha_<?= $rs_enviados->id_imagem; ?>">
            <label class="label_nada" for="imagem_check_<?= $rs_enviados->id_imagem; ?>">
                <img src="includes/timthumb/timthumb.php?src=<?= CAMINHO_CDN . $rs_enviados->tipo_imagem ."_". $rs_enviados->id_externo ."/". $rs_enviados->nome_arquivo; ?>&amp;w=<?=$largura_imagem;?>&amp;h=<?=$altura_imagem;?>&amp;zc=1&amp;q=95" width="<?=$largura_imagem;?>" height="<?=$altura_imagem;?>" border="0" alt="" />
            </label>
            
            <div class="imagem_dimensao">
                <?= pega_descricao_dimensao_imagem($rs_enviados->dimensao_imagem); ?>
            </div>
            
            <a class="imagem_lupa" toptions="layout = quicklook" href="<?= CAMINHO_CDN . $rs_enviados->tipo_imagem ."_". $rs_enviados->id_externo ."/". $rs_enviados->nome_arquivo; ?>">
                +
            </a>
            
            <input class="imagem_check tamanho20" <? if ($checado) echo "checked=\"checked\""; ?> type="checkbox" name="imagem_check" onchange="marcaImagemCuradoria(this, '<?=$rs_enviados->id_imagem;?>', '<?=$_GET["id_pessoa"];?>', '<?=$_GET["id_projeto"];?>', '<?=$_GET["id_curadoria"];?>');" id="imagem_check_<?= $rs_enviados->id_imagem; ?>" value="<?= $rs_enviados->id_imagem; ?>" />
            <input name="id_imagem[]" type="hidden" id="id_imagem_<?= $rs_enviados->id_imagem; ?>" value="<?= $rs_enviados->id_imagem; ?>" />
        </div>
        <? } } ?>
    
    <?
	}
	
	if ($_GET["chamada"]=="carregaImagensArtistaCuradoria") {
		$tamanho_imagem= $_GET["tamanho_imagem"];
		
		if ($tamanho_imagem=="") $tamanho_imagem=1;
	?>
        
        <script type="text/javascript">
			$(document).ready(function() {
				
				$(function() {
					$(".imagens_curadoria").sortable({ opacity: 0.8, cursor: 'move', update: function() {
						var order = $(this).sortable("serialize") + '&chamada=atualizaOrdemImagensCuradoria'; 
						$.get("link.php", order, function(theResponse) {
							//$("#ordem_retorno").html(theResponse);
						}); 															 
					}								  
					});
				});
				
				carregaObrasArtistaTamanho('aba_imagens_tamanho1', '<?= $_GET["id_pessoa"]; ?>', '1', '<?=$_GET["id_projeto"]; ?>', '<?=$_GET["id_curadoria"]; ?>');
			});
		</script>
        
        <p>Selecione as imagens que entrarão na curadoria:</p>
        <br />
            
            <div class="div_abas screen" id="aba_imagens_tamanhos_<?= $_GET["id_pessoa"]; ?>">
                <ul class="abas abas_aux">
                    <li id="aba_imagens_tamanho1" <? if ($tamanho_imagem=="1") { ?> class="atual" <? } ?>><a href="javascript:void(0);" onclick="carregaObrasArtistaTamanho('aba_imagens_tamanho1', '<?= $_GET["id_pessoa"]; ?>', '1', '<?=$_GET["id_projeto"]; ?>', '<?=$_GET["id_curadoria"]; ?>');">165x150</a></li>
                    <li id="aba_imagens_tamanho2" <? if ($tamanho_imagem=="2") { ?> class="atual" <? } ?>><a href="javascript:void(0);" onclick="carregaObrasArtistaTamanho('aba_imagens_tamanho2', '<?= $_GET["id_pessoa"]; ?>', '2', '<?=$_GET["id_projeto"]; ?>', '<?=$_GET["id_curadoria"]; ?>');">300x280</a></li>
                    <li id="aba_imagens_tamanho3" <? if ($tamanho_imagem=="3") { ?> class="atual" <? } ?>><a href="javascript:void(0);" onclick="carregaObrasArtistaTamanho('aba_imagens_tamanho3', '<?= $_GET["id_pessoa"]; ?>', '3', '<?=$_GET["id_projeto"]; ?>', '<?=$_GET["id_curadoria"]; ?>');">600x450</a></li>
                </ul>
            </div>
            
            <div id="imagens_curadoria_atualiza_<?= $_GET["id_pessoa"]; ?>" class="imagens_curadoria">
				
            </div>
        
            
            <?
			$result_notas= mysql_query("select * from curadorias_pessoas
										where id_curadoria = '". $_GET["id_curadoria"] ."'
										and   id_pessoa = '". $_GET["id_pessoa"] ."'
										");
			$rs_notas= mysql_fetch_object($result_notas);
			?>
            
            <hr />
            
            <h4>Notas:</h4>
                
                <input name="id_pessoa[]" type="hidden" class="escondido" value="<?= $_GET["id_pessoa"]; ?>" />
                
                <p>
                    <? /*<label class="tamanho100" for="notas_<?= $_GET["id_pessoa"]; ?>">Notas:</label>*/ ?>
                    <textarea class="tamanho100p altura80" id="notas_<?= $_GET["id_pessoa"]; ?>" name="notas[]"><?= $rs_notas->notas; ?></textarea>
                </p>
                
                <? /*
                <a href="javascript:void(0);" onclick="criaEspacoNotaCuradoria('<?= $_GET["id_pessoa"]; ?>');">+ nota</a>
                <br /><br />
                
                <div id="notas_curadoria_<?= $_GET["id_pessoa"]; ?>">
					<?
					$result_notas= mysql_query("select * from curadorias_pessoas_notas
												where id_curadoria = '". $_GET["id_curadoria"] ."'
												and   id_pessoa = '". $_GET["id_pessoa"] ."'
												order by id_curadoria_pessoa_nota asc
												");
					$i=1;
					while ($rs_notas= mysql_fetch_object($result_notas)) {
					?>
	                    <div id="div_nota_<?= $rs_notas->id_pessoa; ?>_<?=$i;?>">
                            <code class="escondido"></code>
                            <input name="id_pessoa[]" type="hidden" class="escondido" value="<?= $rs_notas->id_pessoa; ?>" />
                            <label class="tamanho100 alinhar_esquerda" for="nota_curadoria_pessoa_<?= $rs_notas->id_pessoa; ?>_<?=$i;?>">Nota:</label>
                            <input type="text" name="nota_curadoria_pessoa[]" id="nota_curadoria_pessoa_<?= $rs_notas->id_pessoa; ?>_<?=$i;?>" value="<?=$rs_notas->nota_curadoria_pessoa;?>" />
                            <a href="javascript:void(0);" onclick="removeDiv('notas_curadoria_<?= $rs_notas->id_pessoa; ?>', 'div_nota_<?= $rs_notas->id_pessoa; ?>_<?=$i;?>');">remover</a>
                            <br />
                       </div>
                    <? $i++; } ?>
                </div>
				*/ ?>
                                
            </fieldset>
            <?
	}
	
	if ($_GET["chamada"]=="marcaImagemCuradoria") {
		
		$var=0;
		inicia_transacao();
		
		if ($_GET["rotina"]==1) {
			$sql= "insert into curadorias_pessoas_imagens
					(id_projeto, id_curadoria, id_pessoa, id_imagem, id_usuario)
					values
					('". $_GET["id_projeto"] ."', '". $_GET["id_curadoria"] ."', '". $_GET["id_pessoa"] ."',
					'". $_GET["id_imagem"] ."', '". $_SESSION["id_usuario"] ."')
					";
		}
		else {
			$sql= "delete from curadorias_pessoas_imagens
					where id_projeto= '". $_GET["id_projeto"] ."'
					and   id_curadoria= '". $_GET["id_curadoria"] ."'
					and   id_pessoa= '". $_GET["id_pessoa"] ."'
					and   id_imagem= '". $_GET["id_imagem"] ."'
					";
		}
		
		$result= mysql_query($sql);
		if (!$result) $var++;
		
		finaliza_transacao($var);
		echo $var;
	}
	
	if ($_GET["chamada"]=="notaExcluir") {
		
		$var=0;
		inicia_transacao();
		
		$result= mysql_query("update pessoas_notas
							  	set status_nota = '2'
								where id_pessoa_nota = '". $_GET["id"] ."'
								");
		if (!$result) $var++;
		
		finaliza_transacao($var);
		echo $var;
	}
	
	if ($_GET["chamada"]=="tagExcluir") {
		
		$var=0;
		inicia_transacao();
		
		$result_pre= mysql_query("select * from tags
									where id_tag = '". $_GET["id"] ."'
									") or die(mysql_error());
		$rs_pre= mysql_fetch_object($result_pre);
		
		$result_artistas= mysql_query("select * from pessoas, pessoas_tipos
										where pessoas.id_pessoa = pessoas_tipos.id_pessoa
										and   pessoas_tipos.tipo_pessoa = 'r'
										and   pessoas_tipos.status_pessoa <> '2'
										and   pessoas.id_empresa = '". $_SESSION["id_empresa"] ."'
										") or die(mysql_error());
		
		while ($rs_artistas= mysql_fetch_object($result_artistas)) {
			
			$att=0;
			$str_att="";
			$str_att_1="";
			$str_att_2="";
			
			if ($rs_artistas->tags!="") {
				$tags= str_replace($rs_pre->tag_pt .", ", "", $rs_artistas->tags);
				$str_att_1.= "tags= '". $tags ."', ";
				$att=1;
			}
			
			if ($rs_artistas->tags_site_pt!="") {
				$tags_site_pt= str_replace($rs_pre->tag_pt .", ", "", $rs_artistas->tags_site_pt);
				$str_att_2.= "tags_site_pt= '". $tags_site_pt ."', ";
				$att=1;
			}
			
			if ($rs_artistas->tags_site_en!="") {
				$tags_site_en= str_replace($rs_pre->tag_en .", ", "", $rs_artistas->tags_site_en);
				$str_att_2.= "tags_site_en= '". $tags_site_en ."', ";
				$att=1;
			}
			
			if ($att) {
				if ($rs_pre->tipo_tag=="1") $str_att= $str_att_1 . $str_att_2;
				else $str_att= $str_att_1;
				
				$str_len= strlen($str_att);
				$str_len_nova= $str_len-2;
				
				$str_att= substr($str_att, 0, $str_len_nova);
				
				if ($str_att!="") {
					/*echo "update pessoas
							set 
							$str_att
							where id_pessoa = '". $rs_artistas->id_pessoa ."'
							<br /><br />
							";
					*/
					$result_atualiza= mysql_query("update pessoas
													set 
													$str_att
													where id_pessoa = '". $rs_artistas->id_pessoa ."'
													");
					if (!$result_atualiza) $var++;
				}
			}
		}
		
		$result= mysql_query("delete from tags
								where id_tag = '". $_GET["id"] ."'
								and   id_empresa = '". $_SESSION["id_empresa"] ."'
								");
		if (!$result) $var++;
		
		finaliza_transacao($var);
		echo $var;
	}
	
	if ($_GET["chamada"]=="curadoriaPaginaExcluir") {
		
		$var=0;
		inicia_transacao();
		
		$result_pre= mysql_query("select * from curadoria_paginas, curadoria_paginas_imagens
									where curadoria_paginas_imagens.id_curadoria_pagina = '". $_GET["id"] ."'
									and   curadoria_paginas_imagens.id_curadoria_pagina = curadoria_paginas.id_curadoria_pagina
									") or die(mysql_error());
									
		while ($rs_pre= mysql_fetch_object($result_pre)) {
			
			$result_limpa1= mysql_query("delete from curadorias_pessoas_imagens
										where id_imagem = '". $rs_pre->id_imagem ."'
										and   id_projeto = '". $rs_pre->id_projeto ."'
										and   id_curadoria = '". $rs_pre->id_curadoria ."'
										");
			if (!$result_limpa1) $var++;
			
		}
		
		$result_limpa2= mysql_query("delete from curadoria_paginas
										where id_curadoria_pagina = '". $_GET["id"] ."'
										limit 1
										");
		if (!$result_limpa2) $var++;
		
		$result_limpa3= mysql_query("delete from curadoria_paginas_imagens
									where id_curadoria_pagina = '". $_GET["id"] ."'
									");
		if (!$result_limpa3) $var++;
		
		finaliza_transacao($var);
		echo $var;
	}
	
	if ($_GET["chamada"]=="projetoExcluir") {
		
		$var=0;
		inicia_transacao();
		
		$result= mysql_query("update projetos
							  	set status_projeto = '0'
								where id_projeto = '". $_GET["id"] ."'
								");
		if (!$result) $var++;
		
		finaliza_transacao($var);
		echo $var;
	}
	
	if ($_GET["chamada"]=="curadoriaExcluir") {
		
		$var=0;
		inicia_transacao();
		
		$result= mysql_query("update curadorias
							  	set status_curadoria = '2'
								where id_curadoria = '". $_GET["id"] ."'
								");
		if (!$result) $var++;
		
		finaliza_transacao($var);
		echo $var;
	}
	
	if ($_GET["chamada"]=="excluiArtistaCuradoria") {
		
		$var=0;
		inicia_transacao();
		
		$result= mysql_query("delete from curadorias_pessoas
								where id_projeto= '". $_GET["id_projeto"] ."'
								and   id_curadoria= '". $_GET["id_curadoria"] ."'
								and   id_pessoa = '". $_GET["id_pessoa"] ."'
								") or die(mysql_error());
		if (!$result) $var++;
		
		$result2= mysql_query("delete from curadorias_pessoas_imagens
								where id_projeto= '". $_GET["id_projeto"] ."'
								and   id_curadoria= '". $_GET["id_curadoria"] ."'
								and   id_pessoa = '". $_GET["id_pessoa"] ."'
								") or die(mysql_error());
		if (!$result2) $var++;
		
		finaliza_transacao($var);
		echo $var;
	}
	
	if ($_GET["chamada"]=="adicionaArtistaCuradoria") {
		
		$var=0;
		inicia_transacao();
		
		$result_pre= mysql_query("select * from curadorias_pessoas
									where id_projeto = '". $_GET["id_projeto"] ."'
									and   id_curadoria = '". $_GET["id_curadoria"] ."'
									and   id_pessoa = '". $_GET["id_pessoa"] ."'
									");
		$linhas_pre= mysql_num_rows($result_pre);
		
		if ($linhas_pre==0) {
			
			$result_ordem= mysql_query("select * from curadorias_pessoas
										where id_projeto = '". $_GET["id_projeto"] ."'
										and   id_curadoria = '". $_GET["id_curadoria"] ."'
										order by ordem desc limit 1
										");
			$rs_ordem= mysql_fetch_object($result_ordem);
			$nova_ordem= $rs_ordem->ordem+1;
			
			$result= mysql_query("insert into curadorias_pessoas (id_projeto, id_curadoria, id_pessoa, ordem, id_usuario)
									values
									('". $_GET["id_projeto"] ."', '". $_GET["id_curadoria"] ."', '". $_GET["id_pessoa"] ."', '". $nova_ordem ."', '". $_SESSION["id_usuario"] ."')
									") or die(mysql_error());
			if (!$result) $var++;
		}
		else $var++;
		
		finaliza_transacao($var);
		echo $var;
	}
	
	if ($_GET["chamada"]=="buscaArtistaCuradoria") {
		
		if ($_GET["tags"]!="") {
			$pedaco= explode(",", $_GET["tags"]);
			$str="";
			
			$i=0;
			while ($i<count($pedaco)) {
				if ($pedaco[$i]!="") $str.= "and   pessoas.tags like '%". retira_acentos($pedaco[$i]) ."%' ";
				$i++;
			}
		}
		
		$result= mysql_query("select * from pessoas, pessoas_tipos
								where pessoas.id_pessoa = pessoas_tipos.id_pessoa
								and   pessoas_tipos.tipo_pessoa = 'r'
								and   pessoas_tipos.status_pessoa <> '2'
								and   ( pessoas.nome_rz like '%". $_GET["busca"] ."%' or pessoas.apelido_fantasia like '%". $_GET["busca"] ."%' )
								$str
								order by pessoas.nome_rz asc
								") or die(mysql_error());
		$linhas= mysql_num_rows($result);
		
		if ($linhas==0) echo "Nada encontrado.";
		else {
			echo "<ul>";
			
			while ($rs= mysql_fetch_object($result)) {
				//busca img
				$result_enviados= mysql_query("select * from imagens
                                                where id_externo = '". $rs->id_pessoa ."'
                                                and   tipo_imagem = 'a'
                                                and   id_empresa = '". $_SESSION["id_empresa"] ."'
                                                and   ( miniatura_destaque is NULL or miniatura_destaque = '0')
                                                order by ordem asc limit 1
                                                ") or die(mysql_error());
                $linhas_enviados= mysql_num_rows($result_enviados);
                $rs_enviados= mysql_fetch_object($result_enviados);
				
				echo "<li><a href=\"javascript:void(0);\" onclick=\"adicionaArtistaCuradoria('". $rs->id_pessoa ."', '". addslashes($rs->apelido_fantasia) ."',  '". $_GET["id_projeto"] ."', '". $_GET["id_curadoria"] ."');\"><img src='includes/timthumb/timthumb.php?src=". CAMINHO_CDN ."a_". $rs->id_pessoa ."/". $rs_enviados->nome_arquivo ."&w=50&h=50&zc=1&q=90' /><span>". $rs->apelido_fantasia ."</span></a></li>";
			}
			
			echo "</ul>";
		}
	}
	
	if (isset($_GET["verificaCnpj"])) {
		$cnpj= $_GET["cnpj"];
		$sql= "select pessoas.id_pessoa from pessoas
						where pessoas.cpf_cnpj = '". $cnpj ."'
						and   pessoas.tipo = 'j'
						and   pessoas.id_empresa = '". $_SESSION["id_empresa"] ."'
						";
		
		if ($_GET["id_pessoa"]!="")
			$sql .= " and pessoas.id_pessoa <> '". $_GET["id_pessoa"] ."' " ;
		
		$result= mysql_query($sql) or die(mysql_error());
		
		$campo[0]= "<input type=\"hidden\" name=\"passa_cnpj\" id=\"passa_cnpj\" value=\"\" class=\"escondido\" />";
		$campo[1]= "<input type=\"hidden\" name=\"passa_cnpj\" id=\"passa_cnpj\" value=\"1\" class=\"escondido\" />";
	
		if (mysql_num_rows($result)==0) {
			echo $campo[1] ."<span id=\"span_cnpj_testa\" class=\"verde\">CNPJ disponível!</span>";
			echo "<script language=\"javascript\">habilitaCampo('enviar');</script>";
		}
		else {
			$rs= mysql_fetch_object($result);
			
			$result2= mysql_query("select * from pessoas, pessoas_tipos
								 	where pessoas.id_pessoa = pessoas_tipos.id_pessoa
									and   pessoas.id_pessoa = '". $rs->id_pessoa ."'
									and   pessoas_tipos.status_pessoa <> '2'
									");
			$linhas2= mysql_num_rows($result2);
			
			$como= " como ";
			$pode_duplicar= true;
			
			$i=1;
			while ($rs2= mysql_fetch_object($result2)) {
				//if ($rs2->tipo_pessoa=='c') $pode_duplicar= false;
				$tipo_pessoa_intencao= $rs2->tipo_pessoa;
				
				$como .= "<strong>". pega_tipo_pessoa($rs2->tipo_pessoa) ."</strong>";
				
				if ($i!=$linhas2) $como .= ", ";
				
				$i++;
			}
			
			if (($_GET["tipo_pessoa"]!=$tipo_pessoa_intencao) && ($_GET["tipo_pessoa"]!="t")) {
				echo $campo[1] ."<span id=\"span_cnpj_testa\" class=\"vermelho\">CNPJ já cadastrado ". $como ."!</span>";
				
				if ($pode_duplicar)
					echo "<br /><label>&nbsp;</label><a class=\"menor\" href=\"javascript:void(0);\" onclick=\"cadastraNovoTipoPessoa('". $rs->id_pessoa ."', '". $_GET["tipo_pessoa"] ."');\">&raquo; cadastrar como <strong>". pega_tipo_pessoa($_GET["tipo_pessoa"]) ."</strong></a>";
				
				echo "<br /><label>&nbsp;</label> <span class=\"menor\">ou prossiga para cadastrar com o mesmo CNPJ</span>";
			}
		}
	}
	
	if (isset($_GET["cadastraNovoTipoPessoa"])) {
		$result_pre= mysql_query("select * from pessoas_tipos
									where id_pessoa = '". $_GET["id_pessoa"] ."'
									and   tipo_pessoa = '". $_GET["tipo_pessoa"] ."'
									and   id_empresa = '". $_SESSION["id_empresa"] ."'
									");
		$linhas_pre= mysql_fetch_object($result_pre);
		
		if ($linhas_pre==0)
			$result= mysql_query("insert into pessoas_tipos
									(id_pessoa, tipo_pessoa, id_empresa)
									values
									('". $_GET["id_pessoa"] ."', '". $_GET["tipo_pessoa"] ."', '". $_SESSION["id_empresa"] ."')
									");
		else
			$result= mysql_query("update pessoas_tipos
									set status_pessoa = '1'
									where id_pessoa = '". $_GET["id_pessoa"] ."'
									and   tipo_pessoa = '". $_GET["tipo_pessoa"] ."'
									and   id_empresa = '". $_SESSION["id_empresa"] ."'
									");
									
		$pagina= "financeiro/pessoa_listar";
		require_once("index2.php");
	}
	
	
	if (isset($_GET["alteraTipoPessoa"])) {
		if ($_GET["tipo_pessoa"]=='f') require_once("_financeiro/__pessoaf.php");
		else require_once("_financeiro/__pessoaj.php");
	}

	if ($_GET["chamada"]=="pessoaStatus") {
		$var=0;
		inicia_transacao();
		
		$result= mysql_query("update pessoas_tipos set status_pessoa = '". $_GET["status"] ."'
								where id_pessoa= '". $_GET["id"] ."'
								and   tipo_pessoa = '". $_GET["tipo"] ."'
								");
		if (!$result) $var++;
		
		if ($_GET["tipo"]=="u") {
		
			if ($_GET["status"]=="0") $novo_status=2;
			else $novo_status=1;
		
			$result2= mysql_query("update usuarios set status_usuario = '". $novo_status ."'
									where id_pessoa= '". $_GET["id"] ."'
									limit 1
									");
			if (!$result2) $var++;
		}
		
		finaliza_transacao($var);
		
		echo $var;
	}
	
	if ($_GET["chamada"]=="pessoaExcluir") {
		$var=0;
		inicia_transacao();
	
		$result= mysql_query("update pessoas_tipos set status_pessoa = '2'
								where id_pessoa= '". $_GET["id"] ."'
								and   tipo_pessoa = '". $_GET["tipo"] ."'
								");
		if (!$result) $var++;
	
		finaliza_transacao($var);
		
		echo $var;
	}
	
	if ($_GET["chamada"]=="alteraTipoContatoPessoa") {
		
		$result= mysql_query("select * from pessoas, pessoas_tipos
								where pessoas.id_pessoa = pessoas_tipos.id_pessoa
								and   pessoas_tipos.tipo_pessoa = '". $_GET["tipo_pessoa"] ."'
								and   pessoas_tipos.status_pessoa <> '2'
								order by pessoas.apelido_fantasia asc");
	
		$str= "<p><label for=\"id_pessoa\">". pega_tipo_contato($_GET["tipo_pessoa"]) .":</label><select name=\"id_pessoa\" id=\"id_pessoa\">
				<option value=\"\">-</option>";
		
		$i=1;
		while ($rs= mysql_fetch_object($result)) {
			if ($i==1) $classe= " class=\"cor_sim\"";
			else $classe= " ";
			$i++;
			$str .= "<option ". $classe ." value=\"". $rs->id_pessoa ."\">". $rs->apelido_fantasia ."</option>";
			if ($i==2) $i=0;
		}
		
		$str .= "</select></p>";
		echo $str;
		
		echo '
		<script language="javascript">
		$(function(){
			$("#div_id_pessoa select").uniform();
		  });
		</script>
		';
	}
	
	if ($_GET["chamada"]=="alteraContatos") {
		
		$result= mysql_query("select * from contatos
								where id_pessoa = '". $_GET["id_pessoa"] ."'
								order by nome asc");
	
		$str= "<p><label for=\"". $_GET["id"] ."\">". $_GET["label"] ."</label><select name=\"". $_GET["id"] ."\" id=\"". $_GET["id"] ."\">
				<option value=\"\">-</option>";
		
		$i=1;
		while ($rs= mysql_fetch_object($result)) {
			if ($i==1) $classe= " class=\"cor_sim\"";
			else $classe= " ";
			$i++;
			$str .= "<option ". $classe ." value=\"". $rs->id_contato ."\">". $rs->nome ."</option>";
			if ($i==2) $i=0;
		}
		
		$str .= "</select></p>";
		echo $str;
	}

	if ($_GET["chamada"]=="contatoExcluir") {
		
		$var=0;
		inicia_transacao();
		
		$result1= mysql_query("update contatos
								set   status_contato = '2'
								where id_contato= '". $_GET["id"] ."'
								and   id_empresa = '". $_SESSION["id_empresa"] ."'
								") or die(mysql_error());
		if (!$result1) $var++;
		
		finaliza_transacao($var);
		
		echo $var;
	}
	
}//fim

/* ---------------------------------------------------------------------------------------------------- */

//echo "</body></html>";

/* <div id="temp">
	<strong>id_usuario:</strong> <?= $_SESSION["id_usuario"]; ?> <br />
	<strong>tipo_usuario:</strong> <?= $_SESSION["tipo_usuario"]; ?> <br />
	<strong>id_empresa:</strong> <?= $_SESSION["id_empresa"]; ?> <br />
	<strong>nome:</strong> <?= $_SESSION["nome"]; ?> <br />
	<strong>permissao:</strong> <?= $_SESSION["permissao"]; ?> <br />
	<strong>trocando:</strong> <?= $_SESSION["trocando"]; ?>
</div>
*/
            
?>