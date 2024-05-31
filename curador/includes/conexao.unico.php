<?
session_start();

define("S3_KEY", "");
define("S3_SECRET", "");

//define("BUCKET", "http://curador.s3-website-sa-east-1.amazonaws.com/");
//define("BUCKET", "http://nortecurador-site.s3-website-us-east-1.amazonaws.com/");
define("BUCKET", "https://s3.amazonaws.com/nortecurador-site/");

define("BUCKET_CURADOR", "curador/uploads/");
define("BUCKET_SITE", "site/uploads/");

define("CAMINHO_CDN", BUCKET . BUCKET_CURADOR);


$conf_host="enceladus.cle1tvcm29jx.us-east-1.rds.amazonaws.com";
$conf_db="curador_db";

$conexao= @mysql_connect($conf_host, "user", "password") or die("O servidor est� um pouco instavel, favor tente novamente! ". mysql_error());
@mysql_select_db($conf_db) or die("O servidor esta um pouco instavel, favor tente novamente!! ". mysql_error());
mysql_query("set names utf8;");
?>