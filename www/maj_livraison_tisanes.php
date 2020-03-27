<?php
	include_once("webmaster/define.php");

	// mise à jour de la commande de tisanes pour l'amapien d'id = $id et pour la date de commande $dateDerniereCommande
	// Les valeurs de champs doivent être mis dans le formulaire dans le même ordre que les champs de la table

	mysql_connect(hote, login, mot_passe_sql); // Connexion à MySQL
	mysql_select_db(base_de_donnees); // Sélection de la base 

	$id = $_POST['ID'];
	$dateDerniereCommande = $_POST['dateDerniereCommande'];

	// echo("ID : $id et date : $dateDerniereCommande <BR />");
	// champs de la table amap_tisanes_cde  dans l'ordre de la table
	$fields = mysql_list_fields (base_de_donnees, 'amap_tisanes_cde');

	$questionChoix="SELECT * FROM amap_tisanes_cde where Id_Personne=$id and Date='$dateDerniereCommande'";
	
	$questionUpdate="UPDATE amap_tisanes_cde SET " ;

	// dans les champs cachés, on a les informations :
	// name (=Indice) -> value (=Id_Produit)
	$reponse = mysql_query($questionChoix) or die(mysql_error());
	while ($choix=mysql_fetch_array($reponse)) { 
		$question = $questionUpdate;
		//echo "Indice : ".$choix["Indice"]." Id_Produit : ".$_POST[$choix["Indice"]];
		$question.= "Id_Produit=	".$_POST[$choix["Indice"]];
		$question.=", Date_modif='".date("Y-m-d",time())."' WHERE Id_Personne='".$id."' AND Indice=".$choix["Indice"];  
		//echo("Question : ".$question."<BR />");
		$reponse2=mysql_query($question) ;
		//echo("Réponse à l'update: ".$reponse2."<BR />");
	} 
  
	mysql_close();
	$page="Location: index.php";
	header($page);   
?> 
