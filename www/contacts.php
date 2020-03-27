<?php include_once("webmaster/define.php"); ?>

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
			<?php include_once("includes/menu_gauche.php") ?>
		<h1 class="texte" style="text-align:center">   Liste des r�f�rents </h1>
	  <p class="texte" style="text-align:center">
		<?php
			mysql_connect(hote, login, mot_passe_sql); // Connexion � MySQL
			mysql_select_db(base_de_donnees); // S�lection de la base 
			$referents=mysql_query("SELECT Nom_amap, nom, prenom, mail FROM `liste_amap` JOIN referent ON liste_amap.id_referent=referent.id") or die(mysql_error());	  
      while ($donnees = mysql_fetch_array($referents)){
	      $amap = $donnees[0];
	      $nom = "$donnees[2] $donnees[1]";
        $mail = $donnees[3];
	      echo $donnees[0] ?>  :
	      <a style="color:#00EEEE" href="mailto:<?php echo $mail?>?subject=[Amap LesGUMES] INFO <?php echo $amap?>"><?php echo $nom?> </a> ( <?php echo $mail?>)<br />
	    <?php } 
      
      $reponses =mysql_query("SELECT mail FROM referent WHERE fonction_speciale='liste_diffusion'") or die(mysql_error());
      $mailRespDiff =mysql_fetch_array($reponses);
      ?>
     </p> 
      
     <h1  class="texte" style="text-align:center">   Pour envoyer des mails</h1>
      <p  class="texte" style="text-align:center">
        
			   Ecrire � tous les r�f�rents : <a style="color:#00EEEE" href="mailto: <?php
         	$mails=mysql_query("SELECT  DISTINCT mail FROM referent ORDER BY Nom") or die(mysql_error());
   	      while ($donnees = mysql_fetch_array($mails)){
			     echo $donnees[0]; ?>;<?php 
	        }
         ?>?subject=[Amap LesGUMES]">cliquer ici</a><br />
         Ecrire au responsable des listes de diffusion : <a style="color:#00EEEE" href="mailto: 
         <?php
			     echo $mailRespDiff[0]; 
         ?>?subject=[Amap LesGUMES]LISTE DIFFUSION">cliquer ici</a> (<?php echo $mailRespDiff[0]; ?>) <br />
	  	   Ecrire � tous les amapiens pour un sujet concernant directement et exclusivement la vie de l'amap: <a style="color:#00EEEE" href="mailto:amap-lesgumes@googlegroups.com"> cliquer ici </a>(amap-lesgumes@googlegroups.com)<br />
	  	   Diffuser une information autour de l'amap LesGumes : <a style="color:#00EEEE" href="mailto: inforumamapsaintseb@googlegroups.com"> cliquer ici </a>(amap-lesgumes@googlegroups.com)<br /> <br />
	    </p>
	    <h1 class="texte" style="text-align:center">   Liens </h1>
      <p  class="texte" style="text-align:center">
         <a style="text-align:center; color:yellow" href="http://www.amap44.org/" onclick="window.open(this.href); return false;">
           <img src="images/amap44.jpg" title="Pour en savoir plus sur les AMAPs..." alt="amap44" width="100"/>
        </a>
         <a href="http://www.avenir-bio.fr"><img title="Le site du consommer autrement..." src="images/avenirbio.jpg" width="80"  /></a>
         <a href="http://www.terreco.net"><img title="L'actualit�..." src="images/terraeco.png" width="80"  /></a>
         <a href="http://www.consommer-responsable.fr/"><img title="L'actualit� dans les pays de la loire..." src="images/consommerResponsable.png" width="100"  /></a>
		 <a href="http://www.zeste.coop/"><img title="platefome de finance participative" src="images/Signature_Zeste.png" width="100"  /></a>

      </p> 
		</div>		
		<div id="pied_page">
			<!-- <?php include_once("includes/pied_page.php") ?> -->
		</div>
	<p>
		<!--<img src="images/logo_lesgumes.jpeg" alt="Logo de l'AMAP" title="Groupement Uni pour un Meilleur Environnement Solidaire" /> -->
		<!-- alt indique un texte alternatif au cas o� l'image ne peut pas �tre t�l�charg�e -->
	</p>
	</body>
</html>