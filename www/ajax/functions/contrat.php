<?php

function contrat($sAction, $sVars)
{
  switch($sAction)
  {
    case 'getProducteur':
    case 'getReferent':
    case 'getSuppleant':
    case 'getWait':
      require_once($_SERVER["DOCUMENT_ROOT"]."/config/config.php");
      require_once($_SERVER["DOCUMENT_ROOT"]."/class/autoload.php");
  
      $db = new cMariaDb($Cfg);
    
      $sSQL  = "SELECT sys_user.id, CONCAT(sNom,' ',sPrenom) AS Raisoc FROM sys_user WHERE bActive=1 ORDER BY Raisoc;";
      $aRet = $db->getAllFetch($sSQL);
      break;

    case 'save':
      $aRet = saveContrat($sVars);
      break;

    case 'getInfo':
      $aRet = getInfo($sVars);
      break;

    case 'getDocs':
      require_once($_SERVER["DOCUMENT_ROOT"]."/config/config.php");
      $DocFolder = $_SERVER["DOCUMENT_ROOT"].$Cfg["Documents"]."/Contrats/".$sVars;
      $DocTarget = $Cfg["Documents"]."/Contrats/".$sVars;
      $aRet = ["Errno" => -1, "ErrMsg" => $DocFolder];
      $aFiles=[];
      if (is_dir($DocFolder)) {
        if ($dh = opendir($DocFolder)) {
            while (($file = readdir($dh)) !== false) {
              if( filetype($DocFolder."/".$file)  == "file" ){
                $aFiles[]=$file;
              }
            }
            closedir($dh);
        }
        $aRet = ['Errno' => 0, "Files" => $aFiles, "Folder" => $DocTarget];
      } else {
        mkdir($DocFolder,0775,true);
        $aRet = ['Errno' => 0, "Files" => [], "Folder" => $DocTarget];
      }
      break;    

    case 'uploadDoc':
    require_once($_SERVER["DOCUMENT_ROOT"]."/config/config.php");
    $aRet = ['Errno' => -1, "ErrMsg" => print_r($sVars,true)];
      $DocFolder = $_SERVER["DOCUMENT_ROOT"].$Cfg["Documents"]."/Contrats/".$sVars;
      $sTargetFile = $DocFolder."/".basename($_FILES['file']['name']	);
      if( move_uploaded_file($_FILES['file']['tmp_name'],$sTargetFile) ){
        $aRet = ['Errno' => 0, "ErrMsg" => $sTargetFile];
      } else {
        $aRet = ['Errno' => -1, "ErrMsg" => $sTargetFile];
      }
      break;

    case 'listLivraison':
      $aRet = listLivraison($sVars);
      break;

    case 'saveLivraison':
      $aRet = saveLivraison($sVars);
      break;

    case 'delLivraison':
      $aRet = delLivraison($sVars);
      break;

    case 'addWaitUser':
      $aRet = addWaitUser($sVars);
      break;

    case 'listWait':
      $aRet = listWait($sVars);
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
    $sSQL .= $aVars["idProducteur"] .", ".$aVars["idReferent"].", ";
    $sSQL .= $aVars["idSuppleant"] .", '";
    $sSQL .= $aVars["Name"]."', ".$aVars["nbPeople"].", '";
    $sSQL .= convMonthDay($aVars["Start"])."', '".convMonthDay($aVars["End"])."', ";
    if( $aVars["locked"] ){
      $sSQL .= "1, '";
    } else {
      $sSQL .= "0, '";
    }
    $sSQL .= $aVars["curSeason"]."','".$aVars["price"]."','".$aVars["Document"]."','No rules');";
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
    $sSQL .= "PrixContrat='".$aVars["price"]."', ";
    $sSQL .= "Document='".$aVars["Document"]."' ";
    $sSQL .= "WHERE id=".$aVars["id"].";";
  }

  $aRet = $db->Query($sSQL);

  $aRet = ["Errno" => -1, "ErrMsg" => $sSQL];
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
  $sSQL .= "       Verouille, nbPermanence, DebutContrat, FinContrat, EnCours, PrixContrat, Document ";
  $sSQL .= "  FROM sys_contrat ";
  $sSQL .= "  LEFT JOIN sys_user AS prod ON sys_contrat.IdProducteur = prod.id ";
  $sSQL .= "  LEFT JOIN sys_user AS Ref ON sys_contrat.idReferent = Ref.id ";
  $sSQL .= "  LEFT JOIN sys_user AS sup ON sys_contrat.idSuppleant = sup.id ";
  $sSQL .= "  LEFT JOIN sys_parameter ON sys_contrat.idContratType = sys_parameter.id";
  $sSQL .= "  WHERE sys_contrat.id=".$id;
  return ["Errno" => 0, "Data" => $db->getAllFetch($sSQL)];
}

function listLivraison($id)
{
  require_once($_SERVER["DOCUMENT_ROOT"]."/config/config.php");
  require_once($_SERVER["DOCUMENT_ROOT"]."/class/autoload.php");
  $db = new cMariaDb($Cfg);

  $sSQL  = "SELECT id, DATE_FORMAT(Date, '%d/%m/%Y') AS frDate , numLivraison, Montant ";
  $sSQL .= "FROM sys_livraison WHERE idContrat=$id ORDER BY Date DESC;";
  return ["Errno" => 0, "Datas" => $db->getAllFetch($sSQL), "SQL" => $sSQL];
}

function saveLivraison($sVars)
{
  require_once($_SERVER["DOCUMENT_ROOT"]."/config/config.php");
  require_once($_SERVER["DOCUMENT_ROOT"]."/class/autoload.php");
  $db = new cMariaDb($Cfg);

  // {"id":"0","idContrat":"4","frDate":"08/05/2020","numLivraison":"8","Montant":"45"}"
  $aVars = json_decode($sVars,true);
  if( $aVars['id'] == 0){
    $sSQL  = "INSERT INTO sys_livraison VALUES (0, ".$aVars['idContrat'].",";
    $sSQL .= "'".frToUsDate($aVars['frDate'])."','".$aVars['numLivraison']."','";
    $sSQL .= $aVars['Montant']."');";
    $db->Query($sSQL);
    $id = $db->getLastId();
  } else {
    $sSQL  = "UPDATE sys_livraison SET idContrat=".$aVars['idContrat'];
    $sSQL .= ", Date='" .frToUsDate($aVars['frDate'])."', numLivraison=";
    $sSQL .= $aVars['numLivraison'] . ", Montant='". $aVars['Montant'] ."' ";
    $sSQL .= "WHERE id=".$aVars["id"];
    $db->Query($sSQL);
  }

  return ["Errno" => 0, "ErrMsg" => $sSQL, "id" => $id];
}

function delLivraison($id)
{
  require_once($_SERVER["DOCUMENT_ROOT"]."/config/config.php");
  require_once($_SERVER["DOCUMENT_ROOT"]."/class/autoload.php");
  $db = new cMariaDb($Cfg);
   
  $sSQL = "DELETE FROM sys_livraison WHERE id=$id;";
  $db->Query($sSQL);
  $sSQL = "DELETE FROM sys_livraison_produit WHERE idLivraison=$id";
  $db->Query($sSQL);
  return ["Errno" => 0, "ErrMsg" => ""];
}

function addWaitUser($sVars)
{
  require_once($_SERVER["DOCUMENT_ROOT"]."/config/config.php");
  require_once($_SERVER["DOCUMENT_ROOT"]."/class/autoload.php");
  $db = new cMariaDb($Cfg);
  $aVars = json_decode($sVars,true);

  $sSQL  = "INSERT INTO sys_liste_attente VALUES (0,";
  $sSQL .= $aVars["id"].", ".$aVars["idContrat"].",0,CURRENT_TIMESTAMP)";
  $db->Query($sSQL);
  return ["Errno" => 0, "ErrMsg" => $sSQL];
}

function listWait($id)
{
  require_once($_SERVER["DOCUMENT_ROOT"]."/config/config.php");
  require_once($_SERVER["DOCUMENT_ROOT"]."/class/autoload.php");
  $db = new cMariaDb($Cfg);

  $sSQL  = "SELECT DATE_FORMAT(dateInscription,'%d/%m/%Y') AS frDate, ";
  $sSQL .= "CONCAT(sNom,' ',sPrenom) AS Raisoc, sTelMobile, hasContract, sys_liste_attente.id ";
  $sSQL .= "FROM sys_liste_attente ";
  $sSQL .= "LEFT JOIN sys_user ON  sys_liste_attente.idUser = sys_user.id ";
  $sSQL .= "WHERE sys_liste_attente.idContrat=$id ";
  $sSQL .= "ORDER BY hasContract DESC;";
  $db->Query($sSQL);
  return ["Errno" => 0, "ErrMsg" => $sSQL, "Wait" => $db->getAllFetch($sSQL)];
}

function frToUsDate($frDate)
{
  $usDate = substr($frDate,6)."-".substr($frDate,3,2)."-".substr($frDate,0,2);
  return $usDate;
}