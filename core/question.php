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
$tab = pg_fetch_all($pgresult);
// var_dump($tab);

$sql = "SELECT ST_AsGeoJSON(ST_Transform(geom,4326),15,0) FROM nomenc.region_geo;";
$pgresult=pg_query ($db,$sql) or fatal_error ("Erreur pgSQL : ".pg_result_error ($pgresult),false);
$i=0;
while($res = pg_fetch_row($pgresult)) {$geo_region[$i] = $res[0];$i++;}
// var_dump($geo_region);

//$JsonFeature
$JsonFeature = null;
for($i=0;$i<=12;$i++)
	$JsonFeature .= "{\"type\": \"Feature\",\"properties\": {\"type\": \"".$tab[$i]["statut"]."\",\"popupContent\": \"".$tab[$i]["statut"]."\"}, \"geometry\": ".$geo_region[$i]."},";
$JsonFeature = rtrim($JsonFeature,',');

//$JsonStyle
$JsonStyle = "switch (feature.properties.type) {";
foreach ($style[$_GET["id"]] as $key => $value)
            $JsonStyle .= " case '$key' : return $value; ";
$JsonStyle .= "}";

?>


<div id="mapid_big"></div>

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

