<?php
include ("../_INCLUDE/config_sql.inc.php");
include ("../_INCLUDE/fonctions.inc.php");
include ("../_INCLUDE/constants.inc.php");

//------------------------------------------------------------------------------ CONNEXION SERVEUR PostgreSQL
$db=sql_connect (SQL_base);
if (!$db) fatal_error ("Impossible de se connecter au serveur PostgreSQL ".SQL_server,false);

//------------------------------------------------------------------------------ INIT JAVASCRIPT
?>

<?php
//------------------------------------------------------------------------------ MAIN
html_header ("utf-8","","");

//Récupération des données
// $pgresult=pg_query ($db,sqlConst ("plateforme",$_GET["id"])) or fatal_error ("Erreur pgSQL : ".pg_result_error ($pgresult),false);
// $tab = pg_fetch_all($pgresult);
// $result = $tab[0];
// var_dump($result);



?>
Affaire à suivre
