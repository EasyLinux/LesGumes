<?php include_once("define.php"); 

if (isset($_REQUEST['amap']))
	$amap = $_REQUEST['amap'];
else
	$amap = "";

if (isset($_POST['valeurAEncoder']))
	$valeurAEncoder = $_POST['valeurAEncoder'];
else
	$valeurAEncoder = "";

if (isset($_POST['idAEncoder']))
	$idAEncoder = $_POST['idAEncoder'];
else
	$idAEncoder = "";

if (isset($_POST['idAReinitialiser']))
	$idAReinitialiser = $_POST['idAReinitialiser'];
else
	$idAReinitialiser = "";

if (isset($_POST['mailPourReinitialisation']))
	$mailPourReinitialisation = $_POST['mailPourReinitialisation'];
else
	$mailPourReinitialisation = "";

$mail = "";
$nom = "";
$message ="";

if ($idAEncoder!="") {
	mysql_connect(hote, login, mot_passe_sql); // Connexion à MySQL
	mysql_select_db(base_de_donnees); // Sélection de la base 
	mysql_error(); // lecture pour vider d'éventuels messages antérieurs
	$question='SELECT id, Nom, Prenom, e_mail from amap_generale WHERE id='.$idAEncoder;
	$reponse = mysql_query( $question ) or die(mysql_error());
	$reponse=mysql_fetch_array($reponse);
	if ( $reponse != NULL) {
		$mail = $reponse[3];
		$nom = $reponse[1]." ".$reponse[2];
	} else {
		$nom = "pas d'amapien avec cet id";
	}
	mysql_close();	
}

if ($idAReinitialiser!="") {
	mysql_connect(hote, login, mot_passe_sql); // Connexion à MySQL
	mysql_select_db(base_de_donnees); // Sélection de la base 
	mysql_error(); // lecture pour vider d'éventuels messages antérieurs
	// UPDATE `amap_generale` SET `Login` = 'sodimoreau@free.fr', `Mot_passe` = 'sodimoreau@free.fr' WHERE `amap_generale`.`id` = 174;
	$question='UPDATE amap_generale SET Login = \''.$mailPourReinitialisation.'\', Mot_passe = \''.sha1($mailPourReinitialisation).'\' WHERE amap_generale.id = '.$idAReinitialiser;
	$reponse = mysql_query( $question ) or die(mysql_error());
	if ( $reponse == 1) {
		$message = "Réinitialisation effectuée";
	} else {
		$message = "Pb pendant la réinitialisation";
	}	
	mysql_close();	
}

?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="fr" >
<!-- xmlns indique une adresse traitant du xHTML -->
<!-- xml:lang : sert à indiquer dans quelle langue est rédigée votre page -->
	<head>
		<title>		AMAP Saint-Sébastien/Loire</title>
		<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
		<!-- meta indique que l'on utilise des caractères spécifiques au français éèêà... -->
		<link rel="stylesheet" media="screen" type="text/css" title="css_style" href="../espace_producteurs/style_producteurs.css" />
	</head>
	<body>
		<div id="page_principale">
		<p> <strong>&nbsp;&nbsp;Navigation &nbsp;&nbsp;&nbsp;:&nbsp;</strong>
			<input type="button" value="Retour à l'accueil" onclick="document.location.href='../index.php'"/>
			<input type="button" value="Menu Webmaster" onclick="document.location.href='webmaster.php?mode=<?php echo $retourAMAP; ?>'"/>
		</p>
		<p>			
		<table>
			<form method="post" action="webmaster_gestion_login.php">	
				<caption>Réinitialiser le mot de passe d'un amapien : </caption>				
				<input hidden type="text" name="amap" value="<?php echo $amap;?>" />  
				<tr><th><label for="id">Id de l'amapien :  </label></th>
					<td><input type="number" name="idAEncoder" value="<?php echo $idAEncoder;?>" />  
						<input type="submit" value="Rechercher" /></td></tr>	
				<tr><th><label for="nom">&nbsp;Nom et prénom de cet amapien : </label></th>
					<td><input disabled style="text-align:left" type="text" value="<?php echo $nom;?>"  /> </td></tr>
				<tr><th><label for="nom">&nbsp;Le mot de passe sera mis à jour avec : </label></th>
				<td><input disabled style="text-align:left" type="text" name="mail" value="<?php echo $mail;?>" /> </td></tr>
			</form>
			<tr><td colspan="2" align="center">
			<form method="post" action="webmaster_gestion_login.php">
				<input hidden type="text" name="amap" value="<?php echo $amap;?>" />  
				<input hidden type="number" name="idAReinitialiser" value="<?php echo $idAEncoder;?>" />  
				<input hidden type="text" name="mailPourReinitialisation" value="<?php echo $mail;?>" />  
				<input type="submit" value="Réinitialiser avec son adresse mail" <?php if ( $mail == "") echo "disabled";?>/> <?php echo $message; ?>
			</form>
			</td></tr>
		</table>			
	</p>
	<p>	&nbsp; 	</p>
	<p>
	<table>
		<form method="post" action="webmaster_gestion_login.php">
			<caption> Test d'encodage :  </caption>	
			<input hidden type="text" name="amap" value="<?php echo $_GET['amap'];?>" />  
			<tr><th><label for="id">Texte à encoder :  </label></th>
				<td><input style="text-align:left" type="text" name="valeurAEncoder" value="<?php echo $valeurAEncoder;?>" />
				<input type="submit" value="Calculer" /></td></tr>
			<tr><th><label for="id">Résultat de l'encodage :  </label></th>
				<td><?php if ($valeurAEncoder!= "") echo sha1($valeurAEncoder) ?></td></tr>
		</form>
	</table>
		
	</p>

	</div>
	</body>
</html>
