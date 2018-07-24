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
// reconstruction du tableau
$i = 0;$list_org=array();
foreach ($org as $unit) 
	{
	if ($unit["id"]!= null)
		{
			if (in_array($unit["id"],$list_org)) $new_tab[$unit["id"]]["role"] .= ", ".$unit["role"];
			else 
			{
				$sql = "SELECT * FROM nomenc.ref_org WHERE codeorganisme = '".$unit["id"]."';";
				$pgresult = pg_query ($db,$sql) or fatal_error ("Erreur pgSQL : ".pg_result_error ($pgresult),false);$ref_org = pg_fetch_all($pgresult);$ref_org = $ref_org[0];
				$new_tab[$unit["id"]]["id"] = $unit["id"];
				$new_tab[$unit["id"]]["role"] = $unit["role"];
				$new_tab[$unit["id"]]["libellelong"] = ucfirst(strtolower($ref_org["libellelong"]));
				$new_tab[$unit["id"]]["x"] = $ref_org["x"];
				$new_tab[$unit["id"]]["y"] = $ref_org["y"];
				array_push($list_org,$unit["id"]);
			}
		}
		else
		{
			if (in_array($i,$list_org)) $new_tab[$i]["role"] .= ", ".$unit["role"];
			else 
			{
				$new_tab[$i]["id"] = null;
				$new_tab[$i]["libellelong"] = ucfirst(strtolower($unit["nom"]));
				$new_tab[$i]["role"] = $unit["role"];
				array_push($list_org,$i);
				$i++;
			}
		}
	}
foreach ($new_tab as $unit)
{
	if ($unit["id"]!= null) echo "<li><a href=\"organisme.php?id=".$unit["id"]."\">".$unit["libellelong"]." (".$unit["role"].")</a></li>";
	else echo "<li>".$unit["libellelong"]." (".$unit["role"].")</a></li>";
}

?>
</div>

<?php 
//featurecollection
// $phpFeature = array(
	// "type" => "FeatureCollection",
	// "features" => array());
// foreach ($list_org as $unit)
	// {	
	// $org = api_org($unit);
	// $adresse=geocoder($org,$unit);
	// $phpNewFeature = array(
		// "type"  => "Feature",
		// "properties"  => array(
			// "name"  => $adresse["name"],
			// "popupContent"  => $adresse["postal"]
			// ),
		// "geometry"  => array(
			// "type"  => "Point",
			// "coordinates"  => "[".$adresse["x"].",".$adresse["y"]."]"
			// )
		// );
	// array_push($phpFeature["features"],$phpNewFeature);
	// }
// if (isset($phpNewFeature)) echo "<div id=\"mapid\"></div>";
	// else echo "aucun organisme";
// $geojsonFeature = json_encode($phpFeature);
// var_dump($geojsonFeature);

$i=0;
foreach ($new_tab as $unit)
{	
	if (!is_null($unit["id"]))
	{
		if (!is_null($unit["x"]))
		{
			$adresse[$i]["name"]=$unit["libellelong"];
			$adresse[$i]["postal"]=$unit["libellelong"];
			$adresse[$i]["x"]=$unit["x"];
			$adresse[$i]["y"]=$unit["y"];
			$i++;
		}
	}
}

if (isset($adresse)) echo "<div id=\"mapid_big\"></div>";
	else echo "aucun organisme";

?>

<script type="text/javascript">
function onEachFeature(feature, layer) {
    if (feature.properties && feature.properties.popupContent) {
        layer.bindPopup(feature.properties.popupContent);
    }
}

var geojsonFeature = {"type": "Feature","properties": {"name": "<?php echo $adresse[0]["name"];?>","popupContent": "<?php echo $adresse[0]["postal"];?>"},
    "geometry": {"type": "Point","coordinates": [<?php echo $adresse[0]["x"];?>,<?php echo $adresse[0]["y"];?>]}};
var geojsonFeature1 = {"type": "Feature","properties": {"name": "<?php echo $adresse[1]["name"];?>","popupContent": "<?php echo $adresse[1]["postal"];?>"},
    "geometry": {"type": "Point","coordinates": [<?php echo $adresse[1]["x"];?>,<?php echo $adresse[1]["y"];?>]}};
var geojsonFeature2 = {"type": "Feature","properties": {"name": "<?php echo $adresse[2]["name"];?>","popupContent": "<?php echo $adresse[2]["postal"];?>"},
    "geometry": {"type": "Point","coordinates": [<?php echo $adresse[2]["x"];?>,<?php echo $adresse[2]["y"];?>]}};
var geojsonFeature3 = {"type": "Feature","properties": {"name": "<?php echo $adresse[3]["name"];?>","popupContent": "<?php echo $adresse[3]["postal"];?>"},
    "geometry": {"type": "Point","coordinates": [<?php echo $adresse[3]["x"];?>,<?php echo $adresse[3]["y"];?>]}};
var geojsonFeature4 = {"type": "Feature","properties": {"name": "<?php echo $adresse[4]["name"];?>","popupContent": "<?php echo $adresse[4]["postal"];?>"},
    "geometry": {"type": "Point","coordinates": [<?php echo $adresse[4]["x"];?>,<?php echo $adresse[4]["y"];?>]}};


// document.write(geojsonFeature);

var mymap = L.map('mapid_big').setView([46, 0], 4);
L.tileLayer('http://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
    attribution: 'Map data © <a href="http://openstreetmap.org">OpenStreetMap</a> contributors, <a href="https://creativecommons.org/licenses/by-sa/2.0/">CC-BY-SA</a>',
    minZoom: 0,
    maxZoom: 12
}).addTo(mymap);

L.geoJSON(geojsonFeature, {onEachFeature: onEachFeature}).addTo(mymap);
L.geoJSON(geojsonFeature1, {onEachFeature: onEachFeature}).addTo(mymap);
L.geoJSON(geojsonFeature2, {onEachFeature: onEachFeature}).addTo(mymap);
L.geoJSON(geojsonFeature3, {onEachFeature: onEachFeature}).addTo(mymap);
L.geoJSON(geojsonFeature4, {onEachFeature: onEachFeature}).addTo(mymap);
</script>

