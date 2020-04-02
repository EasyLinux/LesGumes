<?php
/**
 * gestion des droits
 * 
 * Ce fichier contient la logique applicative de gestion des droits 
 * il agit sur la table sys_right
 */
 
/**
 * listRights
 * 
 * Renvoi le contenu de la table sys_right
 * 
 * @param  void
 * @return array   Contenu de la table
 */
function listRights()
{
  require_once(__DIR__."/../config/config.php");
  require_once(__DIR__."/../vendor/autoload.php");
  require_once(__DIR__."/../class/autoload.php");

  $db = new cMariaDb($Cfg);

  $sSQL = "SELECT * FROM sys_right;";
  return $db->getAllFetch($sSQL);
}

/**
 * saveRights
 * 
 * Enregistre le contenu 
 * 
 * @param  string  id      Identifiant de l'enregistrement ou 0 si nouveau
 * @param  string  label   label
 * @param  string  desc    description
 * @return array   id, code erreur
 */
function saveRights($id,$label,$desc)
{
  $aRet = ["Errno" => 0];
  require_once(__DIR__."/../config/config.php");
  require_once(__DIR__."/../vendor/autoload.php");
  require_once(__DIR__."/../class/autoload.php");

  $db = new cMariaDb($Cfg);
  if( $i == 0 ) {
    $sSQL = "INSERT INTO sys_right SET Label='$label', Description='$desc';";
    $db->Query($sSQL);
    $aRet = ["Errno" => 0,"id" => $db->getLastId()];
  }
  else {
    $sSQL = "UPDATE sys_right SET Label='$label', Description='$desc' WHERE id=$id;";
    $db->Query($sSQL);
    $aRet = ["Errno" => 0,"id" => $id];
  }
  return $aRet;
}

/**
 * delRights
 * 
 * Supprime une entrée dans la table sys_right. Cette fonction vérifie que le droit 
 * en cours de suppression n'est pas utilisé dans une règle
 * 
 * @param  string  id      Identifiant de l'enregistrement à supprimer
 * @return array   id, code erreur
 */
function delRights($id)
{
  $iErrno = 0;
  $sErrMsg = "Ce droit est utilisé : <ul>";
  require_once(__DIR__."/../config/config.php");
  require_once(__DIR__."/../vendor/autoload.php");
  require_once(__DIR__."/../class/autoload.php");

  $db = new cMariaDb($Cfg);
  // Utilisé dans un menu ?
  $sSQL = "SELECT * FROM sys_menu_rights WHERE idRights=$id;";
  $db->Query($sSQL);
  if( $db->getNumRows() > 0 ){
    $iErrno = -1;
    $sErrMsg .= "<li>une ligne de menu</li>";
  }
  // Utilisé par un utilisateur ?
  $sSQL = "SELECT * FROM sys_user_rights WHERE idRights=$id;";
  $db->Query($sSQL);
  if( $db->getNumRows() > 0 ){
    $iErrno = -1;
    $sErrMsg .= "<li>un utilisateur</li>";
  }

  if( $iErrno == 0){
    $sSQL = "DELETE FROM sys_right WHERE Id=$id;";
    $db->Query($sSQL);
    $sErrMsg = "Enregistrement supprimé";
  } else {
    $sErrMsg .= "</ul>";
  }
  $aRet = ["Errno" => $iErrno, "ErrMsg" => $sErrMsg];
  return $aRet;
}