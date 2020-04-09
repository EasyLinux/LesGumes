<?php
/** Gestion des paramètres
 * 
 * Ce fichier gère les fonctionnalités Ajax lièes aux pseudo tables
 * 
 * @author    Serge NOEL <serge.noel@easylinux.fr>
 * @version   2.0
 */

/**
 * paramTables
 * 
 * Récupère les pseudo tables et leur contenu
 * 
 * @param      void
 * @return     array  Liste
 */
function paramTables()
{
  include_once($_SERVER["DOCUMENT_ROOT"]."/config/config.php");
  include_once($_SERVER["DOCUMENT_ROOT"]."/class/mariadb.class.php");
  $db = new cMariaDb($Cfg);
  $sSQL = "SELECT id, value, type, link, description FROM sys_parameter WHERE name IS NULL;";
  header('content-type:application/json');
  echo json_encode($db->getAllFetch($sSQL)); 
}

/** updateParameters
 * 
 * Modifie la pseudo table et renvoi les données à jour
 * @param  string $Type    Action à entreprendre <add|del|edit>
 * @param  string $Id      Identifiant concerné (0 si ajout)
 * @param  string $Value   Nom de l'item
 * @param  string $Table   Nom de la pseudo table liée (pour l'ajout)
 * @return array  Liste
 */
function updateParameters($Type,$Id,$Value,$Table)
{
  include_once($_SERVER["DOCUMENT_ROOT"]."/config/config.php");
  include_once($_SERVER["DOCUMENT_ROOT"]."/class/mariadb.class.php");
  $db = new cMariaDb($Cfg);

  switch($Type)
  {
    case 'add':
      $sSQL = "INSERT INTO sys_parameter SET name=NULL, value='$Value', type='string', link='table$Table',description=NULL;";
      $db->Query($sSQL);
      break;

    case 'del':
      $sSQL = "DELETE FROM sys_parameter WHERE id=$Id";
      $db->Query($sSQL);
      break;

    case 'edit':
      $sSQL = "UPDATE sys_parameter SET value='$Value' WHERE id=$Id;";
      $db->Query($sSQL);
      break; 

    default:
      error_log("ERREUR updateParameters('$Type',...)");
      break;
  }
  // rafraichir les données
  $sSQL = "SELECT id, value, type, link, description FROM sys_parameter WHERE name IS NULL;";
  header('content-type:application/json');
  echo json_encode($db->getAllFetch($sSQL)); 
}