<?php
include_once("webmaster/define.php");
$ok=-1;/* pas identifier */
if (isset($_COOKIE['identification_amap'])) // Si la variable existe
{
	$ok=0; //identifi� mais d�j� inscrit aux l�gumes
	mysql_connect(hote, login, mot_passe_sql); // Connexion � MySQL
	mysql_select_db(base_de_donnees); // S�lection de la base 
	$id=$_COOKIE['identification_amap'];
	echo "id cockie=".$id ." et id requete=".$_GET['id'];
	$question="SELECT * FROM amap_legumes WHERE id='".$id."'";
	$reponse = mysql_query($question) or die(mysql_error());
	$ligne = mysql_num_rows($reponse);
	if($ligne==0) {
		$ok=1; //identifi� et non inscrit aux l�gumes
		$question="SELECT * FROM amap_legumes_liste_attente WHERE id='".$id."'";
		$reponse = mysql_query($question) or die(mysql_error());
		$ligne = mysql_num_rows($reponse);
		if($ligne>0) {
			$ok=2;} //d�j� inscrit sur la liste d'attente
		if (isset($_GET['id'])) // Si la variable existe
		{
			$question="SELECT * FROM amap_legumes_liste_attente WHERE id='".$_GET['id']."'";
			echo $question;
			$reponse = mysql_query($question) or die(mysql_error());
			$ligne = mysql_num_rows($reponse);
			if(($ligne==0) && ($_GET['reponse']=='oui')) {
				$ok=3; //inscription r�ussi
				$question="SELECT * FROM amap_generale WHERE id='".$_GET['id']."'";
				$reponse = mysql_query($question) or die(mysql_error());
				$donnees = mysql_fetch_array($reponse);
			
				$question = "INSERT INTO amap_legumes_liste_attente VALUES ('".$_GET['id']."', '".$donnees['Nom']."', '".$donnees['Prenom']."'";
				$question.=", '".date('Y-m-d H:m:s')."')";
				$reponse = mysql_query($question) or die(mysql_error());
			}
			elseif ($ligne>0 && $_GET['reponse']=='non') {
				$ok=4; // d�sinscription r�ussi
				$question = "DELETE FROM amap_legumes_liste_attente WHERE id='".$_GET['id']."'";
				$reponse = mysql_query($question) or die(mysql_error());
			}
		}
	}
	mysql_close();

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
			<?php include_once("includes/en_tete.php") ?>
		</div>
		<div id="bandeau">
			<?php include_once("includes/bandeau.php") ?>
		</div>
		<div id="page_principale">
			<?php include_once("includes/menu_gauche.php");
			if($ok==-1 || $ok==0) {
				if($ok==-1) {?>
					<h3 class="mot_passe_recette">Il faut vous identifier pour acc�der � ce service !!</h3> 
				<?php }
				if($ok==0) { ?>
					<h3 class="mot_passe_recette">Vous �tes d�j� inscrit � l'AMAP <?php echo $_GET['amap'] ?><br />Allez dans <em style="color:blue"> acc�s contrat </em>pour v�rifier/modifier !!</h3>
				<?php } ?>
				<?php }
			if($ok==1 || $ok==2) { ?>
				<?php if($ok==1) { ?><h3 class="mot_passe_recette">Cliquez pour vous inscrire sur la liste d'attente l�gumes</h3> <?php } ?>
				<?php if($ok==2) { ?><h3 class="mot_passe_recette">Cliquez pour vous d�sinscrire de la liste d'attente l�gumes</h3> <?php } ?>
				<form class="mot_passe_recette" method="post" action="index.php" >
					<p class="mot_passe_recette">
						<?php if($ok==1) { ?><input type="button" value="Je veux m'inscrire sur la liste d'attente l�gumes" onclick="window.location.href='liste_attente_legumes.php?id=<?php echo $id; ?>&amp;reponse=oui'" /> <?php } ?>
						<?php if($ok==2) { ?><input type="button" value="Je veux me d�sinscrire de la liste d'attente l�gumes" onclick="window.location.href='liste_attente_legumes.php?id=<?php echo $id; ?>&amp;reponse=non'" /> <?php } ?>
					</p>
				</form>
				<?php
			}
			if($ok==3) { ?>
				<h3 class="mot_passe_recette">Vous venez d'�tre ajout� � la liste d'attente l�gumes.</h3> 
			<?php } 
			if($ok==4) { ?>
				<h3 class="mot_passe_recette">Vous venez d'�tre retir� de la liste d'attente l�gumes.</h3> 
			<?php } 
			if($ok>0) {
				mysql_connect(hote, login, mot_passe_sql); // Connexion � MySQL
				mysql_select_db(base_de_donnees); // S�lection de la base 
				$question="SELECT * FROM amap_legumes_liste_attente ORDER BY Date_inscription";
				$reponse = mysql_query($question) or die(mysql_error());
				$ligne = mysql_num_rows($reponse);
				$n=0;?>
				<table class="h3"> 
					<caption class="h3">Le nombre de personnes sur la liste d'attente est de <?php echo $ligne; ?></caption>
					<tr>
						<th>Nom</th><th>Date d'inscription</th><th>Votre ordre dans la liste</th>
					</tr> <?php
					while($donnees=mysql_fetch_array($reponse)) {
						$n++;?>
						<tr> 
							<td class="h3"><?php echo $donnees['Nom']." ".$donnees['Prenom']; ?></td>
							<td class="h3"><?php echo date("d-M-Y H:m:s",strtotime($donnees['Date_inscription'])); ?></td>
							<td class="h3"><?php echo $n; ?></td>
						</tr>
					<?php } ?>
				</table>
				<?php
				mysql_close();
			} ?>
		</div>		
		<div id="pied_page">
			<!--<?php include_once("includes/pied_page.php") ?>-->
		</div>
	<p>
		<!--<img src="images/logo_lesgumes.jpeg" alt="Logo de l'AMAP" title="Groupement Uni pour un Meilleur Environnement Solidaire" /> -->
		<!-- alt indique un texte alternatif au cas o� l'image ne peut pas �tre t�l�charg�e -->
	</p>
	</body>
</html>
