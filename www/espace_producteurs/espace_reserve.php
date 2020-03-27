<?php
include_once("../webmaster/define.php");
include_once("../espace_producteurs/mes_fonctions.php");

/* en arrivant du menu de l'index ordre='passe' et il faut demander le mot de passe */
/* ensuite ordre!='passe' et on ne demande plus le mot de passe */
$ok=1;
$tri = isset( $_GET['classement']) ? $_GET['classement'] : "Nom";
$sens = $_GET['sens']=="DESC" ? "DESC" : "ASC";
$ordre = $_GET['ordre']=='passe' ? "rien" : $_GET['ordre'];

if($_GET['ordre']=='passe') {
	if (isset($_POST['motpasse'])) 
		$mot_test=$_POST['motpasse'];
	else $mot_test='';
	$ok=0;
	if($mot_test=="pommes" && $_GET['amap']=='amap_pommes')$ok=1;
	if($mot_test=="potiron" && $_GET['amap']=='amap_legumes')$ok=1;
	if($mot_test=="viande"	&& $_GET['amap']=='amap_viande_bovine')$ok=1;
	if($mot_test=="cerises"	&& $_GET['amap']=='amap_cerises') $ok=1;
	if($mot_test=="pain"	&& $_GET['amap']=='amap_pain') $ok=1;
	if($mot_test=="rene"	&& $_GET['amap']=='amap_oeufs') $ok=1;
	if($mot_test=="rene"	&& $_GET['amap']=='amap_poulets') $ok=1;
	if($mot_test=="clementine"	&& $_GET['amap']=='amap_agrumes') $ok=1;
	if($mot_test=="champipi"	&& $_GET['amap']=='amap_champignons') $ok=1;
	if($mot_test=="potronminet"	&& $_GET['amap']=='amap_tisanes') $ok=1;
	if($mot_test=="biquette"	&& $_GET['amap']=='amap_chevre') $ok=1;
}
if($ok==1)
// Si le mot de passe est bon
{
//***********************************************************************
// On a le droit
//***********************************************************************
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="fr" >
<!-- xmlns indique une adresse traitant du xHTML -->
<!-- xml:lang : sert à indiquer dans quelle langue est rédigée votre page -->
	<head>
		<title>AMAP Saint-Sébastien/Loire</title>
		<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
		<!-- meta indique que l'on utilise des caractères spécifiques au français éèêà... -->
		<link rel="stylesheet" type="text/css" media="all" href="style_producteurs.css" />
		<link rel="icon" type="image/jpeg" href="images/favicone-2.jpeg" />
	</head>
	<body>
	<?php
    function MailTo($amap, $objet) {
    
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
    			else $adresse=$adresse."; ".$donnees['e_mail'];
    		}
    	}
    	
    	//ajouter les e-mail des binômes de cette amap
    	$question="SELECT e_mail FROM amap_generale WHERE id IN (SELECT id_binome FROM binome, ".$amap.
                "  WHERE binome.id_contrat=".$amap.".id  And binome.type_amap='".$amap."')";
    	$reponse=mysql_query($question);
    	$nbre_eng=mysql_num_rows($reponse);
    	if($nbre_eng!=0) {
    		while($donnees=mysql_fetch_array($reponse)) {
    		  $adresse=$adresse.";".$donnees['e_mail'];
    		}
    	}
	
    	$envoi="mailto:".$adresse;
    	if($objet!='') $envoi=$envoi."?subject=".$objet;
    	mysql_close();
    	return $envoi;
    }
  ?>
		<div id="page_principale">
			<p> <strong>&nbsp;&nbsp;Navigation &nbsp;&nbsp;&nbsp;:&nbsp;</strong>
				<input type="button" value="Retour à l'accueil" onclick="document.location.href='../index.php'"/>
				<input type="button" value="page précédente" onclick="javascript:history.back();"/>
			</p>
			<p> <strong>Autres actions :&nbsp;</strong>
				<input type="button" value="Ecrire aux adhérents" onclick="document.location.href='../webmaster/webmaster_mail_to.php?amap=<?php echo $_GET['amap']; ?>'"/>
				<input type="button" value="Générer pdf <?php echo $_GET['amap']; ?>" onclick="document.location.href='../webmaster/ImprimeAM.php?amap=<?php echo $_GET['amap']; ?>'"/>
				<!--input type="button" value="Générer csv <?php echo $_GET['amap']; ?>" onclick="document.location.href='../webmaster/ImprimeCSV.php?amap=<?php echo $_GET['amap']; ?>'"/-->
				<input type="button" value="Feuille Emargement <?php echo $_GET['amap']; ?>" onclick="document.location.href='../webmaster/ImprimeEmargement.php?amap=<?php echo $_GET['amap']; ?>'"/>
								
			</p>
			
			<?php
				AfficherTable(base_de_donnees, $_GET['amap'],$tri, $sens, 'espace_reserve.php');
			?>
		
			<h2>Mails de tous les adhérents au contrat   <?php echo $_GET['amap']?> : </h2>
				<font size=2><?php echo substr(MailTo($_GET['amap'], ""),7) ?> </font><br />
			
		</div>		
	</body>
</html>


<?php
} 
else // le mot de passe n'est pas bon
{
//***********************************************************************
// On affiche la zone de texte pour rentrer de nouveau le mot de passe.
//***********************************************************************
?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="fr" >
<!-- xmlns indique une adresse traitant du xHTML -->
<!-- xml:lang : sert à indiquer dans quelle langue est rédigée votre page -->
	<head>
		<title>AMAP Saint-Sébastien/Loire</title>
		<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
		<!-- meta indique que l'on utilise des caractères spécifiques au français éèêà... -->
		<link rel="stylesheet" type="text/css" media="all" href="style_producteurs.css" />
		<link rel="icon" type="image/jpeg" href="images/favicone-2.jpeg" />
	</head>

<script type="text/javascript">
<!--
function verifkey(boite) {
	if(boite.value=='') {
		alert('Entrer un mot de passe !');
		boite.focus();
		return false;
	}
	return true;
}
-->
</script>
	
	<body onload="document.getElementById('motpasse').focus();">
		<div id="page_principale">
			<form  onsubmit="return verifkey(this.motpasse);" method="post" action="espace_reserve.php?amap=<?php echo $_GET['amap']; ?>&amp;ordre=<?php echo $_GET['ordre']; ?>" >
				<p class="mot_passe_recette">
					<label for="motpasse">Mot de passe</label> : <input type="password" name="motpasse" id="motpasse" size="50" maxlength="45" tabindex="10"/>
					<input type="submit" tabindex="20"/> <input type="reset" tabindex="30"/>
				</p>
			</form>

		</div>		
	<p>
		<!--<img src="images/logo_lesgumes.jpeg" alt="Logo de l'AMAP" title="Groupement Uni pour un Meilleur Environnement Solidaire" /> -->
		<!-- alt indique un texte alternatif au cas où l'image ne peut pas être téléchargée -->
	</p>
	</body>
</html>
<?php } ?>