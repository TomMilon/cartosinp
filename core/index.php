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
Ce site propose une expérimentation en terme de visualisation de la cartographie du SINP<BR><BR>
<input type="search"  size="75" placeholder="Filtrer les résultats" name="champ recherche" onkeyup="filtre()">
<BR><BR>
</div>


<div id="c1" class="ptf">
	<h2>Plateformes</h2>
	<?php
		$sqlList["plateforme"] = " SELECT '<a href=\"plateforme.php?id='||id_ptf||'\">'||nom_region||'</a>' FROM hab.plateforme ORDER BY nom_region;";
		$result=pg_query ($db,$sqlList["plateforme"]) or fatal_error ("Erreur pgSQL : ".pg_result_error ($result),false);
		while ($row = pg_fetch_row($result))
			echo "<li>".$row[0]."</li>";
	?>
</div>

<div id="c3" class="outil">
	<h2>Outils</h2>
	<?php
	$sqlList["outil"] = "SELECT '<a href=\"outil.php?id='||id_outil||'\">'||outil_nom||'</a>' FROM hab.outil ORDER BY outil_nom;";
	$result=pg_query ($db,$sqlList["outil"]) or fatal_error ("Erreur pgSQL : ".pg_result_error ($result),false);
	while ($row = pg_fetch_row($result))
		echo "<li>".$row[0]."</li>";
		?>
</div>

<div id="c2" class="organisme">
	<h2>Organismes</h2>
	<?php
	$json = file_get_contents($URLAPI_organisme.$limit_json);
	$jsondec = json_decode($json, true);
	foreach ($jsondec["response"]["docs"] as $unit)
		{
		$libelleLong[$unit["codeOrganisme"]] = ucfirst(strtolower($unit["libelleLong"]));
		$libelleCourt[$unit["codeOrganisme"]] = ucfirst(strtolower($unit["libelleCourt"]));
	}
	asort($libelleCourt);asort($libelleLong);
	for ($i = 1; $i < 18; $i++)
	// for ($i = 1; $i < 800; $i++)
		{
		echo "<li><a href=\"organisme.php?id=".key($libelleLong)."\">".current($libelleLong)."<a></li>";
		next($libelleLong);
		}
	echo "<li>...</li>";
		?>
</div>


<BR><BR>
<div id="question">
	<h2>Les questions</h2>
	<?php
	$sqlList["question"] = "SELECT '<a href=\"question.php?id='||lib_nmc||'\">'||val_nmc||'</a>' FROM nomenc.carto_question ORDER BY lib_nmc::integer;";
	$result=pg_query ($db,$sqlList["question"]) or fatal_error ("Erreur pgSQL : ".pg_result_error ($result),false);
	while ($row = pg_fetch_row($result))
		echo "<li>".$row[0]."</li>";
		?>
</div>