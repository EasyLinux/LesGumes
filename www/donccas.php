<?php include("webmaster/define.php"); 
$ok=-1;
if (isset($_COOKIE['identification_amap'])) // Si la variable existe
{	
	//pour imposer que la personne soit inscrite aux l�gumes r�tablir les lignes ci-dessous et enlever ok=1
	$ok=0; //identifi� mais pas inscrit aux l�gumes
	mysql_connect(hote, login, mot_passe_sql); // Connexion � MySQL
	mysql_select_db(base_de_donnees); // S�lection de la base 
  $id = $_COOKIE['identification_amap'];
	$question="SELECT Nom, Prenom FROM ".$_GET['amap']." WHERE id='".$id."'";
	$reponse = mysql_query($question) or die(mysql_error());
	$ligne = mysql_num_rows($reponse);
 
	if($ligne>0)
     $ok=1; //identifi� et inscrit aux l�gumes
	mysql_close();
}
if($ok==1) { ?>

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
				mysql_connect(hote, login, mot_passe_sql); // Connexion � MySQL
				mysql_select_db(base_de_donnees); // S�lection de la base 
    
        $reponse =  mysql_query("SELECT Count(*) as nb FROM amap_legumes_dons WHERE Date <= CURRENT_DATE and id='".$id."'");
        $row = mysql_fetch_array ($reponse);
        $nbavant = $row[0];
        $reponse =  mysql_query("SELECT Count(*) as nb FROM amap_legumes_dons WHERE Date <= CURRENT_DATE ");
        $row = mysql_fetch_array ($reponse);
        $nbAmap = $row[0];
 
    		$reponse = mysql_query("SELECT Date FROM ".$_GET['amap']."_permanences WHERE Distribution='1' and Date > CURRENT_DATE   ORDER BY Date") or die(mysql_error()); // Requ�te SQL
    
      ?>	  
							
			<h3 style="color:yellow; text-align:center; ">
      Je suis dans l'incapacit� de r�cup�rer ou faire r�cup�rer mon panier de l�gumes � une distribution. <br /> 
      Pour �viter le gaspillage, je choisis de donner mon panier au CCAS qui le redistribuera aux plus d�munis. <br />
      En remplissant ce tableau, je pr�viens <em>au moins 1 jour � l'avance </em> les producteurs qui s'engagent � donner ce panier au CCAS du Cellier.     
      </h3>
      <h3 style="color:orange; text-align:center; ">
      Nombre de don r�alis� par l'amap depuis le d�but du contrat :  <? echo $nbAmap;?>.
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
		<!-- alt indique un texte alternatif au cas o� l'image ne peut pas �tre t�l�charg�e -->
	</body>
</html>
<?php
mysql_close(); // D�connexion de MySQL
}
else { 
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
			if($ok==-1) { ?><h3 class="mot_passe_recette">Il faut vous identifier pour acc�der � ce service !!</h3><?php }
			if($ok==0) { ?><h3 class="mot_passe_recette">Il faut faire partie de l'<?php echo $_GET['amap'] ?> pour acc�der � ce service !!</h3><?php } ?>
		</div>		
		<div id="pied_page">
			<!-- <?php include("includes/pied_page.php") ?> -->
		</div>

	</body>
</html> 
 <?php } ?>
