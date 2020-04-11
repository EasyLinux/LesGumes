<?php
/**
 * gestion des menus
 */

function doMenu($sAction)
{
  switch($sAction)
  {
    case 'loadMenu':
      loadMenu();
      break;

    case 'listMenu':
      listMenu();
      break;

    default:
      $aRet = ["Errno" => -1,"ErrMsg" => "Fonction $sAction non définie dans menu.php"];
      header('content-type:application/json');
      echo json_encode($aRet); 
      break;
  }
}

function loadMenu()
{
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

}

function listMenu()
{
  $aLines = getMenuLines()["gauche"];
  //$html = nl2br(print_r(getMenuLines(),true));
  
  $html="";
  foreach($aLines as $Line)
  {
    //$html .= nl2br(print_r($Line,true));
    //$html .= "<br />";
    $html .= "<div>".$Line["label"];
    if( count($Line["childs"]) > 0 ){
      foreach($Line["childs"] as $child1)
      {
        $html .= "<div>".$child1["label"];
        if( count($child1["childs"]) > 0 ){
          foreach($child1["childs"] as $child2)
          {
            $html .= "<div>".$child2["label"]."</div>"; 
          }
        }
        $html .= "</div>";
      }
    }
    $html .= "</div>";
  }


  $aRet = ["Errno" => 0,"html" => $html];
  header('content-type:application/json');
  echo json_encode($aRet); 

}


function getMenuLines()
{
  include_once($_SERVER["DOCUMENT_ROOT"]."/config/config.php");
  include_once($_SERVER["DOCUMENT_ROOT"]."/vendor/autoload.php");
  include_once($_SERVER["DOCUMENT_ROOT"]."/class/autoload.php");

  $db = new cMariaDb($Cfg);

  $aRet = ["Errno" => 0, "ErrMsg" => "OK"];
  $sSQL  = "SELECT * FROM sys_menu WHERE type='up';";
  $aRet['up'] = $db->getAllFetch($sSQL);

  $sSQL = str_replace("up", "gauche", $sSQL);
  $aTmp = $db->getAllFetch($sSQL); 
  // réorganisation des données pour avoir une vue hiérarchique
  foreach($aTmp AS $menuItem )
  {
      if( $menuItem["parent"] == 0){
          $aRet['gauche'][] = $menuItem;
      }
  }  
  // Deuxième niveau / troisème niveau
 $Idx=0;
 while( $Idx < count($aRet['gauche']))
 {
      // Parcours menu de niveau 0
      $Idy=0;
      $IdNew=0;
      while( $Idy < count($aTmp) )
      {
          // S'il existe des sous-menu
          if( $aTmp[$Idy]["parent"] == $aRet['gauche'][$Idx]["id"]){
              // Entree de second niveau
              $aRet['gauche'][$Idx]["childs"][]=$aTmp[$Idy];
              
              $Idz = 0;
              while( $Idz < count($aTmp) )
              {
                  if($aTmp[$Idz]["parent"] == $aTmp[$Idy]["id"]){
                      $aRet['gauche'][$Idx]["childs"][$IdNew]["childs"][]=$aTmp[$Idz];
              }
              $Idz++;
              }
          $IdNew++;
          }
          $Idy++;
  }
  $Idx++;
 }
  
 return $aRet; 
}