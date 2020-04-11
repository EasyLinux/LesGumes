<?php
/**
 * Page principale
 * 
 * Récupérer les informations (menu et news), puis afficher la page
 */
include_once("config/config.php");

require_once("class/autoload.php");
require_once("vendor/autoload.php");

session_start();
// Generateur de templates

$tmpl = new Smarty();
$db = new cMariaDb($Cfg);
$sys = new cSystem($db->getDb());

error_log(print_r($_SESSION,true));
if( !isset($_SESSION["User"]) || $_SESSION["User"] == "None" ) {
	// Pas d'utilisateur connecté
	$_SESSION["User"] = "None";
	$_SESSION["Access"] = $sys->getPublicRight();
	$tmpl->assign("Connected",false);
} else {
	error_log("SESSION" . print_r($_SESSION,true));
	$tmpl->assign("Connected",true);
	$tmpl->assign("Raisoc",$_SESSION["User"]["sPrenom"]." ".$_SESSION["User"]["sNom"]);
}

// Récupérer les éléments de menu
$aMenu = $sys->getMenu($_SESSION["Access"]);
if( $aMenu["Errno"] != 0 ) 
{
	die("ERREUR: Table sys_menu, veuillez contacter le responsable du site");
}

$tmpl->assign("Menu",$aMenu);
// Récupérer les news
$aNews = $sys->getNews(5);
$tmpl->assign("News",$aNews);

// Afficher la page principale
$tmpl->display("main.smarty");
exit(0);
