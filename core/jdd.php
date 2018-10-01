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
if (!empty($jdd["id_outil"])) {$tool["id"] = $jdd["id_outil"];$tool["lib"]= $jdd["lib_outil_origine"];}
//-----ptf
if (!empty($jdd["id_ptf"])) {$ptf["id"] = $jdd["id_ptf"];$ptf["lib"]= $jdd["nom_region"];}


//----- référentiels
// $ref["codeperimetreaction"] = recup_ref("perimetre_action");
// $ref["codetypeorganisme"] = recup_ref("type_organisme");
// $ref["codestatutorganisme"] = recup_ref("statut_organisme");
// $ref["codeniveauadhesion"] = recup_ref("niveau_adhesion");
?>

<!-- FICHE-->
<h2><?php echo "<div class=\"jdd\">Jeu de données : ".$jdd["lib_jdd"]."</div>";?></h2>
<i> Les informations présentées sur cette page proviennent de l'outil Métadonnées </i><BR><BR>
<b>Identifiant base métadonnées</b> : <?php echo $jdd["id_jdd"];?><BR>
<b>Identifiant SINP</b> : <?php echo $jdd["id_sinp_jdd"];?><BR>
<b>Identifiant INPN</b> : <?php echo $jdd["cd_jdd"];?><BR>
<b>Libellé</b> : <?php echo $jdd["lib_jdd"];?><BR>
<b>URL Charte</b> : <?php echo $jdd["url_charte"];?><BR>
<b>Floutage de la données source</b> : <?php echo $jdd["floutage_ds"];?><BR>
<BR><BR>


<div id="c1" class="organisme">
<b>Liste des organismes</b><BR>
<?php 
if (empty($org)) echo $valeur_non_applicable; else foreach ($org as $unit) echo "<li><a href=\"organisme.php?id=".$unit["id_org"]."\">".$unit["lib_org"]."</a></li>";?>
</div>


<div id="c3" class="outil">
<b>Liste des outils</b><BR>
<?php 
if (empty($tool)) echo $valeur_non_applicable; else foreach ($tool as $unit) echo "<li><a href=\"outil.php?id=".$unit["id"]."\">".$unit["lib"]."</a></li>";?>
<BR><BR>
</div>


<div id="c2" class="ptf">
<b>Liste des plateformes</b><BR>
<?php 
if (empty($ptf)) echo $valeur_non_applicable; else echo "<li><a href=\"plateforme.php?id=".$ptf["id"]."\">".$ptf["lib"]."</a></li>";
?>
</div>


