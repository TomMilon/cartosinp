<?php
//------------------------------------------------------------------------------//
//  /index.php                                                                  //
//                                                                              //
//  Version 1.00  13/07/12 - OlGa (CBNMED)                                      //
//------------------------------------------------------------------------------//

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
$pgresult=pg_query ($db,sqlConst ("plateforme",$_GET["id"])) or fatal_error ("Erreur pgSQL : ".pg_result_error ($pgresult),false);
$tab = pg_fetch_all($pgresult);
$result = $tab[0];
// var_dump($result);

?>
<h2><?php echo $result["Nom de la région"];?></h2>
<b><a href="./question.php?id=1">Statut de la plateforme</a></b> : <?php echo $result["Statut de la plateforme"];?><BR><BR>
<b><a href="./question.php?id=2">Dynamique de la plateforme</a></b> : <?php echo $result["Dynamique de la plateforme"];?><BR><BR>
<b><a href="./question.php?id=3">Plateforme pérène?</a></b> : <?php echo $result["Plateforme pérène?"];?><BR>
<?php echo $result["compléments sur la pérénité"];?><BR><BR>
<b><a href="./question.php?id=4">Charte SINP?</a></b> : <?php echo $result["Charte SINP?"];?><BR>
<?php echo $result["compléments sur la charte"];?><BR>
<?php echo $result["lien vers la charte"];?><BR><BR>
<b><a href="./question.php?id=5">Standard de données régional SINP</a></b> : <?php echo $result["Standard de données régional SINP"];?><BR><BR>
<b><a href="./question.php?id=6">Échange avec la plateforme nationale?</a></b> : <?php echo $result["Échange avec la plateforme nationale?"];?><BR>
<?php echo $result["compléments sur les échanges"];?><BR><BR>
<b><a href="./question.php?id=7">validation scientifique des données?</a></b> : <?php echo $result["validation scientifique des données?"];?><BR>
<?php echo $result["compléments sur la validation scientifique"];?><BR><BR>
<b><a href="./question.php?id=8">Référentiel de sensibilité</a></b> : <?php echo $result["Référentiel de sensibilité"];?><BR><BR>
<b>liste des jeux de données échangés</b> travaux en cours
<BR><BR>
<b>Liste des outils</b><BR>
<b><a href="./outil.php?id=8"><?php echo $result["liste des outils"];?></a></b> : <BR><BR>
<b>liste des organismes</b><BR>
<b><a href="./organisme.php?id=8"><?php echo $result["liste des organismes"];?></a></b> : <BR><BR>



