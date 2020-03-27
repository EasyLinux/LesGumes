<?php include_once("define.php"); 
if (isset($_POST['separateur']))
	$separateur = $_POST['separateur']." ";
else
	$separateur = ", ";
	
	
$retourAMAP = $_GET['amap'];
if ( $retourAMAP == "amap_legumes_liste_attente")
	$retourAMAP = "amap_legumes";

?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="fr" >
<!-- xmlns indique une adresse traitant du xHTML -->
<!-- xml:lang : sert à indiquer dans quelle langue est rédigée votre page -->
	<head>
		<title>AMAP Saint-Sébastien/Loire</title>
		<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
		<!-- meta indique que l'on utilise des caractères spécifiques au français éèêà... -->
		<link rel="stylesheet" media="screen" type="text/css" title="css_style" href="styleW.css" />
		<link rel="icon" type="image/jpeg" href="images/favicone-2.jpeg" />
	</head>
	<body>
	
<?php
function adresses($amap, $sep) {
	mysql_connect(hote, login, mot_passe_sql); // Connexion à MySQL
	mysql_select_db(base_de_donnees);
	$question="SELECT * FROM amap_generale WHERE id IN (SELECT id FROM ".$amap.")";
	$reponse=mysql_query($question);
	$nbre_eng=mysql_num_rows($reponse);
	//echo $nbre_eng." personnes<br />";
	$adresse='';
	$i=0;
	if($nbre_eng!=0) {
		while($donnees=mysql_fetch_array($reponse)) {
			$i++;
			if($i==1) $adresse=$donnees['e_mail'];
			else $adresse=$adresse.$sep.$donnees['e_mail'];
		}
	}
	
   	//ajouter les e-mail des binômes de cette amap
   	$question="SELECT e_mail FROM amap_generale WHERE id IN (SELECT id_binome FROM binome, ".$amap.
              "  WHERE binome.id_contrat=".$amap.".id  And binome.type_amap='".$amap."')";
  	$reponse=mysql_query($question);
  	$nbre_eng=mysql_num_rows($reponse);
   	if($nbre_eng!=0) {
  		while($donnees=mysql_fetch_array($reponse)) {
  		  $adresse=$adresse.$sep.$donnees['e_mail'];
  		}
  	}
	// il peut rester des ; insérés directement dans le champ du mail ....
	$adresse=str_replace ( ";" , $sep , $adresse);
	mysql_close();
	return $adresse;
}

function MailTo($adresses, $objet) { 
	$envoi="mailto:".$adresse;
	if($objet!='') 
		$envoi=$envoi."?subject=".$objet;
	return $envoi;
}

?>	
	<h2> Ecrire aux adhérents de <?php  echo $_GET['amap']; ?></h2>
    <?php $adresses = adresses( $_GET['amap'], $separateur); ?>
 	<ul>
		<li><a href="<?php echo MailTo($adresses, "") ?>">Lancer le client mail pour envoyer un mail aux adhérents</a>
		<?php if( isset($_GET['modewebmaster'])) { ?>
		<li><a href="webmaster.php?mode=<?php echo $retourAMAP ?>">Retour au menu Webmaster</a>
		<?php } ?>
		<li><a href="../index.php">Retour à l'Accueil</a>	
	</ul>	
	<p width="95%"> Adresses mail des adhérents : <br/ >	
	<?php echo $adresses; ?>
	
	<form method="post" action="webmaster_mail_to.php?amap=<?php echo $_GET['amap']; ?>">
	<p />
	<p> Changer le séparateur entre les adresses mails : 
	<input style="text-align:center" type="text" name="separateur" value="<?php echo $separateur;?>" />
	<input type="submit" value="Modifier" />
	</p>
	</form>
	</body>
</html>
