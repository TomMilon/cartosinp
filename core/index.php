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

?>
<div id=\"en-tete\" style="text-align: center;">
Ce site propose une expérimentation en terme de visualisation de la cartographie du SINP.
<BR><BR> <b>Sources</b> : <BR>
<li> - dossiers d'habilitations des plateformes régionales
<li> - référentiel organisme
<li> - INPN métadonnées
</div>


<div id="c1" class="ptf">
	<h2>Plateformes</h2>
	<table><tbody>
	<?php		
		$sqlList["plateforme"] = " SELECT '<a href=\"plateforme.php?id='||id_ptf||'\">'||nom_region||'</a>' FROM hab.plateforme ORDER BY nom_region;";
		$result=pg_query ($db,$sqlList["plateforme"]) or fatal_error ("Erreur pgSQL : ".pg_result_error ($result),false);
		while ($row = pg_fetch_row($result))
			echo "<tr><td>".$row[0]."</td></tr>";
	?>
	</tbody></table>
</div>

<div id="c2" class="organisme">
	<h2>Organismes</h2>
	<table><tbody>
	<?php
	// Version API
	// $json = file_get_contents($URLAPI_organisme.$limit_json);
	// $jsondec = json_decode($json, true);
	// foreach ($jsondec["response"]["docs"] as $unit)
		// {
		// $libelleLong[$unit["codeOrganisme"]] = ucfirst(strtolower($unit["libelleLong"]));
		// $libelleCourt[$unit["codeOrganisme"]] = ucfirst(strtolower($unit["libelleCourt"]));
	// }
	// asort($libelleCourt);asort($libelleLong);
	// for ($i = 1; $i < count($libelleCourt) ; $i++)
		// {
		// echo "<tr><td><a href=\"organisme.php?id=".key($libelleLong)."\">".current($libelleLong)."<a><p hidden>".current($libelleCourt)."</p></td></tr>";
		// next($libelleLong);next($libelleCourt);
		// }
		
	// Version BDD
	$sqlList["organisme"] = " SELECT '<a href=\"organisme.php?id='||codeorganisme||'\">'||initcap(libellelong)||'</a><p hidden>'||libellecourt||'</p>' FROM nomenc.ref_org ORDER BY libellelong;";
	$result=pg_query ($db,$sqlList["organisme"]) or fatal_error ("Erreur pgSQL : ".pg_result_error ($result),false);
	while ($row = pg_fetch_row($result))
		echo "<tr><td>".$row[0]."</td></tr>";
	?>
</tbody></table>
</div>

	
<div id="c3" class="outil">
	<h2>Outils</h2>
<table><tbody>
	<?php 
	$sqlList["outil"] = "SELECT '<a href=\"outil.php?id='||id_outil||'\">'||outil_nom||'</a>' FROM hab.outil ORDER BY outil_nom;";
	$result=pg_query ($db,$sqlList["outil"]) or fatal_error ("Erreur pgSQL : ".pg_result_error ($result),false);
	while ($row = pg_fetch_row($result))
			echo "<tr><td>".$row[0]."</td></tr>";
?>
</tbody></table>
</div>


<div id="c4" class="jdd">
	<h2>Jeux de données</h2>
<table><tbody>
	<?php
	$sqlList["jdd"] = "SELECT '<a href=\"jdd.php?id='||id_sinp_jdd||'\">'||lib_jdd||'</a>' FROM nomenc.ref_jdd ORDER BY lib_jdd;";
	$result=pg_query ($db,$sqlList["jdd"]) or fatal_error ("Erreur pgSQL : ".pg_result_error ($result),false);
	while ($row = pg_fetch_row($result))
		echo "<tr><td>".$row[0]."</td></tr>";
	?>
	</tbody></table>
</div>

<BR><BR>
<BR><BR>
<BR><BR>
<div id="question">
	<h2>Les questions</h2>
<table><tbody>
	<?php
	$sqlList["question"] = "SELECT '<a href=\"question.php?id='||lib_nmc||'\">'||val_nmc||'</a>' FROM nomenc.carto_question ORDER BY lib_nmc::integer;";
	$result=pg_query ($db,$sqlList["question"]) or fatal_error ("Erreur pgSQL : ".pg_result_error ($result),false);
	while ($row = pg_fetch_row($result))
		echo "<tr><td>".$row[0]."</td></tr>";
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