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

//Récupération des données
$req = sqlConst("question",$_GET["id"]);
$pgresult=pg_query ($db,$req) or fatal_error ("Erreur pgSQL : ".pg_result_error ($pgresult),false);
while($res = pg_fetch_row($pgresult))
	$tab[$res[0]] = $res[1];
// var_dump($tab);
// géométrie
$sql = "SELECT insee_reg, ST_AsGeoJSON(ST_Transform(geom,4326),15,0) FROM nomenc.region_geo;";
$pgresult=pg_query ($db,$sql) or fatal_error ("Erreur pgSQL : ".pg_result_error ($pgresult),false);
while($res = pg_fetch_row($pgresult)) {$geo_region[$res[0]] = $res[1];}
// Question
$sqlList["question"] = "SELECT val_nmc, '<a href=\"question.php?id='||lib_nmc||'\">'||val_nmc||'</a>' as url FROM nomenc.carto_question ORDER BY lib_nmc::integer;";
$result=pg_query ($db,$sqlList["question"]) or fatal_error ("Erreur pgSQL : ".pg_result_error ($result),false);
$questions = pg_fetch_all($result);

//$JsonFeature
$JsonFeature = null;
foreach($geo_region as $key => $value)
	$JsonFeature .= "{\"type\": \"Feature\",\"properties\": {\"type\": \"".$tab[$key]."\",\"popupContent\": \"".$tab[$key]."\"}, \"geometry\": ".$value."},";
$JsonFeature = rtrim($JsonFeature,',');

//$JsonStyle
$JsonStyle = "switch (feature.properties.type) {";
foreach ($style[$_GET["id"]] as $key => $value)
            $JsonStyle .= " case '$key' : return $value; ";
$JsonStyle .= "}";

?>

<h2><?php echo $questions[$_GET["id"]-1]["val_nmc"];?></h2>

<div id="mapid_big"></div>

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

<script type="text/javascript">
function onEachFeature(feature, layer) {
    if (feature.properties && feature.properties.popupContent) {
        layer.bindPopup(feature.properties.popupContent);
    }
}
var mymap = L.map('mapid_big').setView([46.5, 0], 5);
L.tileLayer('http://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
    attribution: 'Map data © <a href="http://openstreetmap.org">OpenStreetMap</a> contributors, <a href="https://creativecommons.org/licenses/by-sa/2.0/">CC-BY-SA</a>',
    minZoom: 0,
    maxZoom: 12
}).addTo(mymap);

var geojsonFeature = [<?php echo($JsonFeature); ?>];
L.geoJSON(geojsonFeature , {onEachFeature: onEachFeature, style: function(feature) { <?php echo($JsonStyle); ?>}}).addTo(mymap);


</script>

