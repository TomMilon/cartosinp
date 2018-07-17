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
Echo "<h1>Cartographie du SINP - expérimentation</h1>";
Echo "<BR>";
Echo "Ce site propose une expérimentation en terme de visualisation de la cartographie du SINP";

//------------------------------------------------------------------------Test première entrée
// ---Liste des plateformes
$sqlList["plateforme"] = "
SELECT '<a href=\"plateforme.php?id='||id_ptf||'\">'||nom_region||'</a>'
FROM hab.plateforme 
ORDER BY nom_region
;
";

Echo "<h1>Les plateforme régionales du SINP</h1>";
$result=pg_query ($db,$sqlList["plateforme"]) or fatal_error ("Erreur pgSQL : ".pg_result_error ($result),false);
while ($row = pg_fetch_row($result))
	echo $row[0]."<BR>";

// ---Liste des outils
$sqlList["outil"] = "
SELECT '<a href=\"outil.php?id='||id_outil||'\">'||outil_nom||'</a>'
FROM hab.outil 
ORDER BY outil_nom
;
";

Echo "<h1>Les outils</h1>";
$result=pg_query ($db,$sqlList["outil"]) or fatal_error ("Erreur pgSQL : ".pg_result_error ($result),false);
while ($row = pg_fetch_row($result))
	echo $row[0]."<BR>";

?>

