<?php
include_once("define.php"); 
include_once("../espace_producteurs/mes_fonctions.php");
$tri = isset( $_GET['classement']) ? $_GET['classement'] : "Nom";
$sens = $_GET['sens']=="DESC" ? "DESC" : "ASC";
?>


<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="fr" >
<!-- xmlns indique une adresse traitant du xHTML -->
<!-- xml:lang : sert à indiquer dans quelle langue est rédigée votre page -->
	<head>
		<title>AMAP Saint-Sébastien/Loire</title>
		<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
		<!-- meta indique que l'on utilise des caractères spécifiques au français éèêà... -->
		<link rel="stylesheet" media="screen" type="text/css" title="css_style" href="styleW.css" />
	</head>
	<body>

	<div id="page_principale">
		<p> <strong>&nbsp;&nbsp;Navigation &nbsp;&nbsp;&nbsp;:&nbsp;</strong>
			<input type="button" value="Retour à l'accueil" onclick="document.location.href='../index.php'"/>
			<input type="button" value="Menu webmaster" onclick="document.location.href='webmaster.php?mode=<?php echo $_GET['amap']; ?>'"/>
		</p>
		
		<?php
			if($_GET['ordre']=='rien') {
				AfficherTable(base_de_donnees, 'amap_generale',$tri,$sens,"webmaster_amap_generale.php");
			} 
		?>
	</div>
	</body>
</html>
