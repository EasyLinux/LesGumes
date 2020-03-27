<?php
include_once("define.php"); 
include_once("../espace_producteurs/mes_fonctions.php");
$tri = isset( $_GET['classement']) ? $_GET['classement'] : "Nom";
$sens = $_GET['sens']=="DESC" ? "DESC" : "ASC";
?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="fr" >
<!-- xmlns indique une adresse traitant du xHTML -->
<!-- xml:lang : sert � indiquer dans quelle langue est r�dig�e votre page -->
	<head>
		<title>AMAP Saint-S�bastien/Loire</title>
		<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
		<!-- meta indique que l'on utilise des caract�res sp�cifiques au fran�ais ����... -->
		<link rel="stylesheet" media="screen" type="text/css" title="css_style" href="style_webmaster.css" />
	</head>
	
	<body>
		<div id="page_principale">
		<div class="menu_fixe">
			<div class="menu_fixe_droit">
<!--
				<p style="padding:5px; color:red; text-align:center; background:yellow">
					<strong>Pour ajouter ou supprimer un adh�rent � l'amap il faut passer par une mise � jour de l'amap g�n�rale!!</strong>
				</p>
-->
			</div>
			<p><a href="../index.php">Accueil</a>------<a href="webmaster.php?mode=<?php echo $_GET['amap']; ?>">Menu webmaster</a></p>
			<p><strong><u>Classement</u> : </strong>
				<a href="webmaster_voir_amap.php?classement=id&amp;amap=<?php echo $_GET['amap']; ?>&amp;ordre=<?php echo $_GET['ordre']; ?>">par id</a>------
				<a href="webmaster_voir_amap.php?classement=nom&amp;amap=<?php echo $_GET['amap']; ?>&amp;ordre=<?php echo $_GET['ordre']; ?>">par nom</a>
			</p>
			<p><strong><u>Actions possibles</u> : </strong>
<!--				<a href="webmaster_voir_amap.php?classement=<?php //echo $_GET['classement']; ?>&amp;ordre=modifier&amp;amap=<?php //echo $_GET['amap']; ?>">modifier un enregistrement</a>------
				<a href="webmaster_voir_amap.php?classement=<?php //echo $_GET['classement']; ?>&amp;ordre=rien&amp;amap=<?php //echo $_GET['amap']; ?>">Action annul�e</a>------
				<a href="javascript:location.reload()">Actualiser la page courante</a>
			</p>
			<p style="color:red; background:yellow"><strong>Pour mettre � jour l'affichage ==> actualiser la page courante apr�s la modification!!</strong></p>
	-->	</div>
		<div class="menu_roulant">
			<?php
			if($_GET['ordre']=='rien') {
				 AfficherTable(base_de_donnees, $_GET['amap'],$tri,$sens,'webmaster_voir_amap.php');
			} ?>
		</div>
		</div>
	</body>
</html>
