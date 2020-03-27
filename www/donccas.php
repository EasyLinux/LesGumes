<?php include("webmaster/define.php"); 
$ok=-1;
if (isset($_COOKIE['identification_amap'])) // Si la variable existe
{	
	//pour imposer que la personne soit inscrite aux légumes rétablir les lignes ci-dessous et enlever ok=1
	$ok=0; //identifié mais pas inscrit aux légumes
	mysql_connect(hote, login, mot_passe_sql); // Connexion à MySQL
	mysql_select_db(base_de_donnees); // Sélection de la base 
  $id = $_COOKIE['identification_amap'];
	$question="SELECT Nom, Prenom FROM ".$_GET['amap']." WHERE id='".$id."'";
	$reponse = mysql_query($question) or die(mysql_error());
	$ligne = mysql_num_rows($reponse);
 
	if($ligne>0)
     $ok=1; //identifié et inscrit aux légumes
	mysql_close();
}
if($ok==1) { ?>

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
		<div id="page_principale">
			<?php include("includes/menu_gauche.php");
				mysql_connect(hote, login, mot_passe_sql); // Connexion à MySQL
				mysql_select_db(base_de_donnees); // Sélection de la base 
    
        $reponse =  mysql_query("SELECT Count(*) as nb FROM amap_legumes_dons WHERE Date <= CURRENT_DATE and id='".$id."'");
        $row = mysql_fetch_array ($reponse);
        $nbavant = $row[0];
        $reponse =  mysql_query("SELECT Count(*) as nb FROM amap_legumes_dons WHERE Date <= CURRENT_DATE ");
        $row = mysql_fetch_array ($reponse);
        $nbAmap = $row[0];
 
    		$reponse = mysql_query("SELECT Date FROM ".$_GET['amap']."_permanences WHERE Distribution='1' and Date > CURRENT_DATE   ORDER BY Date") or die(mysql_error()); // Requête SQL
    
      ?>	  
							
			<h3 style="color:yellow; text-align:center; ">
      Je suis dans l'incapacité de récupérer ou faire récupérer mon panier de légumes à une distribution. <br /> 
      Pour éviter le gaspillage, je choisis de donner mon panier au CCAS qui le redistribuera aux plus démunis. <br />
      En remplissant ce tableau, je préviens <em>au moins 1 jour à l'avance </em> les producteurs qui s'engagent à donner ce panier au CCAS du Cellier.     
      </h3>
      <h3 style="color:orange; text-align:center; ">
      Nombre de don réalisé par l'amap depuis le début du contrat :  <? echo $nbAmap;?>.
      </h3>
			<table class="h3" >
         <tr>
					<th>Date de distribution</th>
          <th>Cliquer sur une case pour enregistrer/annnuler votre don</th>				
				</tr>
				<?php
				while ($donnees = mysql_fetch_array($reponse) ) {
          $date= $donnees['Date'];
				?>
				<tr>
					<td class="h3_date"><?php echo date("d-M-y",strtotime($date)); ?></td>
					<td class="h3">
            <a class="tab_perm_leg" href="modifier_dons.php?date=<?php echo $date;?>&amp;id=<?php echo $id;?>&amp;amap=<?php echo $_GET['amap'];?>">
              <?php 
                   
              $actuel = mysql_query("SELECT Personne FROM amap_legumes_dons WHERE Date='".$date."' And id='".$id."'");
              if ( $row = mysql_fetch_array($actuel))  { ?>
                  je donne ce panier au CCAS
              <?php } else { ?>     ? <?php } ?>
            </a>
          </td>
				</tr>	
        <?php } ?> 						
      </table>   
		</div>		
		<div id="pied_page">
			<!-- <?php include("includes/pied_page.php") ?> -->
		</div>
		<!--<img src="images/logo_lesgumes.jpeg" alt="Logo de l'AMAP" title="Groupement Uni pour un Meilleur Environnement Solidaire" /> -->
		<!-- alt indique un texte alternatif au cas où l'image ne peut pas être téléchargée -->
	</body>
</html>
<?php
mysql_close(); // Déconnexion de MySQL
}
else { 
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
			if($ok==-1) { ?><h3 class="mot_passe_recette">Il faut vous identifier pour accéder à ce service !!</h3><?php }
			if($ok==0) { ?><h3 class="mot_passe_recette">Il faut faire partie de l'<?php echo $_GET['amap'] ?> pour accéder à ce service !!</h3><?php } ?>
		</div>		
		<div id="pied_page">
			<!-- <?php include("includes/pied_page.php") ?> -->
		</div>

	</body>
</html> 
 <?php } ?>
