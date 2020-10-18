<?php
/**
 * Fonctions liées à l'editeur
 */

function doEditor($sAction, $sFolder,$sFile)
{
  switch($sAction)
  {
    case 'getFiles':
      findFiles($sFolder);
      break;

    case 'newFolder':
      mkdir($_SERVER["DOCUMENT_ROOT"].$sFolder."/".$sFile);
      header('content-type:application/json');
      echo json_encode(["Errno" => 0,"ErrMsg" => "OK"]); 
      break;

    case 'delFile';
      deleteFolder($_SERVER["DOCUMENT_ROOT"].$sFolder."/".$sFile);
      header('content-type:application/json');
      echo json_encode(["Errno" => 0,"ErrMsg" => "OK"]); 
      break;

  }
}

/**
 * findFiles
 * 
 * liste les fichiers dans un répertoire. Cette fonction est appelée lorsqu'un
 * utilisateur désire insérer une image dans le texte qu'il rédige
 */
function findFiles($Folder)
{
  $aTmp = scandir($_SERVER["DOCUMENT_ROOT"].$_POST["Folder"]);
  foreach($aTmp as $sFile)
  {
    if( $sFile != "."){ // ne pas transmettre '.'
      $aRet[] = ["name" => $sFile,"folder" => $_POST["Folder"]];
    }
  }
  header('content-type:application/json');
  echo json_encode($aRet); 
}

function deleteFolder( $dir )
{
  if( is_dir( $dir ) ) 
  {
    $files = glob( $dir . '*', GLOB_MARK ); //GLOB_MARK adds a slash to directories returned

    foreach( $files as $file )
    {
      deleteFolder( $file );      
    }

    rmdir( $dir );
  } 
  elseif( is_file( $dir ) ) {
    unlink( $dir );  
  }
}