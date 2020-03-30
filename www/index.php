<?php
/**
 * Page principale
 * 
 * Récupérer les informations (menu et news), puis afficher la page
 */
include_once("config/config.php");

require_once("class/autoload.php");
require_once("vendor/autoload.php");
$db = new MariaDb($Cfg);
$system = new System($db->getDb());
// Generateur de templates
$tmpl = new Smarty();

// Pas encore connecté
$tmpl->assign("Connected",false);
// Récupérer les éléments de menu
$aMenu = $system->getMenu();
if( isset($aMenu["Errno"])) 
{
	die("ERREUR: Table sys_menu, veuillez contacter le responsable du site");
}
$tmpl->assign("Menu",$aMenu);
// Récupérer les news
$aNews = $system->getNews(5);
$tmpl->assign("News",$aNews);

// Afficher la page principale
$tmpl->display("main.smarty");
exit(0);
