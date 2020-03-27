<?php include_once("webmaster/define.php");
	mysqli_connect(hote, login, mot_passe_sql); // Connexion à MySQL
	mysqli_select_db(base_de_donnees); // Sélection de la base 
	$reponse=mysqli_query("SELECT Prix FROM amap_legumes_produits WHERE id=1");
	if ( mysqli_error()) {
		$prix = 14;
	} else {
		$reponse = mysqli_fetch_array($reponse);
		$prix = $reponse[0];
	}
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
	<div id="fb-root"></div>
	<!-- lien facebook -->
<div id="fb-root"></div>
<script>(function(d, s, id) {
  var js, fjs = d.getElementsByTagName(s)[0];
  if (d.getElementById(id)) return;
  js = d.createElement(s); js.id = id;
  js.src = "//connect.facebook.net/fr_FR/all.js#xfbml=1";
  fjs.parentNode.insertBefore(js, fjs);
}(document, 'script', 'facebook-jssdk'));</script>
		<div id="en_tete">
			<?php include_once("includes/en_tete.php") ?>
		</div>
		<div id="bandeau">
			<?php include_once("includes/bandeau.php") ?>
		</div>
		<div id="page_principale">
			<?php include_once("includes/menu_gauche.php") ?>


    <table>
    <tr><td>
       <img style="display: inline; float: right; margin-left: 10px;  margin-right: 24px;	margin-top: 4px;" title="les rangs d'oignons" src="images/oignons.jpg" alt="" width="150" height="150" />
     </td>
     <td > 
       <h1 class="texte"> Les rangs d'oignons   
       	<!-- lien sur page facebook -->
       <a href="https://www.facebook.com/LesRangsdOignons/"><img title="retrouver les rangs d'oignons sur facebook..." src="/images/facebook.jpg" style="width: 20px; height: 20px; float: rigth;" /></a>
      </h1>
          <!-- bouton facebook -->
         <div class="fb-like" data-href="https://www.facebook.com/LesRangsdOignons/" data-send="true" data-layout="button_count" data-width="450" data-show-faces="false" data-action="recommend"></div>
    
          <p class="texte"> Les Rangs d'oignons est une ferme de maraichage biologique diversifié sur la commune du Cellier. L'exploitation a été crée par Guillaume Gaspari et  Pauline Chouinau au printemps 2011 sur 4,5 hectares de terre, dans un cadre préservé. En décembre 2017 l'exploitation est vendue à Benjamin Cochin qui continuera à faire pousser nos légumes et à les distribuer en AMAP. 
		  Les terres sont certifiées en Agriculture Biologique et la production respecte ce cahier des charges.
		  <br />
        
      </td>
    <td>
       <img style="display: inline; float: right; margin-left: 10px;  margin-right: 24px;	margin-top: 4px;" title="les rangs d'oignons" src="images/oignons.jpg" alt="" width="150" height="150" />
     </td>
    
     </tr> 
	 <tr align="center"> 
	 <td ></td> 
	 <td >
		<table>
		<tr>
        <td><img style="display: inline; float: right; margin-right: 24px;"  title="tunnels" src="images/tunnelmaraichersbio-150x150.jpg" alt="tunnels" width="150"/> </td>
		<td><a style="color:yellow"  href="http://www.lesrangsdoignons.com/" title="Cliquer ici pour voir la page ds Rangs d'oignons"><img src="images/plus.jpg" alt="ouvrir la page du producteur" title="Cliquer ici pour ouvrir le site des Rangs d'Oignons"/> </a></td>
        <td><img  style="display: inline; float: left; margin-left: 24px;" title="champs bio" src="images/champs-150x150.jpg" alt="champs bio" width="150"/>  </td> 
		</tr>
		</table>
	</td>
	
	<td ></td> 
	</tr>
    
    <tr>
		<td>
        <img style="display: inline; float: right; margin-right: 24px; " title="recolte de potimarrons" src="images/potimarron.jpg" alt="champs bio" width="150"/>
      </td>
      <td>
      <h2 class="texte">Nos paniers :</h2>
      <p class="texte">Nous proposons des paniers de taille unique à <?echo $prix;?> euros.<br/>
      Pour vous donner une idée de ce que pourra contenir nos paniers, voici la gamme de légumes que nous cultiverons :   <br />
      en automne hivers : Betterave, brocoli, carde, carotte, céleri rave, chicorée, chou de Bruxelles, chou fleur, chou pomme, courge, épinard, échalote, fenouil, mâche, navet, oignon, panais, persil, poireau, pomme de terre, radis, radis noir, roquette, rutabaga, salade;
      <br /> au printemps/été : Aubergine, betterave botte, carde, carotte botte, céleri branche, concombre, courgette, épinard, fenouil, fraise, haricot, melon, navet nouveau, oignon, botte, persil, poivron, pomme de terre nouvelle, radis, rhubarbe, tomate, salade.
      <br /> <br />
      Pour finir voici un exemple de contenu d'un panier d'hiver : 1 kg de carotte , 1,5 g de pomme de terre, 1,5 kg de poireau, 500 g de radis noir, 1 salade, 600 g de betterave
      <br/>
      et d'un panier d'été : 1,5 kg de Tomate, 1 kg  de courgette, 2 poivrons, 1 salade, 2 concombres, 750 g d'haricot vert, basilic, persil ou ciboulette.   
      </p> 
     </td>
	 <td ></td> 
	
    </tr>  
    </table>  
    
    	</div>		
  	<div id="pied_page">
  			<!-- <?php include_once("includes/pied_page.php") ?> -->
		</div>
	</body>
</html>
