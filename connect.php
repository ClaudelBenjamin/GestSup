<?php
$serveur="localhost";//nom du serveur
$user="root";//votre nom utilisateur
$password="b#zejEdE!#nhw5c";//mot de passe
$base="maritimegestsup";//nom de la base de donnée
$connexion = mysql_connect($serveur,$user,$password) or die("impossible de se connecter : ". mysql_error());
$db = mysql_select_db($base, $connexion)  or die("impossible de sélectionner la base : ". mysql_error());
@mysql_query("SET SESSION sql_mode=''");
?>