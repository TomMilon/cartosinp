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
Production des tableaux des différents compartiments
<BR><BR> <b>Sources</b> : <BR>
<li> - dossiers d'habilitations des plateformes régionales
<li> - référentiel organisme
<li> - INPN métadonnées
</div>


<h2>Plateformes...done</h2>
	<?php
		$file = "<table><tbody>";
		$sqlList["plateforme"] = " SELECT '<a href=\"plateforme.php?id='||id_ptf||'\">'||nom_region||'</a>' FROM hab.plateforme ORDER BY nom_region;";
		$result=pg_query ($db,$sqlList["plateforme"]) or fatal_error ("Erreur pgSQL : ".pg_result_error ($result),false);
		while ($row = pg_fetch_row($result))
			$file .= "<tr><td>".$row[0]."</td></tr>";
		$file .= "</tbody></table>";
	file_put_contents("../_DATA/liste_plateformes.html",$file);
	?>

<h2>Organismes...done</h2>
	<?php
	$file = "<table><tbody>";
	$json = file_get_contents($URLAPI_organisme.$limit_json);
	$jsondec = json_decode($json, true);
	foreach ($jsondec["response"]["docs"] as $unit)
		{
		$libelleLong[$unit["codeOrganisme"]] = ucfirst(strtolower($unit["libelleLong"]));
		$libelleCourt[$unit["codeOrganisme"]] = ucfirst(strtolower($unit["libelleCourt"]));
	}
	asort($libelleCourt);asort($libelleLong);
	for ($i = 1; $i < count($libelleCourt) ; $i++)
		{
		$file .= "<tr><td><a href=\"organisme.php?id=".key($libelleLong)."\">".current($libelleLong)."<a></td></tr>";
		next($libelleLong);
		}
		$file .= "</tbody></table>";
		file_put_contents("../_DATA/liste_organismes.html",$file);
?>

<h2>Outils...done</h2>
	<?php
	$file = "<table><tbody>";
	$sqlList["outil"] = "SELECT '<a href=\"outil.php?id='||id_outil||'\">'||outil_nom||'</a>' FROM hab.outil ORDER BY outil_nom;";
	$result=pg_query ($db,$sqlList["outil"]) or fatal_error ("Erreur pgSQL : ".pg_result_error ($result),false);
	while ($row = pg_fetch_row($result))
			$file .= "<tr><td>".$row[0]."</td></tr>";
		$file .= "</tbody></table>";
	file_put_contents("../_DATA/liste_outils.html",$file);
	?>

<h2>Jeux de données...done</h2>
	<?php
	$file = "<table><tbody>";
	$sqlList["jdd"] = "SELECT '<a href=\"jdd.php?id='||id_jdd||'\">'||lib_jdd||'</a>' FROM nomenc.ref_jdd ORDER BY lib_jdd;";
	$result=pg_query ($db,$sqlList["jdd"]) or fatal_error ("Erreur pgSQL : ".pg_result_error ($result),false);
	while ($row = pg_fetch_row($result))
		$file .= "<tr><td>".$row[0]."</td></tr>";
	$file .= "</tbody></table>";
	file_put_contents("../_DATA/liste_jdds.html",$file);
	?>

<h2>Les questions...done</h2>
	<?php
	$file = "<table><tbody>";
	$sqlList["question"] = "SELECT '<a href=\"question.php?id='||lib_nmc||'\">'||val_nmc||'</a>' FROM nomenc.carto_question ORDER BY lib_nmc::integer;";
	$result=pg_query ($db,$sqlList["question"]) or fatal_error ("Erreur pgSQL : ".pg_result_error ($result),false);
	while ($row = pg_fetch_row($result))
		$file .= "<tr><td>".$row[0]."</td></tr>";
	$file .= "</tbody></table>";
	file_put_contents("../_DATA/liste_questions.html",$file);
	?>
