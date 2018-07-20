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
// ---- outils
$req = sqlConst("outil",$_GET["id"]);
$pgresult=pg_query ($db,$req[0]) or fatal_error ("Erreur pgSQL : ".pg_result_error ($pgresult),false);$tab = pg_fetch_all($pgresult);
$result = $tab[0];
// ------ fonction
$pgresult=pg_query ($db,$req[1]) or fatal_error ("Erreur pgSQL : ".pg_result_error ($pgresult),false);$list = pg_fetch_all($pgresult);
foreach ($list as $unit) $fct_outil[$unit["lib_nmc"]] = $unit["val_nmc"];
// var_dump($list);
//-----plateformes
// $pgresult=pg_query ($db,$req[0]) or fatal_error ("Erreur pgSQL : ".pg_result_error ($pgresult),false);$ptf = pg_fetch_all($pgresult);
//-----organismes
$pgresult=pg_query ($db,$req[2]) or fatal_error ("Erreur pgSQL : ".pg_result_error ($pgresult),false);$org = pg_fetch_all($pgresult);


?>
<h2><?php echo $result["outil_nom"];?></h2>
<b><a href="./question.php?id=x">Dynamique de l'outil</a></b> : <?php echo $result["outil_dynq"];?><BR>
<b>URL l'outil</a></b> : <a href="<?php echo $result["outil_url"];?>"><?php echo $result["outil_url"];?></a><BR>
<b>Organisme </b> : <?php echo $result["outil_org_nom"];?><BR>
<?php echo $result["outil_desc"];?><BR>
<?php 
for ($i = 1; $i <= 19; $i++) {
    echo "<li>".$fct_outil["fct_outil_".$i]." = ".$result["fct_outil_".$i]."<BR></li>";
	}
?>
<BR><BR>

<!-----LIEN avec les autres fiches------>
<div id="c1" class="ptf">
<b>Liste des plateformes</b><BR>
<?php echo "<li><a href=\"plateforme.php?id=".$result["id_ptf"]."\">".$result["nom_region"]."</a></li>";?>
</div>

<div id="c3" class="organisme">
<b>Liste des organismes</b><BR>
<?php 
if (empty($org)) echo $valeur_non_applicable; else foreach ($org as $unit) echo "<li><a href=\"organisme.php?id=".$unit["outil_org_id"]."\">".$unit["outil_org_nom"]."</a></li>";
?>
</div>

<div id="c2" class="jdd">
<b>Liste des jeux de données</b><BR>
<?php 
?>
</div>




