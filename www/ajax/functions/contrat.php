<?php

function contrat($sAction, $sVars)
{
  switch($sAction)
  {
    case 'getProducteur':
    case 'getReferent':
    case 'getSuppleant':
      require_once($_SERVER["DOCUMENT_ROOT"]."/config/config.php");
      require_once($_SERVER["DOCUMENT_ROOT"]."/class/autoload.php");
  
      $db = new cMariaDb($Cfg);
    
      $sSQL  = "SELECT sys_user.id, CONCAT(sNom,' ',sPrenom) AS Raisoc FROM sys_user ORDER BY Raisoc;";
      $aRet = $db->getAllFetch($sSQL);
      break;

    case 'save':
      $aRet = saveContrat($sVars);
      break;

    case 'getInfo':
      $aRet = getInfo($sVars);
      break;

    default:
      $aRet = ["Errno" => -1, "ErrMsg" => "Action: $sAction indéfinie dans contrat.php"];
  }
  header('content-type:application/json');
  echo json_encode($aRet); 
}

function saveContrat($sVars)
{
  require_once($_SERVER["DOCUMENT_ROOT"]."/config/config.php");
  require_once($_SERVER["DOCUMENT_ROOT"]."/class/autoload.php");
  // $sVars = "{'id':'0','Name':'Contrat 1','Type':'34','idProducteur':'212',
  //  'idReferent':'355','curSeason':'2019/2020','locked':'true','Start':'01/05',
  // 'End':'30/04','nbPeople':'2','price':'135'}";
  $aVars = json_decode($sVars,true);
  $db = new cMariaDb($Cfg);

  if( $aVars["id"] == 0 ){
    $sSQL  = "INSERT INTO sys_contrat VALUES (";
    $sSQL .= $aVars["id"].",".$aVars["Type"].", ";
    $sSQL .= $aVars["idProducteur"] .", ".$aVars["idReferent"].", '";
    $sSQL .= $aVars["idSuppleant"] .", '";
    $sSQL .= $aVars["Name"]."', ".$aVars["nbPeople"].", '";
    $sSQL .= convMonthDay($aVars["Start"])."', '".convMonthDay($aVars["End"])."', ";
    if( $aVars["locked"] ){
      $sSQL .= "1, '";
    } else {
      $sSQL .= "0, '";
    }
    $sSQL .= $aVars["curSeason"]."','".$aVars["price"]."');";
  } else {
    $sSQL  = "UPDATE sys_contrat SET ";
    $sSQL .= "idContratType=".$aVars["Type"].", ";
    $sSQL .= "idProducteur=".$aVars["idProducteur"].", ";
    $sSQL .= "idReferent=".$aVars["idReferent"].", ";
    $sSQL .= "idSuppleant=".$aVars["idSuppleant"].", ";
    $sSQL .= "label='".$aVars["Name"]."', ";
    $sSQL .= "NbPermanence=".$aVars["nbPeople"].", ";
    $sSQL .= "DebutContrat='".convMonthDay($aVars["Start"])."', ";
    $sSQL .= "FinContrat='".convMonthDay($aVars["End"])."', ";
    if( $aVars["locked"] ){
      $sSQL .= "Verouille=1, ";
    } else {
      $sSQL .= "Verouille=0, ";
    }
    $sSQL .= "Encours='".$aVars["curSeason"]."', ";
    $sSQL .= "PrixContrat='".$aVars["price"]."' ";
    $sSQL .= "WHERE id=".$aVars["id"].";";
  }

  $aRet = $db->Query($sSQL);

  $aRet = ["Errno" => 0, "ErrMsg" => $sSQL];
  return $aRet;
}

function convMonthDay($sDate)
{
  $sRetDate = "2000-".substr($sDate,3,2)."-".substr($sDate,0,2);
  return $sRetDate;
}

function getInfo($id)
{
  require_once($_SERVER["DOCUMENT_ROOT"]."/config/config.php");
  require_once($_SERVER["DOCUMENT_ROOT"]."/class/autoload.php");
  $db = new cMariaDb($Cfg);

  $sSQL  = "SELECT sys_contrat.id, "; 
  $sSQL .= "     sys_contrat.idContratType,";
  $sSQL .= "       sys_parameter.value AS Type, ";
  $sSQL .= "       sys_contrat.IdProducteur,";
  $sSQL .= "       CONCAT(prod.sNom, ' ', prod.sPrenom) AS Producteur, ";
  $sSQL .= "       sys_contrat.IdReferent,";
  $sSQL .= "       sys_contrat.IdSuppleant,";
  $sSQL .= "       label AS Name, ";
  $sSQL .= "       sys_parameter.value AS Type, ";
  $sSQL .= "       CONCAT(Ref.sNom,' ', Ref.sPrenom) AS Referent, ";
  $sSQL .= "       CONCAT(sup.sNom,' ', sup.sPrenom) AS Suppleant, ";
  $sSQL .= "       Verouille, nbPermanence, DebutContrat, FinContrat, EnCours, PrixContrat ";
  $sSQL .= "  FROM sys_contrat ";
  $sSQL .= "  LEFT JOIN sys_user AS prod ON sys_contrat.IdProducteur = prod.id ";
  $sSQL .= "  LEFT JOIN sys_user AS Ref ON sys_contrat.idReferent = Ref.id ";
  $sSQL .= "  LEFT JOIN sys_user AS sup ON sys_contrat.idSuppleant = sup.id ";
  $sSQL .= "  LEFT JOIN sys_parameter ON sys_contrat.idContratType = sys_parameter.id";
  $sSQL .= "  WHERE sys_contrat.id=".$id;
  return ["Errno" => 0, "Data" => $db->getAllFetch($sSQL)];
}