<?php
  include("webmaster/define.php");
  
  // mise à jour de la commande de chèvre pour l'amapien d'id = $id
  // Les valeurs de champs doivent être mis dans le formulaire dans le même ordre que les champs de la table
  
  mysql_connect(hote, login, mot_passe_sql); // Connexion à MySQL
  mysql_select_db(base_de_donnees); // Sélection de la base 

  $id = $_POST['ID'];

  // champs de la table  amap_chevre_cde  dans l'ordre de la table
  $fields = mysql_list_fields (base_de_donnees, 'amap_chevre_cde');
  $firstField = 5;  // juste les produits sans les premiers champs de la table id, Nom, Prénom, Unite et   DateModif
	
  $question="SELECT * FROM amap_chevre_cde";
	$reponse = mysql_query($question) or die(mysql_error());
  $nbFields =  mysql_num_fields($reponse);  // nombre de champs de la table   
    
  $question="UPDATE amap_chevre_cde SET " ;

  for($i=$firstField; $i< $nbFields; $i++) {
    $str = mysql_field_name($fields,$i);
    $question .= $str;
    $question .= "='".$_POST['Elt'.($i-4).'ID']."', ";
  }
  $question.=" Date_modif='".date("Y-m-d",time())."' WHERE id='".$id."' ";  
  $reponse=mysql_query($question) ;
  mysql_close();
  $page="Location: index.php";
  header($page);   
?> 