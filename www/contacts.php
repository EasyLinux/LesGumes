<?php include_once("webmaster/define.php"); ?>

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
			<?php include_once("includes/en_tete.php") ?>
		</div>
		<div id="bandeau">
			<?php include_once("includes/bandeau.php") ?>
		</div>
		<div id="page_principale">
			<?php include_once("includes/menu_gauche.php") ?>
		<h1 class="texte" style="text-align:center">   Liste des référents </h1>
	  <p class="texte" style="text-align:center">
		<?php
			mysqli_connect(hote, login, mot_passe_sql); // Connexion à MySQL
			mysqli_select_db(base_de_donnees); // Sélection de la base 
			$referents=mysqli_query("SELECT Nom_amap, nom, prenom, mail FROM `liste_amap` JOIN referent ON liste_amap.id_referent=referent.id") or die(mysqli_error());	  
      while ($donnees = mysqli_fetch_array($referents)){
	      $amap = $donnees[0];
	      $nom = "$donnees[2] $donnees[1]";
        $mail = $donnees[3];
	      echo $donnees[0] ?>  :
	      <a style="color:#00EEEE" href="mailto:<?php echo $mail?>?subject=[Amap LesGUMES] INFO <?php echo $amap?>"><?php echo $nom?> </a> ( <?php echo $mail?>)<br />
	    <?php } 
      
      $reponses =mysqli_query("SELECT mail FROM referent WHERE fonction_speciale='liste_diffusion'") or die(mysqli_error());
      $mailRespDiff =mysqli_fetch_array($reponses);
      ?>
     </p> 
      
     <h1  class="texte" style="text-align:center">   Pour envoyer des mails</h1>
      <p  class="texte" style="text-align:center">
        
			   Ecrire à tous les référents : <a style="color:#00EEEE" href="mailto: <?php
         	$mails=mysqli_query("SELECT  DISTINCT mail FROM referent ORDER BY Nom") or die(mysqli_error());
   	      while ($donnees = mysqli_fetch_array($mails)){
			     echo $donnees[0]; ?>;<?php 
	        }
         ?>?subject=[Amap LesGUMES]">cliquer ici</a><br />
         Ecrire au responsable des listes de diffusion : <a style="color:#00EEEE" href="mailto: 
         <?php
			     echo $mailRespDiff[0]; 
         ?>?subject=[Amap LesGUMES]LISTE DIFFUSION">cliquer ici</a> (<?php echo $mailRespDiff[0]; ?>) <br />
	  	   Ecrire à tous les amapiens pour un sujet concernant directement et exclusivement la vie de l'amap: <a style="color:#00EEEE" href="mailto:amap-lesgumes@googlegroups.com"> cliquer ici </a>(amap-lesgumes@googlegroups.com)<br />
	  	   Diffuser une information autour de l'amap LesGumes : <a style="color:#00EEEE" href="mailto: inforumamapsaintseb@googlegroups.com"> cliquer ici </a>(amap-lesgumes@googlegroups.com)<br /> <br />
	    </p>
	    <h1 class="texte" style="text-align:center">   Liens </h1>
      <p  class="texte" style="text-align:center">
         <a style="text-align:center; color:yellow" href="http://www.amap44.org/" onclick="window.open(this.href); return false;">
           <img src="images/amap44.jpg" title="Pour en savoir plus sur les AMAPs..." alt="amap44" width="100"/>
        </a>
         <a href="http://www.avenir-bio.fr"><img title="Le site du consommer autrement..." src="images/avenirbio.jpg" width="80"  /></a>
         <a href="http://www.terreco.net"><img title="L'actualité..." src="images/terraeco.png" width="80"  /></a>
         <a href="http://www.consommer-responsable.fr/"><img title="L'actualité dans les pays de la loire..." src="images/consommerResponsable.png" width="100"  /></a>
		 <a href="http://www.zeste.coop/"><img title="platefome de finance participative" src="images/Signature_Zeste.png" width="100"  /></a>

      </p> 
		</div>		
		<div id="pied_page">
			<!-- <?php include_once("includes/pied_page.php") ?> -->
		</div>
	<p>
		<!--<img src="images/logo_lesgumes.jpeg" alt="Logo de l'AMAP" title="Groupement Uni pour un Meilleur Environnement Solidaire" /> -->
		<!-- alt indique un texte alternatif au cas où l'image ne peut pas être téléchargée -->
	</p>
	</body>
</html>