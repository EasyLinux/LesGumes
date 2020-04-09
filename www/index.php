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
// Pas d'utilisateur connecté
$_SESSION["User"] = "None";

$db = new cMariaDb($Cfg);
$sys = new cSystem($db->getDb());
// Accès 'public' uniquement
$_SESSION["Access"] = $sys->getPublicRight();
// Generateur de templates
$tmpl = new Smarty();

// Pas encore connecté
$tmpl->assign("Connected",false);
// Récupérer les éléments de menu
$aMenu = $sys->getMenu($_SESSION["Access"]);
if( isset($aMenu["Errno"])) 
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
