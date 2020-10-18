<?php
/**
 * Fonctionnalités liées au finder
 * 
 */

/**
 * Aiguillage des demandes 
 * 
 */ 
function Finder($sAction, $sVars)
{
  switch($sAction)
  {
    case 'getFiles':
      $aRet = getFiles($sVars);
      break;

    case 'delFile':
      $aRet=["Errno" => 0, "ErrMsg" => "OK"];
      unlink($_SERVER["DOCUMENT_ROOT"].$sVars);
      break;

    case 'addFolder':
      $aRet = addFolder($sVars);
      break;  

    case 'delFolder':
      $aRet = delFolder($sVars);
      break;

    case 'getArticles':
      $aRet = getArticles();
      break;  

    default:
      $aRet=["Errno"=> -1, "ErrMsg" => "Action $sAction indéfinie dans finder.php"];
      break;
  }

  header('content-type:application/json');
  echo json_encode($aRet); 
}

function getFiles($sVars)
{
  include_once($_SERVER["DOCUMENT_ROOT"]."/config/config.php");
  $aFiles=[];
  $aFolders=[];

  $aVars = json_decode($sVars,true);
  if( $aVars['Type'] == 'File' ){
    $sDir = $_SERVER["DOCUMENT_ROOT"].$Cfg["Documents"].$aVars['Path'];
    $sFolder = $Cfg["Documents"].$aVars['Path'];
  } else {
    $sDir = $_SERVER["DOCUMENT_ROOT"].$Cfg["Images"].$aVars['Path'];
    $sFolder = $Cfg["Images"].$aVars['Path'];
  }
 
  $handle = opendir($sDir);
  if( $handle == false ){
    $Msg  = "ERREUR: Finder.php readdir($sDir)\n";
    $Msg .= print_r($aVars,true);
    $Msg .= "\n$sVars";
    $aRet = ["Errno" => -1, "ErrMsg" => $Msg];
    return $aRet;
  }
  if( substr($sDir,-1) != "/" ){
    $sDir .= "/";
  }
  if( substr($sFolder,-1) != "/" ){
    $sFolder .= "/";
  }

  while (($file = readdir($handle)) !== false) {
    if( is_file($sDir . $file) ){
      $aFiles[] = $sFolder.$file;
    } else {
      if( !($file == "." || $file == "..") ){
        $aFolders[] = $file;
      }
    }
  }
  closedir($handle);
  //$Msg  = print_r($aVars,true);
  $Msg = "$sVars";

  $aRet = [
    "Errno"   => 0, 
    "ErrMsg"  => "OK", 
    "sDir"    => $sDir,
    "Files"   => $aFiles,
    "Folders" => $aFolders, 
    "Msg"     => $Msg,
    "Folder"  => $sFolder,
    "aVars"   => $aVars];
  return $aRet;
}

/**
 * addFolder
 */
function addFolder($sVars)
{
  include_once($_SERVER["DOCUMENT_ROOT"]."/config/config.php");
  $aVars = json_decode($sVars,true);

  if( $aVars['Type'] != 'img' ){
    $sDir = $_SERVER["DOCUMENT_ROOT"].$Cfg["Documents"].$aVars['Path'];
  } else {
    $sDir = $_SERVER["DOCUMENT_ROOT"].$Cfg["Images"].$aVars['Path'];
  }
  $sDir .= "/".$aVars["Folder"];
  mkdir($sDir);

  $aRet = ['Errno' => 0, "ErrMsg" => "OK"];
  return $aRet;
}

/**
 * delFolder
 * 
 */
function delFolder($sVars)
{
  include_once($_SERVER["DOCUMENT_ROOT"]."/config/config.php");
  $aVars = json_decode($sVars,true);

  if( $aVars['Type'] != 'img' ){
    $sDir = $_SERVER["DOCUMENT_ROOT"].$Cfg["Documents"].$aVars['Path'];
  } else {
    $sDir = $_SERVER["DOCUMENT_ROOT"].$Cfg["Images"].$aVars['Path'];
  }
  
  delete_directory($sDir);
  $aRet = ["Errno" => 0, "ErrMsg" => "OK"];
  return $aRet;
}

/**
 * @param string $dir /path/for/the/directory/
 * @return bool
 **/
function delete_directory( $dir )
{
    if( is_dir( $dir ) )
    {
      $files = glob( $dir . '*', GLOB_MARK ); //GLOB_MARK adds a slash to directories returned
 
      foreach( $files as $file )
      {
          delete_directory( $file );      
      }
 
      rmdir( $dir );
    } 
    elseif( is_file( $dir ) ) 
    {
      unlink( $dir );  
    }
}

/**
 * getArticles
 * 
 * Liste les titres des articles dans la base de données
 * 
 */
function getArticles()
{
  include_once($_SERVER["DOCUMENT_ROOT"]."/config/config.php");
  include_once($_SERVER["DOCUMENT_ROOT"]."/class/autoload.php");

  $db = new cMariaDb($Cfg);
  $sSQL = "SELECT id, sTitre FROM sys_articles;";

  $aRet = ["Errno" => 0, "ErrMsg" => "OK", "Articles" => $db->getAllFetch($sSQL)];
  return $aRet;
}