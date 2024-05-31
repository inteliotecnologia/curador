<?
include("includes/conexao.unico.php");

define("NOME", "Möve");
define("URL_SISTEMA", "../../../");
define("URL_SITE", "http://norte.art.br/");

define("BUCKET", "http://nortecurador-site.s3-website-us-east-1.amazonaws.com/");
define("BUCKET_SITE", "site/uploads/");

//require_once('includes/phpthumb/phpThumb.config.php');

if ($_SESSION["l"]=="") {
	$_SESSION["l"]= pega_lingua($_SERVER['HTTP_USER_AGENT'], $_SERVER['HTTP_ACCEPT_LANGUAGE'], $_SERVER['REMOTE_ADDR']);
	$l= $_SESSION["l"];
}
else {
	$l= $_SESSION["l"];
}

/* ----- estava em controle.php ----- */

//echo $_SERVER['PHP_SELF']; die();

if (dirname($_SERVER['PHP_SELF'])==DIRECTORY_SEPARATOR) {
	$r= "/";
	$url= explode("/", substr($_SERVER['REQUEST_URI'], 1, 1000));
	
	//echo 123; die();
	
}
else {
	$r= dirname($_SERVER['PHP_SELF']) ."/";
	$root= explode("controle", $_SERVER['PHP_SELF']);
	$url= explode("/", str_replace($root[0], "", $_SERVER['REQUEST_URI']));
	
	//echo 456; die();
}

//echo "qwe:". $url[0] . $url[1];
//die();

$pagina= $url[0];

switch($pagina) {
	case "set":
		switch ($url[1]) {
			case "pt":
			case "en":
				$_SESSION["l"]= $url[1];
			break;
		}
		
		for ($i=2; $i<10; $i++) if ($url[$i]!="") $redir .= $url[$i] ."/";
		
		header("location: ". $r . $redir);
		break;
	case "form":
		
		break;
	case "opt":
		
		break;
	case "rss_projects":
			require_once("__rss_projects.php");
		break;
	case "rss_artists":
			require_once("__rss_artists.php");
		break;
	case "mini":
		
		$pasta= $url[1];
		$arquivo= $url[2];
		$modo= $url[3];
		
		switch ($modo) {
			case 1:
				$w=940;
				$h=380;
			break;
		}
		
		$src= URL_SISTEMA ."curador/uploads/". $pasta ."/". $arquivo;
		$zc=1;
		
		include("includes/phpthumb/phpThumb.php");
		//include("includes/phpthumb/phpThumb.php?src=". URL_SISTEMA ."curador/uploads/". $pasta ."/". $arquivo ."&w=". $w ."&h=". $h ."&zc=1");
		
		break;

	default:
		require_once("index.php");
}

?>