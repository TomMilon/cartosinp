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

//---------------
//-----Récupération des données
$req = sqlConst("jdd",$_GET["id"]);
//-----jdd
$pgresult=pg_query ($db,$req[0]) or fatal_error ("Erreur pgSQL : ".pg_result_error ($pgresult),false);$jdd = pg_fetch_all($pgresult);$jdd = $jdd[0];
//-----organisme
$pgresult=pg_query ($db,$req[1]) or fatal_error ("Erreur pgSQL : ".pg_result_error ($pgresult),false);$org = pg_fetch_all($pgresult);
//-----tool
// $pgresult=pg_query ($db,$req[2]) or fatal_error ("Erreur pgSQL : ".pg_result_error ($pgresult),false);$tool = pg_fetch_all($pgresult);
//-----ptf
// $pgresult=pg_query ($db,$req[3]) or fatal_error ("Erreur pgSQL : ".pg_result_error ($pgresult),false);$ptf = pg_fetch_all($pgresult);


//----- référentiels
// $ref["codeperimetreaction"] = recup_ref("perimetre_action");
// $ref["codetypeorganisme"] = recup_ref("type_organisme");
// $ref["codestatutorganisme"] = recup_ref("statut_organisme");
// $ref["codeniveauadhesion"] = recup_ref("niveau_adhesion");
?>

<!-- FICHE-->
<h2><?php echo $jdd["lib_jdd"];?></h2>
<i> Les informations présentées sur cette page proviennent de l'outil Métadonnées </i><BR><BR>
<b>Identifiant</b> : <?php echo $jdd["id_jdd"];?><BR>
<b>Libellé</b> : <?php echo $jdd["lib_jdd"];?><BR>
<b>description</b> : <?php echo $jdd["description"];?><BR>
<b>Mots-clés</b> : <?php echo $jdd["mots_cles"];?><BR>
<b>Date de première diffusion</b> : <?php echo $jdd["date_premiere_diff"];?><BR>
<b>Date de dernière mise à jour</b> : <?php echo $jdd["date_derniere_maj"];?><BR>
<BR><BR>


<div id="c1" class="ptf">
<b>Liste des organismes</b><BR>
<?php 
if (empty($org)) echo $valeur_non_applicable; else foreach ($org as $unit) echo "<li><a href=\"organisme.php?id=".$unit["id_org"]."\">".$unit["lib_org"]."</a></li>";?>
</div>


<div id="c3" class="outil">
<b>Liste des outils</b><BR>
<?php 
// if (empty($tool)) echo $valeur_non_applicable; else foreach ($tool as $unit) echo "<li><a href=\"outil.php?id=".$unit["id_outil"]."\">".$unit["outil_nom"]."</a></li>";?>
Affaire à suivre
<BR><BR>
</div>


<div id="c2" class="jdd">
<b>Liste des plateformes</b><BR>
<?php 
?>
Affaire à suivre
</div>


