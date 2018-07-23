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
$req = sqlConst("plateforme",$_GET["id"]);
//-----plateformes
$pgresult=pg_query ($db,$req[0]) or fatal_error ("Erreur pgSQL : ".pg_result_error ($pgresult),false);$tab = pg_fetch_all($pgresult);
$ptf = $tab[0];
if ($ptf["hab_decision"] == "Processus non lancé") $ptf["dynamique"] =  $ptf["perenne"] =  $ptf["perenne_desc"] =  $ptf["charte"] =  $ptf["charte_desc"] = $ptf["charte_pj"] =  $ptf["standard"] =  $ptf["echange"] =  $ptf["echange_desc"] =  $ptf["validation"] = $ptf["validation_desc"] = $ptf["ref_sensibilité"] = $valeur_non_renseigne;
//-----organismes
$list_org = array();
$pgresult=pg_query ($db,$req[1]) or fatal_error ("Erreur pgSQL : ".pg_result_error ($pgresult),false);$org = pg_fetch_all($pgresult);
//-----tool
$pgresult=pg_query ($db,$req[2]) or fatal_error ("Erreur pgSQL : ".pg_result_error ($pgresult),false);$tool = pg_fetch_all($pgresult);

//----- référentiels
$sql = "SELECT lib_nmc, val_nmc FROM nomenc.role_org";
$pgresult = pg_query ($db,$sql) or fatal_error ("Erreur pgSQL : ".pg_result_error ($pgresult),false);$role_all = pg_fetch_all($pgresult);
foreach ($role_all as $unit) {$ref_role[$unit["lib_nmc"]] = $unit["val_nmc"];}


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
<b><a href="./question.php?id=8">Référentiel de sensibilité</a></b> : <?php echo $ptf["ref_sensibilité"];?>

<BR><BR>


<!-----LIEN avec les autres fiches------>
<div id="c1" class="jdd">
<b>Liste des jeux de données</b><BR>

</div>

<div id="c3" class="outil">
<b>Liste des outils</b><BR>
<?php 
if (empty($tool)) echo $valeur_non_renseigne; else foreach ($tool as $unit) echo "<li><a href=\"outil.php?id=".$unit["id_outil"]."\">".$unit["outil_nom"]."</a></li>";?>
<BR><BR>
</div>

<div id="c2" class="organisme">
<b>Liste des organismes</b><BR>
<?php 
if (empty($org)) echo $valeur_non_renseigne; else
foreach ($org as $unit) 
	{
	if ($unit["id"]!= null) 
		{
		$json_nom = json_decode(file_get_contents($URLAPI_organisme."&q=codeOrganisme:".$unit["id"]),true);
		$nom = ucfirst(strtolower($json_nom["response"]["docs"][0]["libelleLong"]));
		echo "<li><a href=\"organisme.php?id=".$unit["id"]."\">".$nom." (".$ref_role[$unit["role"]].")</a></li>";
		if (!in_array($unit["id"],$list_org)) array_push($list_org,$unit["id"]);
		}
	else echo "<li>".$unit["nom"]." (".$ref_role[$unit["role"]].")</a></li>";
	}
?>
</div>

<?php var_dump($list_org);?>
<script>
for (var i = 0; i < 9; i++) {
  str = str + i;
}

