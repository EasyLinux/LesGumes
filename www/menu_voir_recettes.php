<?php include("webmaster/define.php"); ?>

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
   
   		$reponse = mysql_query("SELECT Rubrique,Nom_recette FROM recettes ORDER BY Rubrique ASC, Nom_recette ASC") or die(mysql_error()); // Requête SQL
      mysql_close(); // Déconnexion de MySQL
      if(mysql_num_rows($reponse) <= 0) { ?>
        <p> Aucune recette de saisie </p>
      <?php } else {  ?>
        <table >
        <thead>
         <tr> <th colspan="5">
           <button onclick="document.location.href='modifier_table_recettes.php?action=ajouter'" type="button" class="BtnStd">Ajouter une recette</button>
         </th></tr>
          <tr >
               <th style="background-color: orange;">type</th>
               <th style="background-color: orange;">recette</th>
               <th style="background-color: orange;" colspan="3">actions</th>
           </tr>
        </thead>
        <tbody>
         <?php  while ($donnees = mysql_fetch_array($reponse) ) { 
            $nom = $donnees['Nom_recette'];
            $type = $donnees['Rubrique']?>	
            <tr>
              <td style="background-color:#FFFF99;"> <?php echo $type ?> </td>
              <td style="background-color:#FFFF99;"> <?php echo $nom ?> </td>  
              <td > <a class="tab_perm_leg" href="lire_recettes.php?nom_recette=<?php echo $nom?>">
              <img style="display: inline; float: right; " title="lire la recette" src="images/see.jpg" alt="lire la recette" />              
              </a> </td>  
              <td > <a class="tab_perm_leg" href="modifier_table_recettes.php?nom_recette=<?php echo $nom?>&amp;action=modifier">
                <img style="display: inline; float: right; " title="modifier la recette" src="images/update.jpg" alt="modifier la recette" />
              </a></td>
              <td > <a class="tab_perm_leg" href="modifier_table_recettes.php?nom_recette=<?php echo $nom?>&amp;action=supprimer">
                    <img style="display: inline; float: right; " title="supprimer la recette" src="images/delete.jpg" alt="supprimer la recette" />
              </a></td>
            </tr>
          <?php } ?> 
        </tbody>         
          </table> 
     
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
