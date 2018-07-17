<?php
//------------------------------------------------------------------------------//
//  /index.php                                                                  //
//                                                                              //
//  Version 1.00  13/07/12 - OlGa (CBNMED)                                      //
//------------------------------------------------------------------------------//

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

// ------------Introduction
Echo "Ce site propose une expérimentation en terme de visualisation de la cartographie du SINP";

//------------------------------------------------------------------------Test première entrée
// ---Liste des plateformes
$sqlList["plateforme"] = "
SELECT '<a href=\"plateforme.php?id='||id_ptf||'\">'||nom_region||'</a>'
FROM hab.plateforme 
ORDER BY nom_region
;";

Echo "<h2>Les plateforme régionales du SINP</h2>";
$result=pg_query ($db,$sqlList["plateforme"]) or fatal_error ("Erreur pgSQL : ".pg_result_error ($result),false);
while ($row = pg_fetch_row($result))
	echo "<li>".$row[0]."</li>";

// ---Liste des outils
$sqlList["outil"] = "
SELECT '<a href=\"outil.php?id='||id_outil||'\">'||outil_nom||'</a>'
FROM hab.outil 
ORDER BY outil_nom
;";

Echo "<h2>Les outils</h2>";
$result=pg_query ($db,$sqlList["outil"]) or fatal_error ("Erreur pgSQL : ".pg_result_error ($result),false);
while ($row = pg_fetch_row($result))
	echo "<li>".$row[0]."</li>";

// ---Liste des questions
$sqlList["question"] = "
SELECT '<a href=\"question.php?id='||lib_nmc||'\">'||val_nmc||'</a>'
FROM nomenc.carto_question
ORDER BY lib_nmc
;";

Echo "<h2>Les questions</h2>";
$result=pg_query ($db,$sqlList["question"]) or fatal_error ("Erreur pgSQL : ".pg_result_error ($result),false);
while ($row = pg_fetch_row($result))
	echo "<li>".$row[0]."</li>";

?>

