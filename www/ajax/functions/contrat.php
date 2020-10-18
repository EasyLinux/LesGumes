<?php

function contrat($sAction, $sVars)
{
  switch($sAction)
  {
    case 'listeContrat':
      $aRet = listeContrat();
      break;

    case 'getProducteur':
    case 'getReferent':
    case 'getSuppleant':
    case 'getWait':
    case 'getUser':
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

    case 'delWaitUser':
      $aRet = delWaitUser($sVars);
      break;

    case 'listWait':
      $aRet = listWait($sVars);
      break;

    case 'addUser':
      $aRet = addUser($sVars);
      break;

    case 'refreshUserList':
      $aRet = refreshUserList($sVars);
      break;

    case 'saveProduct':
      $aRet = saveProduct($sVars);
      break; 

    case 'listProduct':
      $aRet = listProduct($sVars);
      break; 

    case 'loadProduct':
      $aRet = loadProduct($sVars);
      break;

    case 'delProduct':
      $aRet = delProduct($sVars);
      break;

    default:
      $aRet = ["Errno" => -1, "ErrMsg" => "Action: $sAction indéfinie dans contrat.php"];
  }
  header('content-type:application/json');
  echo json_encode($aRet); 
}

/**
 * listeContrat
 * 
 * Renvoi la liste des contrats
 * @param   void    -
 * @return  array   Data contient le résultat du SELECT
 */
function listeContrat()
{
  require_once($_SERVER["DOCUMENT_ROOT"]."/config/config.php");
  require_once($_SERVER["DOCUMENT_ROOT"]."/class/autoload.php");
  $db = new cMariaDb($Cfg);

  $sSQL  = "SELECT sys_contrat.id, label, sys_parameter.value AS Type";
  $sSQL .= ", CONCAT(usr.sNom,' ',usr.sPrenom) AS Producteur";
  $sSQL .= ", CONCAT(ref.sNom,' ',ref.sPrenom) AS Referent";
  $sSQL .= ", IF(Verouille=1, 'OUI', 'NON') AS Verouille ";
  $sSQL .= "FROM sys_contrat ";
  $sSQL .= "LEFT JOIN sys_parameter ON sys_contrat.idContratType = sys_parameter.id ";
  $sSQL .= "LEFT JOIN sys_user AS usr ON sys_contrat.idProducteur = usr.id ";
  $sSQL .= "LEFT JOIN sys_user AS ref ON sys_contrat.idReferent = ref.id ";
  $sSQL .= "ORDER BY sys_contrat.label;";
  return ["Errno" => 0, "Data" => $db->getAllFetch($sSQL)];
}

/** 
 * saveContrat
 * 
 * Enregistre les données d'un contrat 
 * @param  string  $sVars   représentation json d'un contrat
 */
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
    $sSQL .= $aVars["Start"]."', '".$aVars["End"]."', ";
    if( $aVars["locked"] == "true" ){
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
    $sSQL .= "DebutContrat='".date_format(date_create_from_format('j/m/Y', $aVars["Start"]), 'Y-m-d')."', ";
    $sSQL .= "FinContrat='".date_format(date_create_from_format('j/m/Y', $aVars["End"]), 'Y-m-d')."', ";
    if( $aVars["locked"] == "true" ){
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

  $aRet = ["Errno" => 0, "ErrMsg" => "OK"];
  return $aRet;
}

// function convMonthDay($sDate)
// {
//   $sRetDate = "2000-".substr($sDate,3,2)."-".substr($sDate,0,2);
//   return $sRetDate;
// }

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


function listWait($id)
{
  // TODO rafraichir hasContract 
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

function addWaitUser($sVars)
{
  // TODO rafraichir hasContract 
  require_once($_SERVER["DOCUMENT_ROOT"]."/config/config.php");
  require_once($_SERVER["DOCUMENT_ROOT"]."/class/autoload.php");
  $db = new cMariaDb($Cfg);
 
  $aVars = json_decode($sVars,true);
  
  $sSQL  = "INSERT INTO sys_liste_attente VALUES (0,".$aVars["id"];
  $sSQL .= ",".$aVars["idContrat"].",0,CURRENT_TIMESTAMP);";
  $db->Query($sSQL);
  return ["Errno" => 0, "ErrMsg" => $sSQL];
}

function delWaitUser($sVars)
{
  require_once($_SERVER["DOCUMENT_ROOT"]."/config/config.php");
  require_once($_SERVER["DOCUMENT_ROOT"]."/class/autoload.php");
  $db = new cMariaDb($Cfg);
 
  $aVars = json_decode($sVars,true);
  
  $sSQL  = "DELETE FROM sys_liste_attente WHERE id=".$aVars["id"];
  $db->Query($sSQL);
  return ["Errno" => 0, "ErrMsg" => print_r($aVars,true).$sSQL];
}

function addUser($sVars)
{
  require_once($_SERVER["DOCUMENT_ROOT"]."/config/config.php");
  require_once($_SERVER["DOCUMENT_ROOT"]."/class/autoload.php");
  $db = new cMariaDb($Cfg);

  $aVars = json_decode($sVars,true);

  // Lire le contrat référencé, calculer date debut et date fin
  $sSQL = "SELECT DebutContrat, FinContrat FROM sys_contrat WHERE id=".$aVars["idContrat"].";";
  $aContrat = $db->getAllFetch($sSQL); 
  $sDebut = substr($aContrat[0]["DebutContrat"],4);
  $sFin = substr($aContrat[0]["FinContrat"],4);
  if ( $sDebut > $sFin ){
    // Bascule l'année ex: fin aout et début en septembre
    $sNewDebut = date('Y').$sDebut;
    $sNewFin   = date('Y', strtotime('+1 year')) . $sFin;
  } else {
    $sNewDebut = date('Y').$sDebut;
    $sNewFin   = date('Y'). $sFin;
  }

  // Créer les lignes dans sys_contrat_user
  // $sSQL  = "INSERT IGNORE INTO sys_contrat_user VALUES (0,".$aVars['idContrat'];
  // $sSQL .= ",". $aVars['id'].",'$sNewDebut','$sNewFin',0);";
  // $db->Query($sSQL);
  return ["Errno" => -1,"ErrMsg" => print_r($aVars,true)];
}

function refreshUserList($id)
{
  require_once($_SERVER["DOCUMENT_ROOT"]."/config/config.php");
  require_once($_SERVER["DOCUMENT_ROOT"]."/class/autoload.php");
  $db = new cMariaDb($Cfg);

  $sSQL  = "SELECT DATE_FORMAT(dDateInscription,'%d/%m/%Y') as dateInscription";
  $sSQL .= ", sNom AS Nom, sPrenom AS Prenom,";
  $sSQL .= " sTelMobile AS Telephone FROM sys_contrat_user ";
  $sSQL .= "LEFT JOIN sys_user ON sys_contrat_user.idUser = sys_user.id ";
  $sSQL .= "WHERE sys_contrat_user.idContrat=$id ";
  $sSQL .= "ORDER BY Nom,Prenom;";
  return ["Errno" => 0,"Data" => $db->getAllFetch($sSQL), "ErrMsg" => $sSQL];
}

function frToUsDate($frDate)
{
  $usDate = substr($frDate,6)."-".substr($frDate,3,2)."-".substr($frDate,0,2);
  return $usDate;
}

/**
 * list des produits liés à un contrat
 * 
 * @param int $id   identifiant contrat
 */
function listProduct($id)
{
  require_once($_SERVER["DOCUMENT_ROOT"]."/config/config.php");
  require_once($_SERVER["DOCUMENT_ROOT"]."/class/autoload.php");
  $db = new cMariaDb($Cfg);

  $sSQL  = "SELECT sys_produit.id, Label, Unite, Prix ";
  $sSQL .= "FROM sys_produit LEFT JOIN sys_produit_prix ";
  $sSQL .= "ON sys_produit.id = sys_produit_prix.idProduit ";
  $sSQL .= "WHERE sys_produit_prix.actuel=1 ";
  $sSQL .= "AND sys_produit.idContrat=$id ";
  $sSQL .= "AND sys_produit.Actif=1 ORDER BY Label;";

  return ["Errno" => 0, "Data" => $db->getAllFetch($sSQL) ];
}

/**
 * Met à jour un produit
 * 
 * Ajout ou mise à jour d'un produit, lors d'un changement du prix,
 * on ajoute une ligne dans la table sys_produit-prix avec la date de modification
 * cela permettra de faire le suivi du prix.
 * @param  array $sVars  
 */
function saveProduct($sVars)
{
  require_once($_SERVER["DOCUMENT_ROOT"]."/config/config.php");
  require_once($_SERVER["DOCUMENT_ROOT"]."/class/autoload.php");
  $db = new cMariaDb($Cfg);

  $aVars = json_decode($sVars,true );

  if( $aVars["id"] == 0 ){
    $sSQL  = "INSERT INTO sys_produit VALUES (0";
    $sSQL .= ",". $aVars["idContrat"] .", '".$aVars["label"]."','"; 
    $sSQL .= $aVars["description"]."','";
    $sSQL .= $aVars["unite"]. "',".$aVars["max"].",1);";
    $db->Query($sSQL);
    $idProd = $db->getLastId();
    $sSQL  = "INSERT INTO sys_produit_prix VALUES (0,$idProd,'";
    $sSQL .= $aVars["prix"]."',CURRENT_TIMESTAMP,1);";
    $db->Query($sSQL);
  } else {
    $id = $aVars["id"];
    $sSQL  = "UPDATE sys_produit SET Label='".$aVars["label"]."', ";
    $sSQL .= "Description='".$aVars["description"]."', ";
    $sSQL .= "Unite='".$aVars["unite"]."', ";
    $sSQL .= "MaxLivraison=".$aVars["max"]." WHERE id=$id;";
    $db->Query($sSQL);
    error_log("UPDATE ".$sSQL);
    // Lire ancien prix
    $sSQL  = "SELECT id, Prix FROM sys_produit_prix WHERE idProduit=$id;";
    $Rep = $db->getAllFetch($sSQL);
    $oldPrix = $Rep[0]["Prix"];
    if( $aVars["prix"] != $oldPrix){
      // Ancien prix n'est plus d'actualité
      $sSQL = "UPDATE sys_produit_prix SET actuel=0 WHERE id=".$Rep[0]["id"].";";
      $db->Query($sSQL);
      // Ajout le nouveau prix
      $sSQL  = "INSERT INTO sys_produit_prix VALUES (0,$id,'";
      $sSQL .= $aVars["prix"]."',CURRENT_TIMESTAMP,1);";
      error-log("Nouveau prix: ".$sSQL);
      $db->Query($sSQL);
    }
  }
  return ["Errno" => 0, "ErrMsg" => "OK"];
}

/**
 * loadProduct
 * 
 * Retourne les données liées à un produit
 */
function loadProduct($id)
{
  require_once($_SERVER["DOCUMENT_ROOT"]."/config/config.php");
  require_once($_SERVER["DOCUMENT_ROOT"]."/class/autoload.php");
  $db = new cMariaDb($Cfg);

  $sSQL =  "SELECT *,Prix FROM sys_produit ";
  $sSQL .= "LEFT JOIN sys_produit_prix ";
  $sSQL .= "ON sys_produit.id = sys_produit_prix.idProduit ";
  $sSQL .= "WHERE sys_produit_prix.actuel = 1 ";
  $sSQL .= "AND sys_produit.id = $id;";
  return ["Errno" => 0, "Data" => $db->getAllFetch($sSQL)];
}

/**
 * delProduct
 * 
 * Supprime le produit (le désactive)
 */
function delProduct($id)
{
  require_once($_SERVER["DOCUMENT_ROOT"]."/config/config.php");
  require_once($_SERVER["DOCUMENT_ROOT"]."/class/autoload.php");
  $db = new cMariaDb($Cfg);

  $sSQL =  "UPDATE sys_produit SET Actif=0 WHERE id=$id";
  $db->Query($sSQL);
  return ["Errno" => 0, "ErrMsg" => "OK"];
}