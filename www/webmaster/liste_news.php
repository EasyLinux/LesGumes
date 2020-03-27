<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="fr" >
   <head>
       <title>Liste des news</title>
        <meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
        <style type="text/css">
        h2, th, td, p
        {
            text-align:center;
        }
        table
        {
            border-collapse:collapse;
            border:2px solid black;
            margin:auto;
        }
        th, td
        {
            border:1px solid black;
        }
        </style>
    </head>
    
    <body>
<?php include_once("define.php"); ?>
<h2><a href="rediger_news.php">Ajouter une news</a></h2>
<?php
mysql_connect(hote, login, mot_passe_sql);
mysql_select_db(base_de_donnees);
//-----------------------------------------------------
// V�rification 1 : est-ce qu'on veut poster une news ?
//-----------------------------------------------------
if (isset($_POST['titre']) AND isset($_POST['contenu']))
{
    $titre = addslashes($_POST['titre']);
    $contenu = addslashes($_POST['contenu']);
    // On v�rifie si c'est une modification de news ou pas
    if ($_POST['id_news'] == 0)
    {
        // Ce n'est pas une modification, on cr�e une nouvelle entr�e dans la table
        mysql_query("INSERT INTO news(titre, contenu) VALUES('".$titre."', '".$contenu."')");
    }
    else
    {
        // On prot�ge la variable "id_news" pour �viter une faille SQL
        $_POST['id_news'] = addslashes($_POST['id_news']);
        // C'est une modification, on met juste � jour le titre et le contenu
        mysql_query("UPDATE news SET titre='" . $titre . "', contenu='" . $contenu . "' WHERE id='" . $_POST['id_news'] . "'");
    }
}
 
//--------------------------------------------------------
// V�rification 2 : est-ce qu'on veut supprimer une news ?
//--------------------------------------------------------
if (isset($_GET['supprimer_news'])) // Si on demande de supprimer une news
{
    // Alors on supprime la news correspondante
    // On prot�ge la variable "id_news" pour �viter une faille SQL
//    $_GET['supprimer_news'] = addslashes($_GET['supprimer_news']);
    mysql_query("DELETE FROM news WHERE id='".$_GET['supprimer_news']."'");
}
?>
<table><tr>
<th>Modifier</th>
<th>Supprimer</th>
<th>Titre</th>
<th>Date</th>
</tr>
<?php
$retour = mysql_query('SELECT * FROM news ORDER BY id DESC');
while ($donnees = mysql_fetch_array($retour)) // On fait une boucle pour lister les news
{
?>
<tr>
<td><a href="rediger_news.php?modifier_news=<?php echo $donnees['id']; ?>">Modifier</a></td>
<td><a href="liste_news.php?supprimer_news=<?php echo $donnees['id']; ?>">Supprimer</a></td>
<td><?php echo stripslashes($donnees['titre']); ?></td>
<td><?php echo date('d/m/Y', strtotime($donnees['date'])); ?></td>
</tr>
<?php
} // Fin de la boucle qui liste les news
?>
</table>
<p><a href="../index.php">Accueil</a></p>
</body>
</html>
