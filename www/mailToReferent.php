<?php
// ajoute le prénom du coordinateur de l'amap $amap à $string
// et ajoute dans $mailTo la chaine pour préparer le mail
  function MailToReferent($amap, &$string, &$mailTo)  {
    include("webmaster/define.php");
   
  mysql_connect(hote, login, mot_passe_sql); // Connexion à MySQL
  mysql_select_db(base_de_donnees); // Sélection de la base 
   
  $question= "SELECT mail, prenom, Nom_amap FROM referent JOIN liste_amap ON liste_amap.id_referent=referent.id WHERE Table_amap='".$amap."'";
  $reponse=mysql_query($question) or die(mysql_error()); 
  $donnees=mysql_fetch_array($reponse);
  
  $string .= $donnees['prenom'];
  $string .= ' ('.$donnees['mail'].')';
  $tmp =  'mailto:'.$donnees['mail'].'?subject=[Amap LesGUMES] INFO '.$donnees['Nom_amap'];
  $mailTo .= $tmp;
  mysql_close(); 
  }

?>



