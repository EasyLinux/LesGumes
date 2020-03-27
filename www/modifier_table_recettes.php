<?php
include("webmaster/define.php");
$ok=-1;
if (isset($_COOKIE['identification_amap'])) // Si la variable existe
{
	if($_GET['action']=='ajouter') {
		mysql_connect(hote, login, mot_passe_sql); // Connexion à MySQL
		mysql_select_db(base_de_donnees); // Sélection de la base 
		$question="SELECT COUNT(*) AS nbre FROM amap_generale WHERE id='".$_COOKIE['identification_amap']."'";
		$reponse = mysql_query($question) or die(mysql_error());
		$donnees = mysql_fetch_array($reponse);
		if ($donnees['nbre'] == '1') 
		{
			$ok=1;
			$question="SELECT * FROM amap_generale WHERE id='".$_COOKIE['identification_amap']."'";
			$reponse = mysql_query($question) or die(mysql_error());
			$donnees = mysql_fetch_array($reponse);
			$nom=$donnees['Nom'];
			$prenom=$donnees['Prenom'];
		}
		else $ok=0;
		mysql_close(); // Déconnexion de MySQL
		
	}
	if($_GET['action']=='supprimer' || $_GET['action']=='modifier') {
		mysql_connect(hote, login, mot_passe_sql); // Connexion à MySQL
		mysql_select_db(base_de_donnees); // Sélection de la base 
		$question="SELECT * FROM amap_generale WHERE id='".$_COOKIE['identification_amap']."'";
		$reponse = mysql_query($question) or die(mysql_error());
		$donnees=mysql_fetch_array($reponse);
		$nom_prenom = $donnees['Prenom'].' '.$donnees['Nom'];
		$question="SELECT * FROM recettes WHERE Nom_recette='".$_GET['nom_recette']."'";
		$reponse = mysql_query($question) or die(mysql_error());
		$donnees = mysql_fetch_array($reponse);
		if ($nom_prenom == $donnees['Auteur']) $ok=1;
		else $ok=2;
		mysql_close(); // Déconnexion de MySQL		
	}
}
if ($ok==1) // Si le mot de passe est bon
{
//***********************************************************************
// On a le droit de mettre à jour la table des recettes.
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
		<link rel="stylesheet" media="screen" type="text/css" title="css_style" href="style.css" />
		<link rel="icon" type="image/jpeg" href="images/favicone-2.jpeg" />

			<script type="text/javascript">
			<!--
				function FocusNom(boite) {
					if(boite.value=='- - Entrer le nom de la recette - -') boite.value='';
				}
				function BlurNom(boite) {
					if(boite.value=='') boite.value='- - Entrer le nom de la recette - -';
				}
				function tester(formulaire)
				{
					var nom_recette = document.getElementsByTagName('input')[0].value;
					var regExpBeginning = /^\s+/;
					var regExpEnd = /\s+$/;  
// Supprime les espaces inutiles en début et fin de la chaîne passée en paramètre.
					nom_recette=nom_recette.replace(regExpBeginning, "").replace(regExpEnd, "");
					document.getElementsByTagName('input')[0].value=nom_recette;
					
					var test = nom_recette.toLowerCase();
					
					if (test == '') alert('Il faut indiquer un nom de recette!');
					else if (test[0]>'z' || test[0]<'a') alert('Il faut un nom de recette commençant par une lettre et non par : "'+nom_recette[0]+'"');
					else formulaire.submit();
				}
				
			-->
			</script>



		</head>
	<body>
		<div id="en_tete">
			<?php include("includes/en_tete.php") ?>
		</div>
		<div id="bandeau">
			<?php include("includes/bandeau.php") ?>
		</div>
		<div id="page_principale">
			<?php 
				if($_GET['action']=="modifier") {
					include("includes/menu_gauche.php");
					?>
					<h3 class="modifier_recette">Modifier votre recette <span class="modifier_recette"><?php echo stripslashes($_GET['nom_recette']); ?></span> </h3>
					<form class="modifier_recette" method="post" action="maj_table_recettes.php?nom_recette=<?php echo stripslashes($_GET['nom_recette']); ?>&amp;action=modifier">
						<p class="modifier_recette">
						<?php 
							mysql_connect(hote, login, mot_passe_sql); // Connexion à MySQL
							mysql_select_db(base_de_donnees); // Sélection de la base 
							$nom_recette = $_GET['nom_recette'];
							$question="SELECT Recette FROM recettes WHERE Nom_recette='".$nom_recette."'";
							$reponse=mysql_query($question) or die(mysql_error()); // Requête SQL
							$donnees = mysql_fetch_array($reponse);
							mysql_close(); // Déconnexion de MySQL
						?>
							<textarea name="texte_recette" cols="100" tabindex="10"><?php echo $donnees['Recette']; ?></textarea><br />
							<input type="submit" tabindex="20" />
							<input type="button" value="Annuler" onclick="document.location.href='index.php'" tabindex="50"/>
						</p>
					</form>
				<?php }
				if($_GET['action']=="supprimer") { 
					mysql_connect(hote, login, mot_passe_sql); // Connexion à MySQL
					mysql_select_db(base_de_donnees); // Sélection de la base 
					$nom = $_GET['nom_recette'];
					$question="DELETE FROM recettes WHERE Nom_recette='".$nom."'";
					mysql_query($question) or die(mysql_error()); // Requête SQL
					mysql_close(); // Déconnexion de MySQL
					include("includes/menu_gauche.php");
					?>
					<h3 class="mot_passe_recette">La recette <span class="mot_passe_recette"><?php echo stripslashes($_GET['nom_recette']); ?></span> a bien été supprimée!</h3>
				<?php } 
				if($_GET['action']=="ajouter") { 
					include("includes/menu_gauche.php");
				  mysql_connect(hote, login, mot_passe_sql); // Connexion à MySQL
					mysql_select_db(base_de_donnees); // Sélection de la base 
					$question="SELECT distinct Rubrique FROM `recettes` ";
					$reponse =mysql_query($question) or die(mysql_error()); // Requête SQL
					mysql_close(); // Déconnexion de MySQL
					?>
					<h3 class="modifier_recette">Entrer votre recette</h3>
					<form 	class="ajouter_recette" 
							name="nouvelle_recette" 
							method="post" 
							action="maj_table_recettes.php?action=ajouter&amp;prenom=<?php echo $prenom; ?>&amp;nom=<?php echo $nom; ?>" >
					<p class="ajouter_recette">
						<label for="rubrique">Sélectionner la rubrique</label><br />
						<select name="rubrique" id="rubrique">
						    <?php while ($donnees = mysql_fetch_array($reponse) ) { 
                  $rubrique= $donnees['Rubrique']; ?> 
						   	<option value="<?php echo $rubrique; ?>"><?php echo $rubrique; ?></option>
						   	<?php } ?>
						</select><br />

						<label for="nom_recette">Nom de la recette :</label><br />
						<input 	type="text" 
								onblur="javascript:BlurNom(this);" 
								onfocus="javascript:FocusNom(this);" 
								name="nom_recette" id="nom_recette" 
								value="- - Entrer le nom de la recette - -" 
								size="50" maxlength="40" tabindex="10" /><br />
						<label for="nom_auteur">Nom de l'auteur:</label><br />
						<input 	style="background-color:#DDDDDD" 
								readonly 
								type="text" 
								name="nom_auteur" 
								id="nom_auteur" 
								size="50" maxlength="45" tabindex="20" 
								value="<?php echo $prenom; echo ' '; echo $nom; ?>" />
						<br />Entrer la recette:<br />
						<textarea 	name="texte_recette" 
									cols="90" tabindex="30">-- Entrer le texte de votre recette --</textarea><br />
						<input type="button" value="Envoyer" tabindex="40" onclick="javascript:tester(this.form);" />
						<input type="button" value="Annuler" onclick="document.location.href='index.php'" tabindex="50"/>
					</p></form>
				
				<?php } ?>
		</div>		
		<div id="pied_page">
			<!-- <?php include("includes/pied_page.php") ?> -->
		</div>
	<p>
		<!--<img src="images/logo_lesgumes.jpeg" alt="Logo de l'AMAP" title="Groupement Uni pour un Meilleur Environnement Solidaire" /> -->
		<!-- alt indique un texte alternatif au cas où l'image ne peut pas être téléchargée -->
	</p>
	</body>
</html>

<?php
}
if($ok==0 || $ok==2) // le mot de passe n'est pas bon
{
?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="fr" >
<!-- xmlns indique une adresse traitant du xHTML -->
<!-- xml:lang : sert à indiquer dans quelle langue est rédigée votre page -->
	<head>
		<title>AMAP Saint-Sébastien/Loire</title>
		<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
		<!-- meta indique que l'on utilise des caractères spécifiques au français éèêà... -->
		<link rel="stylesheet" media="screen" type="text/css" title="css_style" href="style.css" />
		<link rel="icon" type="image/jpeg" href="images/favicone-2.jpeg" />
	</head>
	<body onload="document.getElementById('motpasse').focus();">
		<div id="en_tete">
			<?php include("includes/en_tete.php") ?>
		</div>
		<div id="bandeau">
			<?php include("includes/bandeau.php") ?>
		</div>
		<div id="page_principale">
			<?php 
				include("includes/menu_gauche.php"); ?>
			<h3 class="mot_passe_recette">Il faut être l'auteur de la recette <span class="mot_passe_recette"><?php echo $_GET['nom_recette'] ?></span> pour la modifier ou la supprimer</h3>
		</div>		
		<div id="pied_page">
			<!-- <?php include("includes/pied_page.php") ?> -->
		</div>
	<p>
		<!--<img src="images/logo_lesgumes.jpeg" alt="Logo de l'AMAP" title="Groupement Uni pour un Meilleur Environnement Solidaire" /> -->
		<!-- alt indique un texte alternatif au cas où l'image ne peut pas être téléchargée -->
	</p>
	</body>
</html>
<?php }
if($ok==-1) { //premier chargement du mot de passe
 ?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="fr" >
<!-- xmlns indique une adresse traitant du xHTML -->
<!-- xml:lang : sert à indiquer dans quelle langue est rédigée votre page -->
	<head>
		<title>AMAP Saint-Sébastien/Loire</title>
		<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
		<!-- meta indique que l'on utilise des caractères spécifiques au français éèêà... -->
		<link rel="stylesheet" media="screen" type="text/css" title="css_style" href="style.css" />
		<link rel="icon" type="image/jpeg" href="images/favicone-2.jpeg" />
	</head>
	<body onload="document.getElementById('motpasse').focus();">
		<div id="en_tete">
			<?php include("includes/en_tete.php") ?>
		</div>
		<div id="bandeau">
			<?php include("includes/bandeau.php") ?>
		</div>
		<div id="page_principale">
			<?php 
				include("includes/menu_gauche.php"); 
			?>
			<h3 class="mot_passe_recette">Il faut vous identifier pour accéder à ce service !!</h3>
		</div>		
		<div id="pied_page">
			<!-- <?php include("includes/pied_page.php") ?> -->
		</div>
	<p>
		<!--<img src="images/logo_lesgumes.jpeg" alt="Logo de l'AMAP" title="Groupement Uni pour un Meilleur Environnement Solidaire" /> -->
		<!-- alt indique un texte alternatif au cas où l'image ne peut pas être téléchargée -->
	</p>
	</body>
</html> 
 <?php } ?>
