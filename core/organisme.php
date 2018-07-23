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
$req = sqlConst("organisme",$_GET["id"]);
//-----plateformes
$pgresult=pg_query ($db,$req[0]) or fatal_error ("Erreur pgSQL : ".pg_result_error ($pgresult),false);$ptf = pg_fetch_all($pgresult);
//-----jdd
// $pgresult=pg_query ($db,$req[1]) or fatal_error ("Erreur pgSQL : ".pg_result_error ($pgresult),false);$org = pg_fetch_all($pgresult);
//-----tool
$pgresult=pg_query ($db,$req[2]) or fatal_error ("Erreur pgSQL : ".pg_result_error ($pgresult),false);$tool = pg_fetch_all($pgresult);

//----- référentiels
$ref["codePerimetreAction"] = recup_ref("perimetre_action");
$ref["codeTypeOrganisme"] = recup_ref("type_organisme");
$ref["codeStatutOrganisme"] = recup_ref("statut_organisme");
$ref["codeNiveauAdhesion"] = recup_ref("niveau_adhesion");
// ---- API et geocoder
$org = api_org($_GET["id"]);
$adresse=geocoder($org,$_GET["id"]);
?>
<h2><?php echo $org["libelleLong"];?></h2>

<b>Libellé court</b> : <?php echo $org["libelleCourt"];?><BR>
<b>Périmètre d'action</b> : <?php echo $ref["codePerimetreAction"][$org["codePerimetreAction"]];?><BR>
<b>Type Organisme</b> : <?php echo $ref["codeTypeOrganisme"][$org["codeTypeOrganisme"]];?><BR>
<b>Statut Organisme</b> : <?php echo $ref["codeStatutOrganisme"][$org["codeStatutOrganisme"]];?><BR>
<b>Adhésion au SINP</b> : <?php echo $ref["codeNiveauAdhesion"][$org["codeNiveauAdhesion"]];?><BR>

<BR><BR>

<?php if (isset($adresse["postal"])) echo "<div id=\"mapid\"></div>";
	else echo "Aucune d'information concernant l'adresse de l'organisme";
	?>

<BR><BR>


<div id="c1" class="ptf">
<b>Liste des plateformes</b><BR>
<?php 
if (empty($ptf)) echo $valeur_non_applicable; else foreach ($ptf as $unit) echo "<li><a href=\"plateforme.php?id=".$unit["id_ptf"]."\">".$unit["nom_region"]."</a></li>";?>
</div>


<div id="c3" class="outil">
<b>Liste des outils</b><BR>
<?php 
if (empty($tool)) echo $valeur_non_applicable; else foreach ($tool as $unit) echo "<li><a href=\"outil.php?id=".$unit["id_outil"]."\">".$unit["outil_nom"]."</a></li>";?>
<BR><BR>
</div>


<div id="c2" class="jdd">
<b>Liste des jeux de données</b><BR>
<?php 
?>
</div>


<script type="text/javascript">
function onEachFeature(feature, layer) {
    // does this feature have a property named popupContent?
    if (feature.properties && feature.properties.popupContent) {
        layer.bindPopup(feature.properties.popupContent);
    }
}
var geojsonFeature = {
    "type": "Feature",
    "properties": {
        "name": "<?php echo $adresse["name"];?>",
        "popupContent": "<?php echo $adresse["postal"];?>"
    },
    "geometry": {
        "type": "Point",
        "coordinates": [<?php echo $adresse["x"];?>,<?php echo $adresse["y"];?>]
    }
};

// document.write(geojsonFeature.geometry.coordinates);

var mymap = L.map('mapid').setView([46, 0], 4);
L.tileLayer('http://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
    attribution: 'Map data © <a href="http://openstreetmap.org">OpenStreetMap</a> contributors, <a href="https://creativecommons.org/licenses/by-sa/2.0/">CC-BY-SA</a>',
    minZoom: 0,
    maxZoom: 12
}).addTo(mymap);

L.geoJSON(geojsonFeature, {
    onEachFeature: onEachFeature
}).addTo(mymap);

</script>



