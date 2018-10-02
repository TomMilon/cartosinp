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
//-----organisme
$pgresult=pg_query ($db,$req[0]) or fatal_error ("Erreur pgSQL : ".pg_result_error ($pgresult),false);$org = pg_fetch_all($pgresult);$org=$org[0];
//-----plateformes
$pgresult=pg_query ($db,$req[1]) or fatal_error ("Erreur pgSQL : ".pg_result_error ($pgresult),false);$ptf = pg_fetch_all($pgresult);
//-----tool
$pgresult=pg_query ($db,$req[2]) or fatal_error ("Erreur pgSQL : ".pg_result_error ($pgresult),false);$tool = pg_fetch_all($pgresult);
//-----jdd
$pgresult=pg_query ($db,$req[3]) or fatal_error ("Erreur pgSQL : ".pg_result_error ($pgresult),false);$jdd = pg_fetch_all($pgresult);


//----- référentiels
$ref["codeperimetreaction"] = recup_ref("perimetre_action");
$ref["codetypeorganisme"] = recup_ref("type_organisme");
$ref["codestatutorganisme"] = recup_ref("statut_organisme");
$ref["codeniveauadhesion"] = recup_ref("niveau_adhesion");
?>

<!-- FICHE-->
<h2><?php echo "<div class=\"organisme\">Organisme : ".$org["libellelong"]."</div>";?></h2>
<i> Les informations présentées sur cette page proviennent de l'outil Organisme (utilisation de la l'API)</i><BR><BR>
<b>Libellé court</b> : <?php echo $org["libellecourt"];?><BR>
<b>Adresse</b> : <?php echo $org["adresse"];?><BR>
<b>Périmètre d'action</b> : <?php echo $ref["codeperimetreaction"][$org["codeperimetreaction"]];?><BR>
<b>Type Organisme</b> : <?php echo $ref["codetypeorganisme"][$org["codetypeorganisme"]];?><BR>
<b>Statut Organisme</b> : <?php echo $ref["codestatutorganisme"][$org["codestatutorganisme"]];?><BR>
<b>Adhésion au SINP</b> : <?php echo $ref["codeniveauadhesion"][$org["codeniveauadhesion"]];?><BR>

<BR><BR>

<?php if (isset($org["x"])) echo "<div id=\"mapid\"></div>";
	else echo "Aucune d'information concernant l'adresse de l'organisme";
	?>


<div id="c1" class="ptf">
<b>Liste des plateformes</b><BR>
<table><tbody>
<?php 
if (empty($ptf)) echo $valeur_non_applicable; else foreach ($ptf as $unit) echo "<tr><td><a href=\"plateforme.php?id=".$unit["id_ptf"]."\">".$unit["nom_region"]."</a></td></tr>";?>
</tbody></table>
</div>


<div id="c3" class="outil">
<b>Liste des outils</b><BR>
<table><tbody>
<?php 
if (empty($tool)) echo $valeur_non_applicable; else foreach ($tool as $unit) echo "<tr><td><a href=\"outil.php?id=".$unit["id_outil"]."\">".$unit["outil_nom"]."</a></td></tr>";?>
</tbody></table>
</div>


<div id="c2" class="jdd">
<b>Liste des jeux de données</b><BR>
<table><tbody>
<?php 
if (empty($jdd)) echo $valeur_non_applicable; else foreach ($jdd as $unit) echo "<tr><td><a href=\"jdd.php?id=".$unit["id_jdd"]."\">".$unit["lib_jdd"]."</a></td></tr>";?>
</tbody></table>
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
        "name": "<?php echo $org["libellecourt"];?>",
        "popupContent": "<?php echo $org["adresse"];?>"
    },
    "geometry": {
        "type": "Point",
        "coordinates": [<?php echo $org["x"];?>,<?php echo $org["y"];?>]
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

<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.3/jquery.min.js"></script>
<!---sources = http://sunnywalker.github.io/jQuery.FilterTable/ -->
<script src="../_INCLUDE/js/jquery.filtertable.min.js"></script>
<script>
$(document).ready(function() {
	$('table').filterTable(	
	); // apply filterTable to all tables on this page
});
</script>
