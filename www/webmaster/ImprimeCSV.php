<?php
include_once("define.php");


mysql_connect(hote, login, mot_passe_sql); // Connexion à MySQL
mysql_select_db(base_de_donnees); // Sélection de la base 

$tableLegume = $_GET['amap'];
$question="SELECT ".$tableLegume.".*, amap_generale.e_mail, amap_generale.Telephone, amap_generale.Tel_portable FROM ".$tableLegume.", amap_generale WHERE ".$tableLegume.".id= amap_generale.id ORDER BY Nom";
	
$reponse = mysql_query($question) or die(mysql_error());
$ligne = mysql_num_rows($reponse);
if( $ligne ==0) exit;

$questionBinome="SELECT binome.*, amap_generale.Prenom, amap_generale.e_mail, amap_generale.Telephone, amap_generale.Tel_portable FROM binome, amap_generale WHERE binome.id_binome= amap_generale.id AND binome.type_amap='".$tableLegume."'";
$reponseBinome = mysql_query($questionBinome) or die(mysql_error());
mysql_close();


//initialisation du fichier et des constantes
$sep = ';';


// ecrire les entêtes de colonne
$buffer="Nom;Prenom;Portable;Telephone;mail;Nom_binome \n";

// ecrire 1es données lignes par lignes
while($donnees = mysql_fetch_array($reponse)) {
		$buffer .=$donnees['Nom'];
		$buffer .=  $sep;
		$buffer .=  $donnees['Prenom'];
		$buffer .=  $sep;
		$buffer .= $donnees['Tel_portable'];
		$buffer .=  $sep;
		$buffer .=  $donnees['Telephone'];
		$buffer .=  $sep;
		$buffer .= $donnees['e_mail'];
		$buffer .=  $sep;
		$buffer .=  $sep;
		$buffer .=  "</ br>";
}

// ecrire les binômes
while($donnees = mysql_fetch_array($reponseBinome)) {	
		$buffer .=  $donnees['nom_binome'];
		$buffer .=  $sep;
		$buffer .=  $donnees['Prenom'];
		$buffer .=  $sep;
		$buffer .=  $donnees['Tel_portable'];
		$buffer .=  $sep;
		$buffer .=  $donnees['Telephone'];
		$buffer .=  $sep;	
		$buffer .=  $donnees['e_mail'];
		$buffer .=  $sep;
		$buffer .=  $donnees['nom_contrat'];	// le nom du binome qui a le contrat		
		$buffer .=  "</ br>";
			
}	


	$filename='Liste_'.$_GET['amap'].'-du-'.date("d-M-Y").'.csv';
/*	if (is_writable($filename)) {
		 if (!$handle = fopen($filename, 'wt')) {
			echo "Impossible d'ouvrir le fichier ($filename)";
			exit;
		 }
		 else {		 
			  if (fwrite($handle, $buffer, strlen($buffer )) === FALSE) {
					echo "Impossible d'écrire dans le fichier ($filename)";
					exit;
				}
		 }
		 fclose($handle);
    }
	else { echo "fichier $filename pas écrivable ";}*/
	echo $buffer;
	header('Content-Type: application/x-download');
	if(headers_sent())
		die( 'Some data has already been output, can\'t send PDF file');
	header('Content-Length: '.strlen($buffer));
	header('Content-Disposition: attachment; filename="'.$filename.'"');
	header('Cache-Control: private, max-age=0, must-revalidate');
	header('Pragma: public');
	ini_set('zlib.output_compression','0');
	echo $buffer;
	
	
	


?>




