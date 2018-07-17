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

//-----Récupération des données
$req = sqlConst("plateforme",$_GET["id"]);
//plateformes
$pgresult=pg_query ($db,$req[0]) or fatal_error ("Erreur pgSQL : ".pg_result_error ($pgresult),false);$tab = pg_fetch_all($pgresult);
$ptf = $tab[0];
//organismes
$pgresult=pg_query ($db,$req[1]) or fatal_error ("Erreur pgSQL : ".pg_result_error ($pgresult),false);$org = pg_fetch_all($pgresult);
//tool
$pgresult=pg_query ($db,$req[2]) or fatal_error ("Erreur pgSQL : ".pg_result_error ($pgresult),false);$tool = pg_fetch_all($pgresult);

?>
<h2><?php echo $ptf["nom_region"];?></h2>
<b><a href="./question.php?id=1">Statut de la plateforme</a></b> : <?php echo $ptf["hab_decision"];?><BR><BR>
<b><a href="./question.php?id=2">Dynamique de la plateforme</a></b> : <?php echo $ptf["dynamique"];?><BR><BR>
<b><a href="./question.php?id=3">Plateforme pérène?</a></b> : <?php echo $ptf["perenne"];?><BR>
<?php echo $ptf["perenne_desc"];?><BR><BR>
<b><a href="./question.php?id=4">Charte SINP?</a></b> : <?php echo $ptf["charte"];?><BR>
<?php echo $ptf["charte_desc"];?><BR>
<?php echo $ptf["charte_pj"];?><BR><BR>
<b><a href="./question.php?id=5">Standard de données régional SINP</a></b> : <?php echo $ptf["standard"];?><BR><BR>
<b><a href="./question.php?id=6">Échange avec la plateforme nationale?</a></b> : <?php echo $ptf["echange"];?><BR>
<?php echo $ptf["echange_desc"];?><BR><BR>
<b><a href="./question.php?id=7">validation scientifique des données?</a></b> : <?php echo $ptf["validation"];?><BR>
<?php echo $ptf["validation_desc"];?><BR><BR>
<b><a href="./question.php?id=8">Référentiel de sensibilité</a></b> : <?php echo $ptf["ref_sensibilité"];?><BR><BR>
<b>liste des jeux de données échangés</b> travaux en cours
<BR><BR>
<b>Liste des outils</b><BR>
<?php foreach ($tool as $unit) echo "<li><a href=\"outil.php?id=".$unit["id_outil"]."\">".$unit["outil_nom"]."</a></li>";?>
<b>Liste des organismes</b><BR>
<?php foreach ($org as $unit) echo "<li><a href=\"organisme.php?id=".$unit["nom"]."\">".$unit["nom"]." (".$unit["role"].")</a></li>";?>

