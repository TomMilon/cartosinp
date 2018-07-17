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

$json =file_get_contents( $URLAPI_organisme."&q=codeOrganisme:".$_GET["id"]);
$jsondec = json_decode($json, true);
$org = $jsondec["response"]["docs"][0];
?>
<h2><?php echo $org["libelleLong"];?></h2>

<b>Libellé court</b> : <?php echo $org["libelleCourt"];?><BR>
<b>Périmètre d'action</b> : <?php echo $org["codePerimetreAction"];?><BR>
<b>Type Organisme</b> : <?php echo $org["codeTypeOrganisme"];?><BR>
<b>Statut Organisme</b> : <?php echo $org["codeStatutOrganisme"];?><BR>
<b>Adhésion au SINP</b> : <?php echo $org["codeNiveauAdhesion"];?><BR>

<BR><BR>

<b>Liste des plateformes</b><BR>
affaire à suivre<BR><BR>

<b>Liste des outils</b><BR>
affaire à suivre<BR><BR>

<b>Liste des jeux de données</b><BR>
affaire à suivre<BR><BR>

