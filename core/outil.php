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
//-----jdd
$pgresult=pg_query ($db,$req[3]) or fatal_error ("Erreur pgSQL : ".pg_result_error ($pgresult),false);$jdd = pg_fetch_all($pgresult);

?>

<!-- FICHE-->
<h2><?php echo "<div class=\"outil\">Outil : ".$result["outil_nom"]."</div>";?></h2>

<div class="sources"><b>Précaution</b> :Les informations présentées sur cette page proviennent des <b>dossiers d'habilitations mis à disposition par les correspondants SINP régionaux</b>. Seules les informations disponibles dans ces dossiers ont été repris ici. Dans le cadre de l'expérimentation, <b>seulement 2 dossiers non validés ont été utilisés dans la cartographie</b> : le dossier PACA et Centre Val de Loire. Le lien entre Cardobs et les jeux de données a été possible grace à l'API métadonnées.</div>


<div class="fiche">
	<b><a href="./question.php?id=x">Dynamique de l'outil</a></b> : <?php echo $result["outil_dynq"];?><BR>
	<b>URL l'outil</a></b> : <a href="<?php echo $result["outil_url"];?>"><?php echo $result["outil_url"];?></a><BR>
	<b>Organisme </b> : <?php echo $result["outil_org_nom"];?><BR>
	<?php echo $result["outil_desc"];?>
	<BR>
	<table style="height : 400px;">
	<?php 
	for ($i = 1; $i <= 19; $i++) {
		// echo "<li>".$fct_outil["fct_outil_".$i]." = ".$result["fct_outil_".$i]."<BR></li>";
			if ($result["fct_outil_".$i] == "ok") $picto = "observation_vert.png";
			if ($result["fct_outil_".$i] == "part") $picto = "observation_orange.png";
			if ($result["fct_outil_".$i] == "na") $picto = "observation_rouge.png";
		   echo "<TR><TD>".$fct_outil["fct_outil_".$i]."</TD><TD><img src=\"../_GRAPH/icones/$picto\"></TD></TR>";
		}

	?>
	</table>
</div>

<!-----LIEN avec les autres fiches------>
<div id="c1" class="ptf">
<b>Liste des plateformes</b><BR>
<table><tbody>
<?php 
echo "<tr><td><a href=\"plateforme.php?id=".$result["id_ptf"]."\">".$result["nom_region"]."</a></td></tr>";
?>
</tbody></table>
</div>

<div id="c3" class="organisme">
<b>Liste des organismes</b><BR>
<table><tbody>
<?php 
if (empty($org)) echo $valeur_non_applicable; else foreach ($org as $unit) echo "<tr><td><a href=\"organisme.php?id=".$unit["outil_org_id"]."\">".$unit["outil_org_nom"]."</a></td></tr>";
?>
</tbody></table>
</div>

<div id="c2" class="jdd">
<b>Liste des jeux de données</b><BR>
<table><tbody>
<?php 
if (empty($jdd)) echo $valeur_non_applicable; else foreach ($jdd as $unit) echo "<tr><td><a href=\"jdd.php?id=".$unit["id_sinp_jdd"]."\">".$unit["lib_jdd"]."</a></td></tr>";?>
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



