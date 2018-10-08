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
$req = sqlConst("jdd",$_GET["id"]);
//-----jdd
$pgresult=pg_query ($db,$req[0]) or fatal_error ("Erreur pgSQL : ".pg_result_error ($pgresult),false);$jdd = pg_fetch_all($pgresult);$jdd = $jdd[0];
//-----organisme
$pgresult=pg_query ($db,$req[1]) or fatal_error ("Erreur pgSQL : ".pg_result_error ($pgresult),false);$org = pg_fetch_all($pgresult);
//-----tool
if (!empty($jdd["id_outil"])) {$tool["id"] = $jdd["id_outil"];$tool["lib"]= $jdd["lib_outil_origine"];}
//-----ptf
if (!empty($jdd["id_ptf"])) {$ptf["id"] = $jdd["id_ptf"];$ptf["lib"]= $jdd["nom_region"];}

//----- référentiels
// $ref["codeperimetreaction"] = recup_ref("perimetre_action");
// $ref["codetypeorganisme"] = recup_ref("type_organisme");
// $ref["codestatutorganisme"] = recup_ref("statut_organisme");
// $ref["codeniveauadhesion"] = recup_ref("niveau_adhesion");
$date_crea = new DateTime($jdd["datecreation"]);
$date_modif = new DateTime($jdd["daterevision"]);

?>

<!-- FICHE-->
<h2><?php echo "<div class=\"jdd\">Jeu de données : ".$jdd["lib_jdd"]."</div>";?></h2>
<i> Les informations présentées sur cette page proviennent de l'outil Métadonnées </i><BR><BR>
<b>Fiche créée le </b> <?php echo date_format($date_crea, 'd/m/Y à H:i:s');
if ($date_crea < $date_modif) echo " - modidiée le ".date_format($date_modif, 'd/m/Y à H:i:s')." (dernière modification)";?><BR>
<b>Lien vers la fiche métadonnées sur l'INPN </b> : <?php echo "<a href=\"".$URL_appli_metadonnee.$jdd["id_jdd"]."\">Fiche ".$jdd["id_jdd"]."</a>";?><BR>
<b>Identifiant SINP</b> : <?php echo $jdd["id_sinp_jdd"];?><BR>
<b>Identifiant INPN</b> : <?php echo $jdd["cd_jdd"];?><BR>
<b>Libellé court</b> : <?php echo str_replace("''","'",$jdd["libellecourt"]);?><BR>
<b>Libellé long</b> : <?php echo $jdd["lib_jdd"];?><BR>
<BR>

<b> Objectif Jdd</b> : <?php echo $jdd["objectifjdd"];?><BR>
<b> Protocoles</b> : <?php echo $jdd["protocoles"];?><BR>
<b>Description</b> : <?php echo str_replace("''","'",$jdd["description"]);?><BR>
<BR>

<b> TypeDonnees </b> : <?php echo $jdd["typedonnees"];?><BR>
<b> Lien vers les données sur le requêteur</b> : à venir <BR>
<b> Domaine Marin</b> : <?php echo $jdd["domainemarin"];?><BR>
<b> Domaine Terrestre</b> : <?php echo $jdd["domaineterrestre"];?><BR>
<b> Territoire</b> : <?php echo $jdd["territoire"];?><BR>




<div id="c1" class="organisme">
<b>Liste des organismes</b><BR>
<table><tbody>
<?php 
<?php 
if (empty($org)) echo $valeur_non_applicable; 

else foreach ($org as $unit) {
	if ($unit["id_org"] == 'N/A' OR empty($unit["id_org"])) echo "<tr><td>".$unit["lib_org"]." - ".$unit["nom_contact"]." (".$unit["typ_org"].")</td></tr>"; 
	else echo "<tr><td><a href=\"organisme.php?id=".$unit["id_org"]."\">".$unit["lib_org"]." - ".$unit["nom_contact"]." (".$unit["typ_org"].") </a></td></tr>";
}
?>
</tbody></table>
</div>


<div id="c3" class="outil">
<b>Liste des outils</b><BR>
<table><tbody>
<?php 
// if (empty($tool)) echo $valeur_non_applicable; else foreach ($tool as $unit) echo "<tr><td><a href=\"outil.php?id=".$unit["id"]."\">".$unit["lib"]."</a></td></tr>";
if (empty($tool)) echo $valeur_non_applicable; else echo "<tr><td><a href=\"outil.php?id=".$tool["id"]."\">".$tool["lib"]."</a></td></tr>";
?>
</tbody></table>
</div>


<div id="c2" class="ptf">
<b>Liste des plateformes</b><BR>
<table><tbody>
<?php 
if (empty($ptf)) echo $valeur_non_applicable; else echo "<tr><td><a href=\"plateforme.php?id=".$ptf["id"]."\">".$ptf["lib"]."</a></td></tr>";
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
