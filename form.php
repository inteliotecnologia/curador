<?
require_once("includes/funcoes.php");
if (!$conexao) require_once("includes/conexao.php");

header("Content-type: text/html; charset=iso-8859-1", true);
header("Cache-Control: no-store, no-cache, must-revalidate");
header("Cache-Control: post-check=0, pre-check=0", false);
header("Pragma: no-cache");

/*
echo '<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN"
		"http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
		<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">
		<body>'; 
*/


if ($_SESSION["tipo_usuario"]=="a") {
	if (isset($_GET["formEmpresaEmular"])) {
		$_SESSION["id_empresa"]= $_POST["id_empresa_emula"];
		header("location: ./");
	}
}//fim soh adm do sistema

if (pode("avrm", $_SESSION["permissao"])) {	
	if (isset($_GET["formPessoa"])) {
		if (($_POST["tipo_pessoa"]!="") && ($_POST["tipo"]!="") ) {
			//inserir empresa
			if ($_GET["acao"]=="i") {	
				$var=0;
				inicia_transacao();
				
				if ($_POST["origem"]=="pessoa_min") $_POST["nome_rz"]= $_POST["apelido_fantasia"];
				
				if ($_POST["tipo"]=='f') $cpf_cnpj= $_POST["cpf"];
				else $cpf_cnpj= $_POST["cnpj"];
				
				$num_pessoa= (pega_num_ultima_pessoa($_SESSION["id_empresa"], $_POST["tipo_pessoa"])+1);
				
				$result1= mysql_query("insert into pessoas (nome_rz, id_categoria, apelido_fantasia, rg_ie, im, cpf_cnpj, sexo, data, contato, tipo,
															sigla, auth, naturalidade, cidade_natal, estado, release_pt, release_en,
															exposicoes_individuais_pt, exposicoes_individuais_en,
															exposicoes_coletivas_pt, exposicoes_coletivas_en,
															publicacoes_pt, publicacoes_en,
															tags, curriculo_pt, curriculo_en, pessoa_id_pessoa, url, id_empresa) values
										('". addslashes($_POST["nome_rz"]) ."', '". $_POST["id_categoria"] ."', '". addslashes($_POST["apelido_fantasia"]) ."', '". addslashes($_POST["rg_ie"]) ."', '". addslashes($_POST["im"]) ."',
										'". $cpf_cnpj ."', '". $_POST["sexo"] ."', '". formata_data($_POST["data_nasc"]) ."', '". ($_POST["contato"]) ."','". $_POST["tipo"] ."',
										'". $_POST["sigla"] ."',
										
										'". $_POST["auth"] ."', '". $_POST["naturalidade"] ."', '". $_POST["cidade_natal"] ."', '". $_POST["estado"] ."', '". addslashes($_POST["release_pt"]) ."', '". addslashes($_POST["release_en"]) ."',
										
										'". addslashes($_POST["exposicoes_individuais_pt"]) ."', '". addslashes($_POST["exposicoes_individuais_en"]) ."',
										'". addslashes($_POST["exposicoes_coletivas_pt"]) ."', '". addslashes($_POST["exposicoes_coletivas_en"]) ."',
										'". addslashes($_POST["publicacoes_pt"]) ."', '". addslashes($_POST["publicacoes_en"]) ."',
										
										'". $_POST["tags"] ."', '". addslashes($_POST["curriculo_pt"]) ."', '". addslashes($_POST["curriculo_en"]) ."',
										'". $_POST["pessoa_id_pessoa"] ."', '". faz_url($_POST["apelido_fantasia"]) ."', '". $_SESSION["id_empresa"] ."' ) ") or die(mysql_error());
				if (!$result1) $var++;
				$id_pessoa= mysql_insert_id();
				
				$result1b= mysql_query("insert into pessoas_tipos (id_pessoa, num_pessoa, tipo_pessoa, status_pessoa, id_empresa) values
										('". $id_pessoa ."', '". $num_pessoa ."', '". $_POST["tipo_pessoa"] ."',
										'". $_POST["status_pessoa"] ."', '". $_SESSION["id_empresa"] ."') ") or die(mysql_error());
				if (!$result1b) $var++;
				
				$result2= mysql_query("insert into enderecos (id_pessoa, id_pais, pais, cidade_uf, cidade_uf_en, id_uf, id_cidade, rua, numero, complemento, bairro, cep)
										values
										('$id_pessoa', '". $_POST["id_pais"] ."', '". addslashes($_POST["pais"]) ."', '". addslashes($_POST["cidade_uf"]) ."', '". addslashes($_POST["cidade_uf_en"]) ."', '". $_POST["id_uf"] ."', '". $_POST["id_cidade"] ."', '". addslashes($_POST["rua"]) ."', '". $_POST["numero"] ."',
										'". addslashes($_POST["complemento"]) ."', '". addslashes($_POST["bairro"]) ."', '". $_POST["cep"] ."' )") or die(mysql_error());
				if (!$result2) $var++;
				
				$result2_banco= mysql_query("insert into pessoas_dados_bancarios (id_pessoa, id_empresa, nome_banco, agencia, conta, nome_titular, cpf_titular, digito, domicilio, endereco_banco, detalhes_internacionais, id_usuario)
										values
										('$id_pessoa', '". $_SESSION["id_empresa"] ."', '". addslashes($_POST["nome_banco"]) ."', '". $_POST["agencia"] ."', '". $_POST["conta"] ."',
										'". $_POST["nome_titular"] ."', '". $_POST["cpf_titular"] ."', '". $_POST["digito"] ."',
										'". addslashes($_POST["domicilio"]) ."', '". addslashes($_POST["endereco_banco"]) ."', '". addslashes($_POST["detalhes_internacionais"]) ."', '". $_SESSION["id_usuario"] ."' )") or die(mysql_error());
				if (!$result2_banco) $var++;
				
				//só se for uma empresa admin
				if ($_POST["tipo_pessoa"]=='a') {
					$result3 = mysql_query("insert into empresas (id_pessoa, status_empresa)
											values ('$id_pessoa', '1') ") or die(mysql_error());
					if (!$result3) $var++;
					
					$id_empresa_aqui= mysql_insert_id();
					
					/*if ($_FILES["foto"]["name"]!="") {
						$caminho= CAMINHO . "empresa_". $id_empresa_aqui .".jpg";
						@move_uploaded_file($_FILES["foto"]["tmp_name"], $caminho);
						@otimiza_foto($caminho, 195);
					}*/
				}
				
				if ($_POST["tipo_pessoa"]=='u') {
					$i=0;
					$permissao_insere= '.';
					
					while ($_POST["campo_permissao"][$i]) {
						$permissao_insere.= $_POST["campo_permissao"][$i];
						$i++;
					}
					$permissao_insere.= '.';
					
					$result1= mysql_query("insert into usuarios (id_empresa, id_pessoa, nome, usuario, senha, senha_sem, tipo_usuario, status_usuario, permissao, id_usuario_criou) values
											('". $_SESSION["id_empresa"] ."', '". $id_pessoa ."', '". $_POST["apelido_fantasia"] ."', '". $_POST["usuario"] ."', '". md5($_POST["senha"]) ."', '". $_POST["senha"] ."',
												'e', '1', '$permissao_insere', '". $_SESSION["id_usuario"] ."') ") or die("1: ". mysql_error());
					if (!$result1) $var++;
					
					// ------ - -- - - - - - - -
				}
				
				$passa_contato=1;
				
				if ($passa_contato) {
					$result4= mysql_query("insert into contatos (id_empresa, tipo_contato, nome, email, email_alternativo, msn, skype, gtalk, ichat, site,
													blog, flickr, twitter, facebook, outro, id_pessoa, status_contato, id_usuario)
													values (
													'". $_SESSION["id_empresa"] ."', '". $_POST["tipo_pessoa"] ."',
													'". addslashes($_POST["apelido_fantasia"]) ."', '". $_POST["email"] ."', '". $_POST["email_alternativo"] ."',
													'". $_POST["msn"] ."', '". $_POST["skype"] ."', '". $_POST["gtalk"] ."', '". $_POST["ichat"] ."', '". $_POST["site"] ."',
													'". $_POST["blog"] ."', '". $_POST["flickr"] ."', '". $_POST["twitter"] ."', '". $_POST["facebook"] ."', '". $_POST["outro"] ."',
													'$id_pessoa', '1', '". $_SESSION["id_usuario"] ."' ) ") or die("5: ". mysql_error());
					if (!$result4) $var++;
					$id_contato= mysql_insert_id();
					
					$i=0;
					while ($_POST["telefone"][$i]!="") {
						$result6[$i]= mysql_query("insert into contatos_telefones
												(id_empresa, id_contato, telefone, tipo, obs) values
												('". $_SESSION["id_empresa"] ."', '". $id_contato  ."', '". $_POST["telefone"][$i] ."',
												'". $_POST["tipo"][$i] ."', '". ($_POST["obs"][$i]) ."')
												") or die(mysql_error());
						if (!$result6[$i]) $var++;
						$i++;
					}
				}
				
				
				finaliza_transacao($var);
				
				$msg= $var;
				
				if ($_POST["origem"]=="pessoa_min") {
					header("location: ./index3.php?pagina=financeiro/pessoa_min&acao=e&tipo_pessoa=". $_POST["tipo_pessoa"] ."&id_pessoa=". $id_pessoa ."&msg=". $msg);
				}
				else {
					if ($_POST["pessoa_id_pessoa"]!="")
						header("location: ./?pagina=financeiro/pessoa&acao=e&id_pessoa=". $_POST["pessoa_id_pessoa"] ."&tipo_pessoa=r&msg=". $msg);
					else
						header("location: ./?pagina=financeiro/pessoa_listar&tipo_pessoa=". $_POST["tipo_pessoa"] ."&msg=". $msg);
				}
			}
				
			//editar pessoa
			if ($_GET["acao"]=="e") {
				$var=0;
				/*$result_pre= mysql_query("select pessoas.id_pessoa from pessoas
											where pessoas.id_pessoa <> '". $_POST["id_pessoa"] ."'
											and   pessoas.cpf_cnpj = '". $_POST["cnpj"] ."'
											");
				if (mysql_num_rows($result_pre)==0) {*/
					inicia_transacao();
					
					if ($_POST["cpf"]!='') $cpf_cnpj= $_POST["cpf"];
					else $cpf_cnpj= $_POST["cnpj"];
					
					$result1= mysql_query("update pessoas set
											pessoas.nome_rz= '". addslashes($_POST["nome_rz"]) ."',
											pessoas.id_categoria= '". ($_POST["id_categoria"]) ."',
											pessoas.apelido_fantasia= '". addslashes($_POST["apelido_fantasia"]) ."',
											pessoas.rg_ie= '". addslashes($_POST["rg_ie"]) ."',
											pessoas.im= '". addslashes($_POST["im"]) ."',
											pessoas.sexo= '". $_POST["sexo"] ."',
											pessoas.cpf_cnpj= '". $cpf_cnpj ."',
											pessoas.data= '". formata_data($_POST["data_nasc"]) ."',
											pessoas.contato= '". addslashes($_POST["contato"]) ."',
											pessoas.sigla= '". addslashes($_POST["sigla"]) ."',
											pessoas.naturalidade= '". addslashes($_POST["naturalidade"]) ."',
											pessoas.cidade_natal= '". addslashes($_POST["cidade_natal"]) ."',
											pessoas.estado= '". addslashes($_POST["estado"]) ."',
											pessoas.release_pt= '". addslashes($_POST["release_pt"]) ."',
											pessoas.release_en= '". addslashes($_POST["release_en"]) ."',
											
											pessoas.exposicoes_individuais_pt= '". addslashes($_POST["exposicoes_individuais_pt"]) ."',
											pessoas.exposicoes_individuais_en= '". addslashes($_POST["exposicoes_individuais_en"]) ."',
											pessoas.exposicoes_coletivas_pt= '". addslashes($_POST["exposicoes_coletivas_pt"]) ."',
											pessoas.exposicoes_coletivas_en= '". addslashes($_POST["exposicoes_coletivas_en"]) ."',
											pessoas.publicacoes_pt= '". addslashes($_POST["publicacoes_pt"]) ."',
											pessoas.publicacoes_en= '". addslashes($_POST["publicacoes_en"]) ."',
											
											pessoas.tags= '". addslashes($_POST["tags"]) ."',
											pessoas.curriculo_pt= '". addslashes($_POST["curriculo_pt"]) ."',
											pessoas.curriculo_en= '". addslashes($_POST["curriculo_en"]) ."'
											where pessoas.id_pessoa = '". $_POST["id_pessoa"] ."'
											") or die(mysql_error());
					if (!$result1) $var++;
					
					$result2= mysql_query("update pessoas, enderecos set
											enderecos.id_cidade= '". $_POST["id_cidade"] ."',
											enderecos.id_uf= '". $_POST["id_uf"] ."',
											enderecos.id_pais= '". $_POST["id_pais"] ."',
											enderecos.pais= '". $_POST["pais"] ."',
											enderecos.cidade_uf= '". addslashes($_POST["cidade_uf"]) ."',
											enderecos.cidade_uf_en= '". addslashes($_POST["cidade_uf_en"]) ."',
											enderecos.rua= '". addslashes($_POST["rua"]) ."',
											enderecos.numero= '". $_POST["numero"] ."',
											enderecos.complemento= '". addslashes($_POST["complemento"]) ."',
											enderecos.bairro= '". addslashes($_POST["bairro"]) ."',
											enderecos.cep= '". $_POST["cep"] ."'
											where pessoas.id_pessoa = '". $_POST["id_pessoa"] ."'
											and   pessoas.id_pessoa = enderecos.id_pessoa
											") or die(mysql_error());
					if (!$result2) $var++;
					
					$result2_banco_pre= mysql_query("select * from pessoas_dados_bancarios
														where id_pessoa = '". $_POST["id_pessoa"] ."'
														and   id_empresa = '". $_SESSION["id_empresa"] ."'
														") or die(mysql_error());
					$linhas2_banco_pre= mysql_num_rows($result2_banco_pre);
					
					if ($linhas2_banco_pre==0)
						$result2_banco= mysql_query("insert into pessoas_dados_bancarios (id_pessoa, id_empresa, nome_banco, agencia, conta, nome_titular, cpf_titular, digito, domicilio, endereco_banco, detalhes_internacionais, id_usuario)
												values
												('$id_pessoa', '". $_SESSION["id_empresa"] ."', '". $_POST["nome_banco"] ."', '". $_POST["agencia"] ."', '". $_POST["conta"] ."',
												'". $_POST["nome_titular"] ."', '". $_POST["cpf_titular"] ."', '". $_POST["digito"] ."',
												'". $_POST["domicilio"] ."', '". ($_POST["endereco_banco"]) ."', '". $_POST["detalhes_internacionais"] ."', '". $_SESSION["id_usuario"] ."' )") or die(mysql_error());
					else
						$result2_banco= mysql_query("update pessoas_dados_bancarios set
														nome_banco= '". addslashes($_POST["nome_banco"]) ."',
														agencia= '". $_POST["agencia"] ."',
														conta= '". $_POST["conta"] ."',
														
														nome_titular= '". $_POST["nome_titular"] ."',
														cpf_titular= '". $_POST["cpf_titular"] ."',
														digito= '". $_POST["digito"] ."',
														
														domicilio= '". addslashes($_POST["domicilio"]) ."',
														endereco_banco= '". addslashes($_POST["endereco_banco"]) ."',
														detalhes_internacionais= '". addslashes($_POST["detalhes_internacionais"]) ."'
														where id_pessoa = '". $_POST["id_pessoa"] ."'") or die(mysql_error());
					if (!$result2_banco) $var++;
					
					$result3= mysql_query("update pessoas_tipos set
											num_pessoa= '". $_POST["num_pessoa"] ."',
											status_pessoa= '". $_POST["status_pessoa"] ."'
											where id_pessoa = '". $_POST["id_pessoa"] ."'
											and   tipo_pessoa = '". $_POST["tipo_pessoa"] ."'
											") or die(mysql_error());
					if (!$result3) $var++;
					
					if ($_POST["tipo_pessoa"]=="u") {
						$i=0;
						$permissao_insere= '.';
						
						while ($_POST["campo_permissao"][$i]) {
							$permissao_insere.= $_POST["campo_permissao"][$i];
							
							$i++;
						}
						$permissao_insere.= '.';
						
						if ($_POST["senha"]!="")
							$linha_senha= " senha= '". md5($_POST["senha"]) ."', senha_sem= '". $_POST["senha"] ."', ";
						
						$result1= mysql_query("update usuarios set
												nome= '". $_POST["apelido_fantasia"] ."',
												usuario= '". $_POST["usuario"] ."',
												". $linha_senha ."
												permissao= '$permissao_insere'
												where id_usuario = '". $_POST["id_usuario"] ."'
												and   id_pessoa = '". $_POST["id_pessoa"] ."'
												") or die(mysql_error());
						if (!$result1) $var++;
						
						if ($_FILES["foto"]["name"]!="") {
							$caminho= "uploads/pessoas/pessoa_". $_POST["id_pessoa"] ."_". $_FILES["foto"]["name"];
							move_uploaded_file($_FILES["foto"]["tmp_name"], $caminho);
							
							$result_atualiza= mysql_query("update pessoas set foto = '$caminho'
															where id_pessoa = '". $_POST["id_pessoa"] ."'
															") or die(mysql_error());
							if (!$result_atualiza) $var++;
							
							//@otimiza_foto($caminho, 195);
						}
					}
					
					//-------------------------------------------------------------------------------------------------------
					
					$passa_contato=1;
					
					if ($passa_contato) {
						if ($_POST["id_contato"]!="") {
							$result4= mysql_query("update contatos set nome = '". addslashes($_POST["apelido_fantasia"]) ."',
															email = '". $_POST["email"] ."',
															email_alternativo = '". $_POST["email_alternativo"] ."',
															msn = '". $_POST["msn"] ."',
															skype = '". $_POST["skype"] ."',
															gtalk = '". $_POST["gtalk"] ."',
															ichat = '". $_POST["ichat"] ."',
															site = '". $_POST["site"] ."',
															blog = '". $_POST["blog"] ."',
															flickr = '". $_POST["flickr"] ."',
															twitter = '". $_POST["twitter"] ."',
															facebook = '". $_POST["facebook"] ."',
															outro = '". $_POST["outro"] ."',
															obs = '". $_POST["obs_contato"] ."'
															where id_contato = '". $_POST["id_contato"] ."'
															and   id_empresa = '". $_SESSION["id_empresa"] ."'
															") or die("5: ". mysql_error());
							$id_contato= $_POST["id_contato"];
						}
						else {
							$result4= mysql_query("insert into contatos (id_empresa, tipo_contato, nome, email, email_alternativo, msn, skype, gtalk, ichat, site,
													id_pessoa, status_contato, id_usuario)
													values (
													'". $_SESSION["id_empresa"] ."', '". $_POST["tipo_pessoa"] ."',
													'". addslashes($_POST["apelido_fantasia"]) ."', '". $_POST["email"] ."', '". $_POST["email_alternativo"] ."',
													'". $_POST["msn"] ."', '". $_POST["skype"] ."', '". $_POST["gtalk"] ."', '". $_POST["ichat"] ."', '". $_POST["site"] ."',
													
													'". $_POST["id_pessoa"] ."', '1', '". $_SESSION["id_usuario"] ."' ) ") or die("5: ". mysql_error());
							$id_contato= mysql_insert_id();
						}
						if (!$result4) $var++;
						
						$result_del= mysql_query("delete from contatos_telefones
													where id_contato = '". $id_contato ."'
													and   id_empresa = '". $_SESSION["id_empresa"] ."' ") or die(mysql_error());
						
						if (!$result_del) $var++;
						
						$i=0;
						while ($_POST["telefone"][$i]!="") {
							$result6[$i]= mysql_query("insert into contatos_telefones
													(id_empresa, id_contato, telefone, tipo, obs) values
													('". $_SESSION["id_empresa"] ."', '". $id_contato  ."', '". $_POST["telefone"][$i] ."',
													'". $_POST["tipo"][$i] ."', '". ($_POST["obs"][$i]) ."')
													") or die(mysql_error());
							if (!$result6[$i]) $var++;
							$i++;
						}
					}
					
					//-------------------------------------------------------------------------------------------------------
					
					if ($_POST["tipo_pessoa"]=="a") {
						if ($_FILES["foto"]["name"]!="") {
							$id_empresa_aqui= pega_id_empresa_da_pessoa($_POST["id_pessoa"]);
							$caminho= CAMINHO . "empresa_". $id_empresa_aqui .".jpg";
							@move_uploaded_file($_FILES["foto"]["tmp_name"], $caminho);
							@otimiza_foto($caminho, 195);
						}
					}
					
					finaliza_transacao($var);
				//} else { echo 4; $var++; }
				
				$msg= $var;
				
				if ($_POST["pessoa_id_pessoa"]!="")
					header("location: ./?pagina=financeiro/pessoa&acao=e&id_pessoa=". $_POST["pessoa_id_pessoa"] ."&tipo_pessoa=r&msg=". $msg);
				else
					header("location: ./?pagina=financeiro/pessoa&tipo_pessoa=". $_POST["tipo_pessoa"] ."&id_pessoa=". $_POST["id_pessoa"] ."&acao=e&msg=". $msg);
				/*
				if ($_POST["esquema"]=="") {
					$pagina= "financeiro/pessoa_listar";
					require_once("index2.php");
				}
				else {
					$pagina= "financeiro/pessoa";
					require_once("index2.php");
				}
				*/
				
			}//e
		}//fim teste variáveis
		else echo "Faltando dados.";
	}//formPessoa
	
	
	if (isset($_GET["formPessoaWeb"])) {
		if ($_POST["id_pessoa"]!="") {
			
			//editar pessoa
			if ($_GET["acao"]=="e") {
				$var=0;
				inicia_transacao();
				
				$result1= mysql_query("update pessoas set
										pessoas.site= '". $_POST["site"] ."',
										pessoas.texto_site_pt= '". addslashes($_POST["texto_site_pt"]) ."',
										pessoas.texto_site_en= '". addslashes($_POST["texto_site_en"]) ."',
										pessoas.url= '". $_POST["url"] ."',
										pessoas.selo_cor= '". $_POST["selo_cor"] ."',
										pessoas.tags_site_pt= '". addslashes($_POST["tags_site_pt"]) ."',
										pessoas.tags_site_en= '". addslashes($_POST["tags_site_en"]) ."',
										pessoas.destaque= '". addslashes($_POST["destaque"]) ."'
										where pessoas.id_pessoa = '". $_POST["id_pessoa"] ."'
										") or die(mysql_error());
				if (!$result1) $var++;
				
				finaliza_transacao($var);
				//} else { echo 4; $var++; }
				
				$msg= $var;
				
				header("location: ./?pagina=financeiro/pessoa_web&id_pessoa=". $_POST["id_pessoa"] ."&tipo_pessoa=". $_POST["tipo_pessoa"] ."&acao=e&msg=". $msg);
				
				/*
				if ($_POST["esquema"]=="") {
					$pagina= "financeiro/pessoa_listar";
					require_once("index2.php");
				}
				else {
					$pagina= "financeiro/pessoa";
					require_once("index2.php");
				}
				*/
				
			}//e
		}//fim teste variáveis
		else echo "Faltando dados.";
	}//formEmpresa
	
	if (isset($_GET["formContatoExcluirCheck"])) {
		
		$var=0;
		inicia_transacao();
		
		$i=0;
		while ($_POST["index"][$i]!="") {
			if ($_POST["id_contato"][$i]!="") {
				$result1= mysql_query("update contatos
										set   status_contato = '2'
										where id_contato= '". $_POST["id_contato"][$i] ."'
										and   id_empresa = '". $_SESSION["id_empresa"] ."'
										") or die(mysql_error());
				if (!$result1) $var++;
			}
			
			$i++;
		}
		
		finaliza_transacao($var);
		$msg=$var;
		
		header("location: ./?pagina=financeiro/pessoa_contatos&tipo_contato=". $_POST["tipo_contato"] ."&tipo_pessoa=". $_POST["tipo_contato"] ."&id_pessoa=". $_POST["id_pessoa"] ."&origem=". $_POST["origem"] ."&msg=". $msg);
	}
	
	if (isset($_GET["formContato"])) {
		if (($_SESSION["id_empresa"]!="") && ($_POST["nome"]!="")) {
			
			//inserir
			if ($_GET["acao"]=="i") {
				$var=0;
				inicia_transacao();
				
				$result_pre= mysql_query("select * from contatos
											where id_empresa = '". $_SESSION["id_empresa"] ."'
											and   nome = '". $_POST["nome"] ."' ") or die(mysql_error());
				
				if (mysql_num_rows($result_pre)==0) {
					
					$result1= mysql_query("insert into contatos (id_empresa, tipo_contato, id_pessoa, nome, cargo, sexo, data_nasc, email, email_alternativo, msn, skype, gtalk, ichat, site,
													blog, flickr, twitter, facebook, outro, obs, status_contato, id_usuario) values
											('". $_SESSION["id_empresa"] ."', '". $_POST["tipo_contato"] ."', '". $_POST["id_pessoa"] ."', '". $_POST["nome"] ."', '". $_POST["cargo"] ."',
												'". $_POST["sexo"] ."', '". formata_data($_POST["data_nasc"]) ."',
												
												'". $_POST["email"] ."', '". $_POST["email_alternativo"] ."', '". $_POST["msn"] ."', '". $_POST["skype"] ."', '". $_POST["gtalk"] ."', '". $_POST["ichat"] ."', '". $_POST["site"] ."',
													'". $_POST["blog"] ."', '". $_POST["flickr"] ."', '". $_POST["twitter"] ."', '". $_POST["facebook"] ."', '". $_POST["outro"] ."', '". $_POST["obs_contato"] ."', '1',
												'". $_SESSION["id_usuario"] ."' ) ") or die(mysql_error());
					if (!$result1) $var++;
					$id_contato= mysql_insert_id();
					
					$result_del= mysql_query("delete from contatos_telefones
													where id_contato = '". $id_contato ."'
													and   id_empresa = '". $_SESSION["id_empresa"] ."' ") or die(mysql_error());
					if (!$result_del) $var++;
					
					$i=0;
					while ($_POST["telefone"][$i]!="") {
						$result2[$i]= mysql_query("insert into contatos_telefones
												(id_empresa, id_contato, telefone, tipo, obs) values
												('". $_SESSION["id_empresa"] ."', '". $id_contato  ."', '". $_POST["telefone"][$i] ."',
												'". $_POST["tipo"][$i] ."', '". ($_POST["obs"][$i]) ."')
												") or die(mysql_error());
						if (!$result2[$i]) $var++;
						$i++;
					}
					
					
				} else $var++;
				
				$letra= strtolower(substr($_POST["nome"], 0, 1));
				
				finaliza_transacao($var);
				$msg= $var;
				
				if ($_POST["origem"]=="2") header("location: ./?pagina=financeiro/pessoa_contatos&tipo_contato=". $_POST["tipo_contato"] ."&tipo_pessoa=". $_POST["tipo_contato"] ."&id_pessoa=". $_POST["id_pessoa"] ."&origem=". $_POST["origem"] ."&msg=". $msg);
				else header("location: ./?pagina=contatos/contato_esquema&tipo_contato=". $_POST["tipo_contato"] ."&letra=". $letra ."&msg=". $msg);
			}
				
			//editar
			if ($_GET["acao"]=="e") {
				$var=0;
				inicia_transacao();
				
				$result_pre= mysql_query("select * from contatos_telefones
											where id_empresa = '". $_SESSION["id_empresa"] ."'
											and   telefone = '". $_POST["telefone"] ."'
											and   id_contato <> '". $_POST["id_contato"] ."'
											") or die(mysql_error());
				
				if (mysql_num_rows($result_pre)==0) {
						
					if ($_POST["tipo_contato"]==2) $id_pessoa_contato= $_POST["id_pessoa"];
					else $id_pessoa_contato= 0;
					
					$result1= mysql_query("update contatos set
											nome= '". $_POST["nome"] ."',
											tipo_contato= '". $_POST["tipo_contato"] ."',
											id_pessoa= '". $_POST["id_pessoa"] ."',
											cargo = '". $_POST["cargo"] ."',
											sexo = '". $_POST["sexo"] ."',
											data_nasc = '". formata_data($_POST["data_nasc"]) ."',
											email = '". $_POST["email"] ."',
											email_alternativo = '". $_POST["email_alternativo"] ."',
											msn = '". $_POST["msn"] ."',
											skype = '". $_POST["skype"] ."',
											gtalk = '". $_POST["gtalk"] ."',
											ichat = '". $_POST["ichat"] ."',
											site = '". $_POST["site"] ."',
											blog = '". $_POST["blog"] ."',
											flickr = '". $_POST["flickr"] ."',
											twitter = '". $_POST["twitter"] ."',
											facebook = '". $_POST["facebook"] ."',
											outro = '". $_POST["outro"] ."',
											obs = '". $_POST["obs_contato"] ."'
											where id_contato = '". $_POST["id_contato"] ."'
											") or die(mysql_error());
					if (!$result1) $var++;
					
					$result_del= mysql_query("delete from contatos_telefones
													where id_contato = '". $_POST["id_contato"] ."'
													and   id_empresa = '". $_SESSION["id_empresa"] ."' ") or die(mysql_error());
					
					if (!$result_del) $var++;
					
					$i=0;
					while ($_POST["telefone"][$i]!="") {
						$result2[$i]= mysql_query("insert into contatos_telefones
												(id_empresa, id_contato, telefone, tipo, obs) values
												('". $_SESSION["id_empresa"] ."', '". $_POST["id_contato"]  ."', '". $_POST["telefone"][$i] ."',
												'". $_POST["tipo"][$i] ."', '". ($_POST["obs"][$i]) ."')
												") or die(mysql_error());
						if (!$result2[$i]) $var++;
						
						$i++;
					}
					
				} else $var++;
				
				$letra= strtolower(substr($_POST["nome"], 0, 1));
				
				finaliza_transacao($var);
				$msg= $var;
				
				
				if ($_POST["origem"]=="2") header("location: ./?pagina=financeiro/pessoa_contatos&tipo_contato=". $_POST["tipo_contato"] ."&tipo_pessoa=". $_POST["tipo_contato"] ."&id_pessoa=". $_POST["id_pessoa"] ."&origem=". $_POST["origem"] ."&msg=". $msg);
				else header("location: ./?pagina=contatos/contato_esquema&tipo_contato=". $_POST["tipo_contato"] ."&letra=". $letra ."&msg=". $msg);
				
				//header("location: ./?pagina=contatos/contato_esquema&tipo_contato=". $_POST["tipo_contato"] ."&letra=". $letra ."&msg=". $msg);
			}//e
		}//fim teste variáveis
	}
	
	if (isset($_GET["formTag"])) {
		if ($_SESSION["id_empresa"]!="") {
			
			//inserir
			if ($_GET["acao"]=="i") {
				$var=0;
				inicia_transacao();
					
				$i=0;
				while ($_POST["tag_pt"][$i]!="") {
					$result2[$i]= mysql_query("insert into tags
											(id_empresa, tag_pt, tag_en, tipo_tag, id_usuario) values
											('". $_SESSION["id_empresa"] ."', '". $_POST["tag_pt"][$i] ."',
											'". $_POST["tag_en"][$i] ."', '". ($_POST["tipo_tag"][$i]) ."', '". $_SESSION["id_usuario"] ."')
											") or die(mysql_error());
					if (!$result2[$i]) $var++;
					$i++;
				}
			
				finaliza_transacao($var);
				$msg= $var;
				header("location: ./?pagina=financeiro/tag_listar&msg=". $msg);
			}
				
			//editar
			if ($_GET["acao"]=="e") {
				$var=0;
				inicia_transacao();
				
				$result_pre= mysql_query("select * from tags
											where id_tag = '". $_POST["id_tag"] ."'
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
						$tags= str_replace($rs_pre->tag_pt .", ", $_POST["tag_pt"] .", ", $rs_artistas->tags);
						$str_att_1.= "tags= '". $tags ."', ";
						$att=1;
					}
					
					if ($rs_artistas->tags_site_pt!="") {
						$tags_site_pt= str_replace($rs_pre->tag_pt .", ", $_POST["tag_pt"] .", ", $rs_artistas->tags_site_pt);
						$str_att_2.= "tags_site_pt= '". $tags_site_pt ."', ";
						$att=1;
					}
					
					if ($rs_artistas->tags_site_en!="") {
						$tags_site_en= str_replace($rs_pre->tag_en .", ", $_POST["tag_en"] .", ", $rs_artistas->tags_site_en);
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
				
				$result1= mysql_query("update tags set
										tag_pt= '". $_POST["tag_pt"] ."',
										tag_en= '". $_POST["tag_en"] ."',
										tipo_tag= '". $_POST["tipo_tag"] ."'
										where id_tag = '". $_POST["id_tag"] ."'
										") or die(mysql_error());
				if (!$result1) $var++;
				
				finaliza_transacao($var);
				$msg= $var;
				header("location: ./?pagina=financeiro/tag_listar&msg=". $msg);
			}//e
		}//fim teste variáveis
	}
	
	if (isset($_GET["formLegendaImagem"])) {
		if (($_SESSION["id_empresa"]!="") && ($_POST["id_externo"]!="")) {
			$var=0;
			inicia_transacao();
		
			$i=0;
			while ($_POST["id_imagem"][$i]!="") {
				$result[$i]= mysql_query("update  imagens
											set   legenda = '". $_POST["legenda"][$i] ."',
											legenda_en = '". $_POST["legenda_en"][$i] ."'
											where id_imagem = '". $_POST["id_imagem"][$i] ."'
											") or die(mysql_error());
											
				if (!$result[$i]) $var++;
				
				$i++;
			}
			
			$result_del= mysql_query("delete from videos
										where tipo_video = '". $_POST["tipo_imagem"] ."'
										and   id_externo = '". $_POST["id_externo"] ."'
										") or die(mysql_error());
			if (!$result_del) $var++;	
			
			$i=0;
			while ($_POST["url"][$i]!="") {
				
				if ($_POST["largura"][$i]!="") $largura= $_POST["largura"][$i];
				else $largura= pega_dimensao_padrao_video($_POST["tipo_imagem"], 'l');
				
				if ($_POST["altura"][$i]!="") $altura= $_POST["altura"][$i];
				else $altura= pega_dimensao_padrao_video($_POST["tipo_imagem"], 'a');
				
				$result2[$i]= mysql_query("insert into videos
										(id_empresa, tipo_video, id_externo, ordem, url, site, largura, altura, id_usuario) values
										('". $_SESSION["id_empresa"] ."', '". $_POST["tipo_imagem"] ."',
										'". $_POST["id_externo"] ."', '". $i ."', '". $_POST["url"][$i] ."', '". $_POST["site"][$i] ."',
										'$largura', '$altura', '". $_SESSION["id_usuario"] ."'
										)
										") or die(mysql_error());
				if (!$result2[$i]) $var++;
				
				$i++;
			}
			
			finaliza_transacao($var);
			
			if ($_POST["tipo_imagem"]=="a") $variavel= "id_pessoa";
			elseif ($_POST["tipo_imagem"]=="p") $variavel= "id_projeto";
			
			$msg= $var;
			header("location: ./?pagina=financeiro/imagens&". $variavel ."=". $_POST["id_externo"] ."&msg=". $msg);
		}
	}
	
	if (isset($_GET["formProjeto"])) {
		if (($_SESSION["id_empresa"]!="") && ($_POST["projeto_pt"]!="")) {
			
			//inserir
			if ($_GET["acao"]=="i") {
				$var=0;
				inicia_transacao();
			
				$result1= mysql_query("insert into projetos (id_empresa, id_agencia, id_cliente, projeto_pt, data_projeto, descricao, tecnicas_empregadas,
																midias, uso, uso_periodo, pracas_veiculacao, formato_entrega, id_contato_agencia, id_contato_agencia_diretor_arte,
																artistas_indicados, tags, prazo_pagamento, lingua_preferencial, url, id_usuario_contato, id_usuario_producao, status_projeto, id_usuario) values
										('". $_SESSION["id_empresa"] ."', '". $_POST["id_agencia"] ."', '". $_POST["id_cliente"] ."', '". addslashes($_POST["projeto_pt"]) ."', '". formata_data($_POST["data_projeto"]) ."', '". addslashes($_POST["descricao"]) ."', '". addslashes($_POST["tecnicas_empregadas"]) ."',
											'". addslashes($_POST["midias"]) ."', '". addslashes($_POST["uso"]) ."', '". addslashes($_POST["uso_periodo"]) ."', '". addslashes($_POST["pracas_veiculacao"]) ."', '". addslashes($_POST["formato_entrega"]) ."', '". $_POST["id_contato_agencia"] ."', '". $_POST["id_contato_agencia_diretor_arte"] ."', 
											'". addslashes($_POST["artistas_indicados"]) ."', '". addslashes($_POST["tags"]) ."', '". addslashes($_POST["prazo_pagamento"]) ."', '". $_POST["lingua_preferencial"] ."',
											'". faz_url($_POST["projeto_pt"]) ."', '". $_POST["id_usuario_contato"] ."', '". $_POST["id_usuario_producao"] ."', '1', '". $_SESSION["id_usuario"] ."' ) ") or die(mysql_error());
				if (!$result1) $var++;
				$id_projeto= mysql_insert_id();
				
				finaliza_transacao($var);
				$msg= $var;
				
				$_SESSION['mixpanel']= '
								mixpanel.track("Inseriu Projeto", {
									"ID Projeto":  "'. $id_projeto .'"
									"Projeto": "'. $_POST["projeto_pt"] .'"
								});
								';
				
				header("location: ./?pagina=financeiro/projeto&id_projeto=". $id_projeto ."&acao=e&msg=". $msg);
			}
				
			//editar
			if ($_GET["acao"]=="e") {
				$var=0;
				inicia_transacao();
				
				$result1= mysql_query("update projetos set
										id_agencia= '". $_POST["id_agencia"] ."',
										id_cliente= '". $_POST["id_cliente"] ."',
										projeto_pt= '". addslashes($_POST["projeto_pt"]) ."',
										data_projeto= '". formata_data($_POST["data_projeto"]) ."',
										descricao= '". addslashes($_POST["descricao"]) ."',
										tecnicas_empregadas= '". addslashes($_POST["tecnicas_empregadas"]) ."',
										midias= '". addslashes($_POST["midias"]) ."',
										uso= '". addslashes($_POST["uso"]) ."',
										uso_periodo= '". addslashes($_POST["uso_periodo"]) ."',
										pracas_veiculacao= '". addslashes($_POST["pracas_veiculacao"]) ."',
										formato_entrega= '". addslashes($_POST["formato_entrega"]) ."',
										id_contato_agencia= '". $_POST["id_contato_agencia"] ."',
										id_contato_agencia_diretor_arte= '". $_POST["id_contato_agencia_diretor_arte"] ."',
										artistas_indicados= '". addslashes($_POST["artistas_indicados"]) ."',
										prazo_pagamento= '". $_POST["prazo_pagamento"] ."',
										status_projeto= '". $_POST["status_projeto"] ."',
										id_usuario_contato= '". addslashes($_POST["id_usuario_contato"] ."',
										id_usuario_producao= '". $_POST["id_usuario_producao"]) ."',
										lingua_preferencial= '". $_POST["lingua_preferencial"] ."'
										where id_projeto = '". $_POST["id_projeto"] ."'
										and   id_empresa = '". $_SESSION["id_empresa"] ."'
										") or die(mysql_error());
				if (!$result1) $var++;
				
				$result_del= mysql_query("delete from projetos_contatos
											where id_projeto = '". $_POST["id_projeto"] ."'
											and   id_empresa = '". $_SESSION["id_empresa"] ."'
											");
				if (!$result_del) $var++;	
				
				$i=0;
				while ($_POST["contato"][$i]!="") {
					$result2[$i]= mysql_query("insert into projetos_contatos
											(id_projeto, contato, funcao, id_empresa) values
											( '". $_POST["id_projeto"] ."', '". $_POST["contato"][$i]  ."', '". $_POST["funcao"][$i] ."', '". $_SESSION["id_empresa"] ."' )
											") or die(mysql_error());
					if (!$result2[$i]) $var++;
					
					$i++;
				}
				
				$_SESSION['mixpanel']= '
								mixpanel.track("Editou Projeto", {
									"ID Projeto":  "'. $_POST["id_projeto"] .'"
									"Projeto": "'. $_POST["projeto_pt"] .'"
								});
								';
				
				finaliza_transacao($var);
				$msg= $var;
				header("location: ./?pagina=financeiro/projeto&id_projeto=". $_POST["id_projeto"] ."&acao=e&msg=". $msg);
			}//e
		}//fim teste variáveis
	}
	
	if (isset($_GET["formProjetoWeb"])) {
		
		foreach ($_POST as $key => $input_arr) {
			$_POST[$key] = @addslashes($input_arr);
		}
		
		if (($_SESSION["id_empresa"]!="") && ($_POST["id_projeto"]!="")) {
			
			//editar
			if ($_GET["acao"]=="e") {
				$var=0;
				inicia_transacao();
				
				$result1= mysql_query("update projetos set
										projeto_pt= '". $_POST["projeto_pt"] ."',
										projeto_en= '". $_POST["projeto_en"] ."',
										site= '". $_POST["site"] ."',
										selecionado= '". $_POST["selecionado"] ."',
										destaque= '". $_POST["destaque"] ."',
										resumo_pt= '". $_POST["resumo_pt"] ."',
										resumo_en= '". $_POST["resumo_en"] ."',
										texto_site_pt= '". $_POST["texto_site_pt"] ."',
										texto_site_en= '". $_POST["texto_site_en"] ."',
										texto_videos= '". $_POST["texto_videos"] ."',
										url= '". $_POST["url"] ."',
										selo_cor= '". $_POST["selo_cor"] ."',
										tags_site_en= '". $_POST["tags_site_en"] ."',
										tags_site_pt= '". $_POST["tags_site_pt"] ."'
										where id_projeto = '". $_POST["id_projeto"] ."'
										and   id_empresa = '". $_SESSION["id_empresa"] ."'
										") or die(mysql_error());
				if (!$result1) $var++;
				
				finaliza_transacao($var);
				$msg= $var;
				header("location: ./?pagina=financeiro/projeto_web&id_projeto=". $_POST["id_projeto"] ."&acao=e&msg=". $msg);
			}//e
		}//fim teste variáveis
	}
	
	if (isset($_GET["formPagina"])) {
		if (($_SESSION["id_empresa"]!="") && ($_POST["id_pagina"]!="")) {
			
			//inserir
			if ($_GET["acao"]=="i") {
				/*$var=0;
				inicia_transacao();
			
				$result1= mysql_query("insert into projetos (id_empresa, id_agencia, id_cliente, projeto_pt, data_projeto, descricao, tecnicas_empregadas,
																midias, uso, uso_periodo, pracas_veiculacao, formato_entrega, id_contato_agencia, id_contato_agencia_diretor_arte,
																artistas_indicados, tags, prazo_pagamento, url, status_projeto, id_usuario) values
										('". $_SESSION["id_empresa"] ."', '". $_POST["id_agencia"] ."', '". $_POST["id_cliente"] ."', '". addslashes($_POST["projeto_pt"]) ."', '". formata_data($_POST["data_projeto"]) ."', '". addslashes($_POST["descricao"]) ."', '". addslashes($_POST["tecnicas_empregadas"]) ."',
											'". addslashes($_POST["midias"]) ."', '". addslashes($_POST["uso"]) ."', '". addslashes($_POST["uso_periodo"]) ."', '". addslashes($_POST["pracas_veiculacao"]) ."', '". addslashes($_POST["formato_entrega"]) ."', '". $_POST["id_contato_agencia"] ."', '". $_POST["id_contato_agencia_diretor_arte"] ."', 
											'". addslashes($_POST["artistas_indicados"]) ."', '". addslashes($_POST["tags"]) ."', '". addslashes($_POST["prazo_pagamento"]) ."', '". faz_url($_POST["projeto_pt"]) ."', '1', '". $_SESSION["id_usuario"] ."' ) ") or die(mysql_error());
				if (!$result1) $var++;
				$id_projeto= mysql_insert_id();
				
				finaliza_transacao($var);
				$msg= $var;
				header("location: ./?pagina=financeiro/projeto&id_projeto=". $id_projeto ."&acao=e&msg=". $msg);
				*/
			}
				
			//editar
			if ($_GET["acao"]=="e") {
				$var=0;
				inicia_transacao();
				
				$result1= mysql_query("update paginas set
										pagina_pt= '". addslashes($_POST["pagina_pt"]) ."',
										pagina_en= '". addslashes($_POST["pagina_en"]) ."',
										
										destaque_pt= '". addslashes($_POST["destaque_pt"]) ."',
										destaque_en= '". addslashes($_POST["destaque_en"]) ."',
										
										conteudo_pt= '". addslashes($_POST["conteudo_pt"]) ."',
										conteudo_en= '". addslashes($_POST["conteudo_en"]) ."',
										
										ultima_edicao= '". date("Y-m-d H:i:s") ."'
										
										where id_pagina = '". $_POST["id_pagina"] ."'
										and   id_empresa = '". $_SESSION["id_empresa"] ."'
										") or die(mysql_error());
				if (!$result1) $var++;
				
				finaliza_transacao($var);
				$msg= $var;
				header("location: ./?pagina=web/pagina&id_pagina=". $_POST["id_pagina"] ."&acao=e&msg=". $msg);
			}//e
		}//fim teste variáveis
	}
	
	if (isset($_GET["formProjetoWeb"])) {
		if (($_SESSION["id_empresa"]!="") && ($_POST["id_projeto"]!="")) {
			
			//editar
			if ($_GET["acao"]=="e") {
				$var=0;
				inicia_transacao();
				
				$result1= mysql_query("update projetos set
										projeto_pt= '". $_POST["projeto_pt"] ."',
										projeto_en= '". $_POST["projeto_en"] ."',
										site= '". $_POST["site"] ."',
										selecionado= '". $_POST["selecionado"] ."',
										resumo_pt= '". $_POST["resumo_pt"] ."',
										resumo_en= '". $_POST["resumo_en"] ."',
										texto_site_pt= '". $_POST["texto_site_pt"] ."',
										texto_site_en= '". $_POST["texto_site_en"] ."',
										texto_videos= '". $_POST["texto_videos"] ."',
										url= '". $_POST["url"] ."',
										tags_site_en= '". $_POST["tags_site_en"] ."',
										tags_site_pt= '". $_POST["tags_site_pt"] ."'
										where id_projeto = '". $_POST["id_projeto"] ."'
										and   id_empresa = '". $_SESSION["id_empresa"] ."'
										") or die(mysql_error());
				if (!$result1) $var++;
				
				finaliza_transacao($var);
				$msg= $var;
				header("location: ./?pagina=financeiro/projeto_web&id_projeto=". $_POST["id_projeto"] ."&acao=e&msg=". $msg);
			}//e
		}//fim teste variáveis
	}
	
	if (isset($_GET["formImagemCrop"])) {
		
		inicia_transacao();
		$var=0;
		
		
		$result_pre_anterior= mysql_query("select * from imagens
											where id_externo = '". $_POST["id_externo"] ."'
											and   tipo_imagem = '". $_POST["tipo_imagem"] ."'
											and   id_empresa = '". $_SESSION["id_empresa"] ."'
											and   miniatura_destaque = '". $_POST["miniatura"] ."'
											order by ordem asc
											") or die(mysql_error());
		$rs_pre_anterior= mysql_fetch_object($result_pre_anterior);
		
		if ($_POST["tipo_imagem"]=="a") $pasta= "artista";
		else $pasta= "projeto";
		
		@unlink("../uploads/". $pasta ."_". $_POST["id_externo"] ."/". $rs_pre_anterior->nome_arquivo_site);
		
		$targ_w = pega_miniatura($_POST["miniatura"], 'l');
		$targ_h = pega_miniatura($_POST["miniatura"], 'a');
		
		if ($_POST["qualidade"]!="") $jpeg_quality = (int)$_POST["qualidade"];
		else $jpeg_quality = 100;
	
		$imagem= pega_imagem($_POST["tipo_imagem"], $_POST["id_externo"]);
	
		$src = BUCKET . BUCKET_CURADOR . $_POST["tipo_imagem"] ."_". $_POST["id_externo"] ."/". $imagem;
		
		/*
		echo "qqq: ".$src;
		die();
		*/
		
		$img_r = imagecreatefromjpeg($src);
		$dst_r = imagecreatetruecolor( $targ_w, $targ_h );
		
		imageantialias($dst_r, true);
		
		$salvou= imagecopyresampled($dst_r,$img_r,0,0,($_POST['x']/$_POST['zoom']),($_POST['y']/$_POST['zoom']),$targ_w,$targ_h, ($_POST['w']/$_POST['zoom']), ($_POST['h']/$_POST['zoom']));
		
		$imagem_miniatura= "miniatura_". $_POST["miniatura"] ."_". $imagem;
		
		//echo $salvou; die();
		
		//header('Content-type: image/png');
		$png= imagepng($dst_r, "../uploads/" . $pasta ."_". $_POST["id_externo"] ."/". $imagem_miniatura, 9);
		
		//$jpg= imagejpeg($dst_r, "../uploads/" . $pasta ."_". $_POST["id_externo"] ."/". $imagem_miniatura, $jpeg_quality);
		
		//echo $png .' ';
		
		//echo "oi ". "uploads/" . $pasta ."_". $_POST["id_externo"] ."/". $imagem_miniatura; die();
		//die();
		
		
		//Envia para S3
		if (!class_exists('S3')) require_once 'includes/amazon-s3-php-class/S3.php';
		
		// Check for CURL
		if (!extension_loaded('curl') && !@dl(PHP_SHLIB_SUFFIX == 'so' ? 'curl.so' : 'php_curl.dll'))
			exit("\nERROR: CURL extension not loaded\n\n");
		
		$s3 = new S3(S3_KEY, S3_SECRET);
		
		$bucket = "nortecurador";
		
		$envia_s3= $s3->putObjectFile("../uploads/" . $pasta ."_". $_POST["id_externo"] ."/". $imagem_miniatura, $bucket, BUCKET_CURADOR . $_POST["tipo_imagem"] ."_". $_POST["id_externo"] ."/". $imagem_miniatura, S3::ACL_PUBLIC_READ);
		
		//echo CAMINHO . $_POST["tipo_imagem"] ."_". $_POST["id_externo"] ."/". $imagem_miniatura;
		//die();
		
		
		$result_pre= mysql_query("delete from imagens
									where id_externo = '". $_POST["id_externo"] ."'
									and   tipo_imagem = '". $_POST["tipo_imagem"] ."'
									and   id_empresa = '". $_SESSION["id_empresa"] ."'
									and   miniatura_destaque = '". $_POST["miniatura"] ."'
									order by ordem asc
									") or die(mysql_error());
		if (!$result_pre) $var++;
		
		$result_insere= mysql_query("insert into imagens
										(tipo_imagem, id_externo, id_empresa, nome_arquivo, ordem, legenda, dimensao_imagem, largura, altura, site, miniatura_destaque, id_usuario)
										values
										('". $_POST["tipo_imagem"] ."', '". $_POST["id_externo"] ."', '". $_SESSION["id_empresa"] ."', '". $imagem_miniatura ."', '0', '',
										'0', '". $targ_w ."', '". $targ_h ."', '1', '". $_POST["miniatura"] ."', '". $_SESSION["id_usuario"] ."')
										");
		if (!$result_insere) $var++;
		
		$id_imagem= mysql_insert_id();
		
		cria_imagem_site(1, $id_imagem, $jpeg_quality);
		
		//unlink("uploads/". $_POST["tipo_imagem"] ."_". $_POST["id_externo"] ."/". $imagem_miniatura);
		
		finaliza_transacao($var);
		
		$msg= $var;
		
		if ($_POST["tipo_imagem"]=="a")
			$id_chamada= "id_pessoa";
		elseif ($_POST["tipo_imagem"]=="p")
			$id_chamada= "id_projeto";
		
		header("location: ./?pagina=financeiro/imagens_miniaturas&". $id_chamada ."=". $_POST["id_externo"] ."&acao=e&miniatura=". $_POST["miniatura"] ."&msg=". $msg);
		
		//exit;
	}
	
	if (isset($_GET["formPessoaNota"])) {
		if (($_SESSION["id_empresa"]!="") && ($_POST["nota"]!="")) {
			//inserir
			if ($_GET["acao"]=="i") {	
				inicia_transacao();
				$var=0;
				
				$result1= mysql_query("insert into pessoas_notas (id_pessoa, nota, tipo_nota, data_nota, hora_nota, avaliacao, status_nota, id_usuario, id_empresa) values
										('". $_POST["id_pessoa"] ."', '". $_POST["nota"] ."', '". $_POST["tipo_nota"] ."', '". formata_data($_POST["data_nota"]) ."',
										'". date("H:i:s") ."', '". $_POST["avaliacao"] ."', '1', '". $_SESSION["id_usuario"] ."', '". $_SESSION["id_empresa"] ."') ") or die(mysql_error());
				if (!$result1) $var++;

				finaliza_transacao($var);
				$msg= $var;
				
				if ($_POST["tipo_nota"]=="c")
					header("location: ./?pagina=contatos/contato_notas&origem=". $_POST["origem"] ."&id_contato=". $_POST["id_pessoa"] ."&msg=". $msg);
				else
					header("location: ./?pagina=financeiro/pessoa_notas&tipo_pessoa=". $_POST["tipo_pessoa"] ."&id_pessoa=". $_POST["id_pessoa"] ."&msg=". $msg);
			}
				
			//editar
			if ($_GET["acao"]=="e") {
				$var=0;
			
				inicia_transacao();
				
				$result1= mysql_query("update pessoas_notas set
										nota= '". $_POST["nota"] ."',
										data_nota= '". formata_data($_POST["data_nota"]) ."',
										avaliacao= '". $_POST["avaliacao"] ."'
										where id_pessoa_nota = '". $_POST["id_pessoa_nota"] ."'
										and   id_empresa = '". $_SESSION["id_empresa"] ."'
										and   id_pessoa = '". $_POST["id_pessoa"] ."'
										") or die(mysql_error());
				if (!$result1) $var++;
				
				finaliza_transacao($var);
				
				$msg= $var;
				
				if ($_POST["tipo_nota"]=="c")
					header("location: ./?pagina=contatos/contato_notas&origem=". $_POST["origem"] ."&id_contato=". $_POST["id_pessoa"] ."&msg=". $msg);
				else
					header("location: ./?pagina=financeiro/pessoa_notas&tipo_pessoa=". $_POST["tipo_pessoa"] ."&id_pessoa=". $_POST["id_pessoa"] ."&msg=". $msg);
			}//e
		}//fim teste variáveis
	}
	
	
	if (isset($_GET["formCuradoriaPasso1"])) {
		if (($_SESSION["id_empresa"]!="") && ($_POST["id_projeto"]!="") && ($_POST["id_curadoria"]!="")) {
			$var=0;
			inicia_transacao();
			
			$i=0;
			$mostrar_insere= '.';
			
			while ($_POST["mostrar"][$i]!="") {
				$mostrar_insere.= $_POST["mostrar"][$i];
				$i++;
			}
			$mostrar_insere.= '.';
			
			$result1= mysql_query("update curadorias set
									titulo_curadoria= '". addslashes($_POST["titulo_curadoria"]) ."',
									num_curadoria= '". addslashes($_POST["num_curadoria"]) ."',
									data_curadoria= '". formata_data($_POST["data_curadoria"]) ."',
									mostrar= '". $mostrar_insere ."'
									where id_curadoria = '". $_POST["id_curadoria"] ."'
									and   id_empresa = '". $_SESSION["id_empresa"] ."'
									and   id_projeto = '". $_POST["id_projeto"] ."'
									") or die(mysql_error());
			if (!$result1) $var++;
			
			
			/*
			$result_limpa= mysql_query("delete from curadorias_pessoas_notas
										where id_curadoria = '". $_POST["id_curadoria"] ."'
										and   id_projeto = '". $_POST["id_projeto"] ."'
										");
			if (!$result_limpa) $var++;
			*/
			
			
			$i=0;
			while ($_POST["id_pessoa"][$i]!="") {
				
				$result1= mysql_query("update curadorias_pessoas set
										notas= '". addslashes($_POST["notas"][$i]) ."'
										where id_curadoria = '". $_POST["id_curadoria"] ."'
										and   id_projeto = '". $_POST["id_projeto"] ."'
										and   id_pessoa = '". $_POST["id_pessoa"][$i] ."'
										") or die(mysql_error());
				if (!$result1) $var++;
				
				/*$result[$i]= mysql_query("insert into curadorias_pessoas_notas
											(id_curadoria, id_projeto, id_pessoa, nota_curadoria_pessoa, id_usuario)
											values
											('". $_POST["id_curadoria"] ."', '". $_POST["id_projeto"] ."',
											'". $_POST["id_pessoa"][$i] ."', '". $_POST["nota_curadoria_pessoa"][$i] ."', '". $_SESSION["id_usuario"] ."')
											") or die(mysql_error());
											
				if (!$result[$i]) $var++;
				*/
				
				$i++;
			}
			
			
			finaliza_transacao($var);
			
			$_SESSION['mixpanel']= '
								mixpanel.track("Monta Curadoria", {
									"ID Projeto": "'. $_POST["id_projeto"] .'",
									"ID Curadoria": "'. $_POST["id_curadoria"] .'"
								});
								';
			
			$msg= $var;
			header("location: ./?pagina=financeiro/curadoria_passo2&id_projeto=". $_POST["id_projeto"] ."&id_curadoria=". $_POST["id_curadoria"] ."&reorganizar=". $_POST["reorganizar"] ."&msg=". $msg);
		}
		else echo "Faltam dados.";
	}
	
}

// ############################################### MENSAGENS ###############################################



// ############################################### ADM EMPRESA ###############################################
	
if (pode("a", $_SESSION["permissao"])) {
	
	/*if (isset($_GET["formUsuario"])) {
		if (($_POST["id_empresa"]!="") && ($_POST["usuario"]!="")) {
			//inserir
			if ($_GET["acao"]=="i") {
				if ($_POST["senha"]!="") {
					
					$var=0;
					
					
					//if (mysql_num_rows($result_pre)==0) {
						inicia_transacao();
						
						$i=0;
						$permissao_insere= '.';
						
						while ($_POST["campo_permissao"][$i]) {
							$permissao_insere.= $_POST["campo_permissao"][$i];
							$i++;
						}
						$permissao_insere.= '.';
						
						$result1= mysql_query("insert into usuarios (id_empresa, nome, usuario, senha, senha_sem, tipo_usuario, status_usuario, permissao, id_usuario_criou) values
												('". $_POST["id_empresa"] ."', '". $_POST["nome"] ."', '". $_POST["usuario"] ."', '". md5($_POST["senha"]) ."', '". $_POST["senha"] ."',
													'e', '1', '$permissao_insere', '". $_SESSION["id_usuario"] ."') ") or die("1: ". mysql_error());
						if (!$result1) $var++;
						
						finaliza_transacao($var);
					//} else $var++;
				}//senha em branco
				else $var++;
				
				$msg= $var;	
				header("location: ./?pagina=acesso/usuario_listar&msg=". $msg);
			}
				
			//editar
			if ($_GET["acao"]=="e") {
				$var=0;
				inicia_transacao();
				
				$i=0;
				$permissao_insere= '.';
				
				while ($_POST["campo_permissao"][$i]) {
					$permissao_insere.= $_POST["campo_permissao"][$i];
					$i++;
				}
				$permissao_insere.= '.';
				
				if ($_POST["senha"]!="")
					$linha_senha= " senha= '". md5($_POST["senha"]) ."', senha_sem= '". $_POST["senha"] ."', ";
				
				$result1= mysql_query("update usuarios set
										nome= '". $_POST["nome"] ."',
										usuario= '". $_POST["usuario"] ."',
										". $linha_senha ."
										permissao= '$permissao_insere'
										where id_usuario = '". $_POST["id_usuario"] ."'
										") or die(mysql_error());
				if (!$result1) $var++;
				
				finaliza_transacao($var);
				
				$msg= $var;	
				header("location: ./?pagina=acesso/usuario_listar&msg=". $msg);
			}//e
		}//fim teste variáveis
	}*/
	
}// fim pode a

//echo '</body></html>';

?>