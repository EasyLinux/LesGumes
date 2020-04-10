<?php
/**
 * gestion des menus
 */

function doMenu($sAction)
{
  switch($sAction)
  {
    case 'loadMenu':
      include_once($_SERVER["DOCUMENT_ROOT"]."/config/config.php");
      include_once($_SERVER["DOCUMENT_ROOT"]."/vendor/autoload.php");
      include_once($_SERVER["DOCUMENT_ROOT"]."/class/autoload.php");

      $db = new cMariaDb($Cfg);
      $sys = new cSystem($db->getDb());
      // Generateur de templates
      $tpl = new Smarty();
      $tpl->template_dir = $_SERVER["DOCUMENT_ROOT"]."/templates";
      $tpl->compile_dir =  $_SERVER["DOCUMENT_ROOT"]."/templates_c";
    
      // Récupérer les éléments de menu
      $aMenu = $sys->getMenu($_SESSION["Access"]);
      $tpl->assign("Menu",$aMenu);
      $sHtml = $tpl->fetch("menu.smarty");
      $aRet = ["Errno" => 0, "Html" => $sHtml ];
      header('content-type:application/json');
      echo json_encode($aRet); 
      break;

  }
}


