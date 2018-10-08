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

$nom_ref = array('type_donnees','obj_jdd');
foreach ($nom_ref as $unit)
{
	$sql = "SELECT lib_nmc, id_nmc FROM nomenc.$unit";
	$pgresult = pg_query ($db,$sql) or fatal_error ("Erreur pgSQL : ".pg_result_error ($pgresult),false);$ref = pg_fetch_all($pgresult);
	foreach ($ref as $unit2) $referentiel[$unit][$unit2["id_nmc"]] = $unit2["lib_nmc"];
}

$date_crea = new DateTime($jdd["datecreation"]);
$date_modif = new DateTime($jdd["daterevision"]);

?>

<!-- FICHE-->
<h2><?php echo "<div class=\"jdd\">Jeu de données : ".$jdd["lib_jdd"]."</div>";?></h2>

<div class="sources"><b>Précaution</b> : Les informations présentées sur cette page proviennent de <b>l'application métadonnées</b>. La liste des jeux de données n'est pas complète. Dans le cadre de cette expérimentation, un rattachement des jeux de données aux organismes a été testé <b>dans le cas de correspondant exacte</b> entre le nom de l'organisme dans l'application organisme et le nom de l'organisme dans le JDD. Tous les jeux de données pour lesquels les organismes contribuent <b>ne sont donc PAS décrits</b> sur cette page. Ce travail de consolidation entre organisme et jeux de données est, par ailleur, en cours et pourra, à terme, alimenter la cartographie. Il en est de même concernant le lien entre plateforme et jeux de données.</div>


<div class="fiche">
	<b>Fiche créée le </b> <?php echo date_format($date_crea, 'd/m/Y à H:i:s');
	if ($date_crea < $date_modif) echo " - modidiée le ".date_format($date_modif, 'd/m/Y à H:i:s')." (dernière modification)";?><BR>
	<b>Lien vers la fiche métadonnées sur l'INPN </b> : <?php echo "<a href=\"".$URL_appli_metadonnee.$jdd["id_jdd"]."\">Fiche ".$jdd["id_jdd"]."</a>";?><BR>
	<b>Identifiant SINP</b> : <?php echo $jdd["id_sinp_jdd"];?><BR>
	<b>Identifiant INPN</b> : <?php echo $jdd["cd_jdd"];?><BR>
	<b>Libellé court</b> : <?php echo str_replace("''","'",$jdd["libellecourt"]);?><BR>
	<b>Libellé long</b> : <?php echo $jdd["lib_jdd"];?><BR>
	<BR>

	<b> Objectif Jdd</b> : <?php if(!empty($jdd["objectifjdd"])) echo  $referentiel['obj_jdd'][$jdd["objectifjdd"]];?><BR>
	<b> TypeDonnees </b> : <?php if(!empty($jdd["typedonnees"])) echo $referentiel['type_donnees'][$jdd["typedonnees"]];?><BR>
	<b> Protocoles</b> : <?php echo $jdd["protocoles"];?><BR>
	<b>Description</b> : <?php echo str_replace("''","'",$jdd["description"]);?><BR>
	<BR>

	<b> Domaine Marin</b> : <?php echo $jdd["domainemarin"];?><BR>
	<b> Domaine Terrestre</b> : <?php echo $jdd["domaineterrestre"];?><BR>
	<b> Territoire</b> : <?php echo $jdd["territoire"];?><BR>
	<BR>

	<b> Lien vers les données sur le requêteur</b> : à venir <BR>
</div>


<div id="c1" class="organisme">
<b>Liste des organismes</b><BR>
<table><tbody>
<?php 
if (empty($org)) echo $valeur_non_applicable; 
else foreach ($org as $unit) {
	if ($unit["id_org"] == 'N/A' OR empty($unit["id_org"])) echo "<tr><td>".$unit["lib_org"]."  (".$unit["typ_org"].")</td></tr>"; 
	else echo "<tr><td><a href=\"organisme.php?id=".$unit["id_org"]."\">".$unit["lib_org"]."  (".$unit["typ_org"].") </a></td></tr>";
}
?>
</tbody></table>
</div>


<div id="c3" class="outil">
<b>Liste des outils</b><BR>
<table><tbody>
<?php 
// if (empty($tool)) echo $valeur_non_applicable; else foreach ($tool as $unit) echo "<tr><td><a href=\"outil.php?id=".$unit["id"]."\">".$unit["lib"]."</a></td></tr>";
if (empty($tool)) echo $valeur_non_applicable; else echo "<tr><td><a href=\"outil.php?id=".$tool["id"]."\">".$tool["lib"]."</a></td></tr>";
?>
</tbody></table>
</div>


<div id="c2" class="ptf">
<b>Liste des plateformes</b><BR>
<table><tbody>
<?php 
if (empty($ptf)) echo $valeur_non_applicable; else echo "<tr><td><a href=\"plateforme.php?id=".$ptf["id"]."\">".$ptf["lib"]."</a></td></tr>";
?>
</tbody></table>
</div>


<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.3/jquery.min.js"></script>
<!---sources = http://sunnywalker.github.io/jQuery.FilterTable/ -->
<script src="../_INCLUDE/js/jquery.filtertable.min.js"></script>
<script>
$(document).ready(function() {
	$('table').filterTable(	
	); // apply filterTable to all tables on this page
});
</script>
