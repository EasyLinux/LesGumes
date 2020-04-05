<?php
require_once(__DIR__."/../class/autoload.php");
if( !include_once(__DIR__."/../vendor/autoload.php") ){
  echo "<h2>Installation en cours ...</h2>";
  echo "<b>ERREUR FATALE</b><br />";
  echo "Vous avez récupéré le site depuis les sources, dans ce cas, il faut utiliser 'composer' pour ";
  echo "disposer de l'ensemble des plugins.<br />";
  echo "Comment procéder : <ol>";
  echo "<li>Récupérer 'composer' un outil lié à php depuis <a href='https://getcomposer.org/'> ce site</a></li>";
  echo "<li>Lancer 'composer update' depuis le répertoire www</li>";
  echo "<li>Recharger cette page</li>";
  echo "</ol>";
  die();
}

if( !is_writable(__DIR__."/../templates_c")){
  $iErr=-1;
  echo "<b>ERREUR</b> - le répertoire <i>/templates_c</i> doit pouvoir être modifié par apache<br/>";
  echo "Impossible de continuer";
  die();
} 

$smarty = new Smarty;
// $smarty->setTemplateDir("../templates_c");
// // view the template dir chain
// var_dump($smarty->getTemplateDir());
$smarty->display("install.smarty");
die();

