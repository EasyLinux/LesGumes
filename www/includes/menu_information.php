<?php include_once("includes/fileTools.php");?>
	<h3 class="titre_menu_gauche"><a href="">Espace INFOs</a></h3>
	<ul class="h1" id="menuNonDeroulant">
    <li class="h1_menu"><a class="h1" href="">Téléchargement</a>
		<ul class="h2">
			<li class="h2"><a href='<?php echo planningFilename();?>' onclick="window.open(this.href); return false;">Planning livraisons</a></li>
			<li class="h2"><a href="includes/RGPD.pdf">RGPD</a></li>
			<li class="h2"><a class="h2" href="telechargements.php">Documents</a></li>
			<!--li style="color:#00EEEE"><a href="sondage.php">Sondage lapin</a></li-->
		</ul>
		</li>
	</ul>