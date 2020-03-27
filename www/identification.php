<?php 
if (isset($_POST['motpasse']))
	$motpasse = $_POST['motpasse'];
else
	$motpasse = "";

$motpasse = sha1($motpasse) // Encodage mot de passe
?>
<?php
include("webmaster/define.php"); 
$ok=-1;/* pas de mot de passe : premier passage */
if (isset($_POST['motpasse'])) // second passage
{
	$ok=0;/* mot de passe incorrect */
	mysql_connect(hote, login, mot_passe_sql); // Connexion � MySQL
	mysql_select_db(base_de_donnees); // S�lection de la base 
	$question="SELECT * FROM amap_generale WHERE Mot_passe='".$motpasse."' AND Login='".$_POST['login']."'";
	$reponse = mysql_query($question) or die(mysql_error());
	$ligne = mysql_num_rows($reponse);
	if ($ligne == '1') 
	{
		$ok=1;/* mot de passe correct mais non encore modifi� */
		$donnees = mysql_fetch_array($reponse);
		if($donnees['Mot_passe']!=$donnees['e_mail'] || $donnees['Login']!=$donnees['e_mail']) {
			$ok=2; //mot de passe correct et chang�
			// cr�er le cookie retour page d'appel
			setcookie('identification_amap',$donnees['id']);
			mysql_close(); // D�connexion de MySQL
			header("Location: index.php");
		}
		else {
			setcookie('identification_amap',$donnees['id']);
			mysql_close(); // D�connexion de MySQL
		}
	}
	else mysql_close();
}
if(isset($_POST['motpasse_2'])) { // modification du mot de passe
	$ok=3; //les  mots de passe utilisent des caract�res interdits
	$motdp=$_POST['motpasse_2'];
	$login_2=$_POST['login_2'];
	if(preg_match('#^[a-zA-Z0-9.@_-]{4,40}$#',$motdp) && preg_match('#^[a-zA-Z0-9.@_-]{4,40}$#',$login_2)) {
		$ok=4; //mot correct mais confirmation mauvaise
		if($_POST['motpasse_2']==$_POST['motpasse_3']) {
			$ok=5; // mot correct mais d�j� utilis�
			mysql_connect(hote, login, mot_passe_sql); // Connexion � MySQL
			mysql_select_db(base_de_donnees); // S�lection de la base 
			$question="SELECT * FROM amap_generale WHERE Mot_passe='".$motdp."' AND Login='".$login_2."'";
			$reponse = mysql_query($question) or die(mysql_error());
			$ligne = mysql_num_rows($reponse);
			if($ligne==0) {
				$motdp=SHA1($motdp); // Encodage mot de passe
				$question="UPDATE amap_generale SET Mot_passe='".$motdp."', Login='".$login_2."' WHERE e_mail='".$_GET['e_mail']."'";
				$reponse = mysql_query($question) or die(mysql_error());
				$question="SELECT * FROM amap_generale WHERE e_mail='".$_GET['e_mail']."'";
				$reponse = mysql_query($question) or die(mysql_error());
				$donnees=mysql_fetch_array($reponse);
				setcookie('identification_amap',$donnees['id']);
				mysql_close();
				header("Location: index.php");
			}
			mysql_close();
		}
	}
}
?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="fr" >
<!-- xmlns indique une adresse traitant du xHTML -->
<!-- xml:lang : sert � indiquer dans quelle langue est r�dig�e votre page -->
	<head>
		<title>AMAP Saint-S�bastien/Loire</title>
		<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
		<!-- meta indique que l'on utilise des caract�res sp�cifiques au fran�ais ����... -->
		<link rel="stylesheet" media="screen" type="text/css" title="css_style" href="style.css" />
		<link rel="icon" type="image/jpeg" href="images/favicone-2.jpeg" />
	</head>
	<body>
		<div id="en_tete">
			<?php include("includes/en_tete.php") ?>
		</div>
		<div id="bandeau">
			<?php include("includes/bandeau.php") ?>
		</div>
		<div id="page_principale">
			<?php include("includes/menu_gauche.php");
			if($ok==-1 || $ok==0) { ?>
				<h3 class="mot_passe_recette">Entrer vos identifiants</h3>
				<form class="mot_passe_recette" method="post" action="identification.php" >
					<p class="mot_passe_recette">
						<label for="login">Login : </label>
						<br />
						<input type="text" name="login" id="login" size="50" maxlength="45" tabindex="10"/>
						<br />
						<label for="motpasse">Mot de passe : </label>
						<br />
						<input type="password" name="motpasse" id="motpasse" size="50" maxlength="45" tabindex="10"/>
						<br /><br />
						<input type="submit" tabindex="20"/>
						<input type="button" value="Annuler" onclick="document.location.href='index.php'" tabindex="50"/>
					</p>
				</form> <?php
				if($ok==0) { ?><h3 class="mot_passe_recette">Identifiant(s) incorrect(s) ou non inscrit sur la liste de l'AMAP !!</h3><?php } ?>
				<h3 class="mot_passe_recette">Identifiant(s) oubli�(s) !! -> Demander les par mail au r�f�rent legumes</h3>
				<?php
			} 
			if($ok==1) { ?>
				<h3 class="mot_passe_recette">Ceci est votre premi�re identification !<br />Il vous faut personaliser vos identifiants !!</h3>
				<form class="mot_passe_recette" method="post" action="identification.php?e_mail=<?php echo $_POST['motpasse']; ?>" >
					<p class="mot_passe_recette">
						<label for="login_2">Votre nouveau login :</label><br />
						<input type="text" name="login_2" id="login_2" size="50" maxlength="45" tabindex="10"/>
						<br /><br />
						<label for="motpasse_2">Votre nouveau mot de passe :</label><br />
						<input type="password" name="motpasse_2" id="motpasse_2" size="50" maxlength="45" tabindex="10"/>
						<br />
						<label for="motpasse_3">Confirmez votre mot de passe :</label><br />
						<input type="password" name="motpasse_3" id="motpasse_3" size="50" maxlength="45" tabindex="10"/>
						<br /><br />
						<input type="submit" tabindex="20"/>
						<input type="button" value="Annuler" onclick="document.location.href='index.php'" tabindex="50"/>
					</p>
				</form>
			<h3 class="mot_passe_recette">Pour modifier ult�rieurement !<br /><a href="index.php">Cliquez ici !!</a></h3>				<?php
			} 
			if($ok==3 || $ok==4 || $ok==5) {
				if($ok==3) { ?><h3 class="mot_passe_recette">Caract�res interdits : 4 caract�res min, que des lettres non accentu�es, des chiffres, @._- .<br />Recommencez !!</h3><?php }
				if($ok==4) { ?><h3 class="mot_passe_recette">Confirmation mot de passe non valide. Recommencez !!</h3><?php }
				if($ok==5) { ?><h3 class="mot_passe_recette">Identifiants d�j� utilis�s. Recommencez !!</h3><?php } ?>
				<form class="mot_passe_recette" method="post" action="identification.php?e_mail=<?php echo $_GET['e_mail']; ?>" >
					<p class="mot_passe_recette">
						<label for="login_2">Votre nouveau login :</label><br />
						<input type="text" name="login_2" id="login_2" size="50" maxlength="45" tabindex="10"/>
						<br /><br />
						<label for="motpasse_2">Votre nouveau mot de passe :</label><br />
						<input type="password" name="motpasse_2" id="motpasse_2" size="50" maxlength="45" tabindex="10"/>
						<br />
						<label for="motpasse_3">Confirmez votre mot de passe :</label><br />
						<input type="password" name="motpasse_3" id="motpasse_3" size="50" maxlength="45" tabindex="10"/>
						<br /><br />
						<input type="submit" tabindex="20"/>
						<input type="button" value="Annuler" onclick="document.location.href='index.php'" tabindex="50"/>
					</p>
				</form>				
			<?php
			} ?>
		</div>		
		<div id="pied_page">
			<!--<?php include("includes/pied_page.php") ?>-->
		</div>
	<p>
		<!--<img src="images/logo_lesgumes.jpeg" alt="Logo de l'AMAP" title="Groupement Uni pour un Meilleur Environnement Solidaire" /> -->
		<!-- alt indique un texte alternatif au cas o� l'image ne peut pas �tre t�l�charg�e -->
	</p>
	</body>
</html>
