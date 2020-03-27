<?php include("webmaster/define.php");
include("espace_producteurs/mes_fonctions.php");
$ok=-1;/* identification non faite */
if (isset($_COOKIE['identification_amap'])) {
	$ok=0;/* identification faite mais pas dans le répertoire */
	$id=$_COOKIE['identification_amap'];
	mysql_connect(hote, login, mot_passe_sql); // Connexion à MySQL
	mysql_select_db(base_de_donnees); // Sélection de la base 
	$question="SELECT * FROM repertoire WHERE Code='".$id."'";
	$reponse = mysql_query($question) or die(mysql_error());
	$ligne = mysql_num_rows($reponse);
	if($ligne>0) $ok=1; /* identification faite et figure dans le repertoire */
	if(isset($_GET['orderby'])) $orderby=$_GET['orderby']; else $orderby='Nom';
	mysql_close();
}
if ($ok==1 || $ok==0) {
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
		<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
		<!-- meta indique que l'on utilise des caractères spécifiques au français éèêà... -->
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
		<div id="menu_1" class="menubdhv" style="background-color: green;">
			<span style="color:#FFFFFF; font-family:Arial; font-size:12pt; font-weight:bold; marging-left:5px;">&nbsp;Les informations qui figurent dans ce répertoire ont été données par les personnes elles-mêmes.
				</br>&nbsp;Pour faciliter les échanges de paniers lorsque vous êtes en vacances. Pour vous regrouper pour prendre vos paniers etc.
				</br><span style="color:yellow; font-family:Arial; font-size:12px;">&nbsp;&nbsp;&nbsp;Cliquer sur le nom de la colonne pour changer l'ordre : cliquer sur 'Commune' pour classer par commune, sur 'Mail' pour classer par mail, etc.</span>
			</span><br />
		</div>
		<div id="menu_2" class="menubdhv">
			<?php if($ok==0) { ?>
				<button onclick="document.location.href='repertoire_ajoute.php?id=<?php echo $id; ?>&amp;orderby=<?php echo $orderby; ?>'" name="BtnAjouter" type="Button" class="BtnStd">Je veux m'ajouter au répertoire</button>
			<?php }
			else { ?>
				<button onclick="document.location.href='repertoire_sup.php?id=<?php echo $id; ?>&amp;orderby=<?php echo $orderby; ?>'" name="BtnSupprimer" type="Button" class="BtnStd">Je veux m'enlever du répertoire</button>
				<button onclick="document.location.href='repertoire_modif.php?id=<?php echo $id; ?>&amp;orderby=<?php echo $orderby; ?>'" name="BtnModifier" type="Button" class="BtnStd">Modifier mes infos</button>
			<?php } ?>
			<button onclick="document.location.href='index.php'" name="BtnAccueil" type="Button" class="BtnStd">Accueil</button><br />
		</div>
		<div id="menu_3" class="menubdhv">
		<form name="MForm">
			<table class="DBEdit" cellspacing="0" cellpadding="10px">
			<tr class="StaticText">
			
				<?php
				mysql_connect(hote, login, mot_passe_sql); // Connexion à MySQL
				mysql_select_db(base_de_donnees); // Sélection de la base 
				$question="SELECT * FROM repertoire";
				$reponse = mysql_query($question) or die(mysql_error());
				$ligne = mysql_num_rows($reponse);
				$donnees = mysql_fetch_array($reponse);
				if($ligne<1) { ?><td nowrap="" class="DBEditHeader"><font class="DBEditHeader">Ce répertoire est vide</font></td>
				<?php }
				else {
					$i=0;
					foreach($donnees as $cle => $element) {
						$i++;
						if($i % 2 ==0 && $i>2) {?>
						<td nowrap="" class="DBEditHeader"><font class="DBEditHeader"><a class="ADTxt" href="repertoire.php?orderby=<?php echo $cle; ?>"><?php echo $cle; ?></a></font></td>
				<?php } } ?>
				</tr>
				<?php
					mysql_connect(hote, login, mot_passe_sql); // Connexion à MySQL
					mysql_select_db(base_de_donnees); // Sélection de la base 
					$question="SELECT * FROM repertoire ORDER BY ".$orderby;
					$reponse = mysql_query($question) or die(mysql_error());
					$ligne = mysql_num_rows($reponse);
					if($ligne>0) { while($donnees = mysql_fetch_array($reponse)) { ?>
						<tr class="DBEditRowDefault" id="TR-<?php echo($donnees[1]); ?>" onmouseout="javascript:ADDBSetRowColorByID('TR-<?php echo($donnees[1]); ?>',0);" onmouseover="javascript:ADDBSetRowColorByID('TR-<?php echo($donnees[1]); ?>',1);">
							<?php
							$i=0;
							foreach($donnees as $element) {
								$i++;
								if($i % 2 ==0 && $i>2) {
									if ($i==16) {?><td class="DBEditData"><span class="ADMainTxt"><a href="mailto:<?php echo($element);?>"><?php echo($element);?></a></span></td><?php }
									else {?><td class="DBEditData"><span class="ADMainTxt"><?php echo($element);?></span></td><?php }
								}
							} ?>
						</tr> 
					<?php } 
					} ?>
			<?php } ?>
			</table>
		</form>
		<?php mysql_close(); ?>
		</div>
	</body>
</html>
<?php }


if($ok==-1) { 
 ?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="fr" >
<!-- xmlns indique une adresse traitant du xHTML -->
<!-- xml:lang : sert à indiquer dans quelle langue est rédigée votre page -->
	<head>
		<title>AMAP Saint-Sébastien/Loire</title>
		<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
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