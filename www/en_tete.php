
<p style="margin:0; padding:0; float:right; position:relative; right:8px; top:10px">
<?php
	if(isset($_COOKIE['identification_amap'])) { 
		mysql_connect(hote, login, mot_passe_sql); // Connexion à MySQL
		mysql_select_db(base_de_donnees); // Sélection de la base 
		$question="SELECT * FROM amap_generale WHERE id='".$_COOKIE['identification_amap']."'";
		$reponse = mysql_query($question) or die(mysql_error());
		$donnees=mysql_fetch_array($reponse);
		?>
 		<a style="color:#00EEEE; font-weight:bold; position:relative; right:10px; bottom:10px" href="mot_passe_change_2.php"><?php echo $donnees['Prenom']." ".$donnees['Nom']; ?></a>
 
  	<?php 
  		mysql_close();
  	} 
  	else { ?>
  		<a style="color:orange; font-weight:bold; position:relative; right:10px; bottom:10px" href="identification.php">Identifiez vous!!</a>		
  	<?php } ?>

             
    <a href="http://validator.w3.org/check?uri=referer">
		<img style="border:0;width:88px;height:31px"
			src="http://www.w3.org/Icons/valid-xhtml10"
			alt="Valid XHTML 1.0 Strict"/>   
	</a>
    <a href="http://jigsaw.w3.org/css-validator/check/referer">
        <img style="border:0;width:88px;height:31px"
            src="http://jigsaw.w3.org/css-validator/images/vcss"
            alt="CSS Valide !" />
    </a>
	
</p>

<h1>
  <a class="en_tete_amap" href="index.php" title="vers accueil" style="position:relative; left:10px; bottom:10px">
		AMAP &nbsp;
	</a>
	<a class="en_tete_amap" href="index.php" title="vers accueil">
		<img alt="Les gumes" src="images/logo_lesgumes.jpeg" title="vers accueil" />
	</a>
	<a class="en_tete_amap" href="index.php" title="vers accueil" style="position:relative; left:10px; bottom:10px">
	  S<sup>t</sup> Sébastien/Loire
	</a>
</h1>


<div class="separateur"></div>
