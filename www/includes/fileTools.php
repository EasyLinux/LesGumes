<?php  
function planningFilename() {
	// recherche du fichier pdf du planning : on ne fixe pas un nom de fichier pour �viter les probl�me de mise � jour :
	//  (ceratins navigateurs garde le fichier en cache) donc on change le nom du fichier � chaque maj et on
	// prend le  fichier pdf du repertoire le plus r�cent ...
	$planningFilename = '';
	$filelist = getFilesFrom( 'documentation/livraisons/', '.pdf');
	if (array_count_values($filelist) >=1)
		$planningFilename ='documentation/livraisons/'.$filelist[0];
	return $planningFilename;
}

function getFilesFrom( $directoryName, $extention) {
	// liste les fichiers de directory et les range par ordre alphabetique
	// ext : pour ne conserver que les fichiers d'une extension donn�e
	$filelist = array();
	if ($dir = @opendir($directoryName)) {
		while (($file = readdir($dir)) !== false)  {
			if($file != ".." && $file != ".") {
				if ( ($extention == NULL) || ( stristr($file, $extention) != FALSE)) {
					$filelist[] = $file;
				}
			}
		} 
		closedir($dir);
	}
	arsort ($filelist, SORT_STRING | SORT_FLAG_CASE );
	$filelist = array_values($filelist);
	return $filelist;
}

?>
