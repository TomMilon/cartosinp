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
Ce site propose une expérimentation en terme de visualisation de la cartographie du SINP.
<BR><BR> <b>Sources</b> : <BR>
<li> - dossiers d'habilitations des plateformes régionales
<li> - référentiel organisme
<li> - INPN métadonnées
</div>


<div id="c1" class="ptf">
	<h2>Plateformes</h2>
	<?php include("../_DATA/liste_plateformes.html"); ?>
</div>

<div id="c2" class="organisme">
	<h2>Organismes</h2>
	<?php include("../_DATA/liste_organismes.html"); ?>
</div>

	
<div id="c3" class="outil">
	<h2>Outils</h2>
<?php include("../_DATA/liste_outils.html"); ?>
</div>


<div id="c4" class="jdd">
	<h2>Jeux de données</h2>
<?php include("../_DATA/liste_jdds.html"); ?>
</div>

<BR><BR>
<BR><BR>
<BR><BR>
<div id="question">
	<h2>Les questions</h2>
<?php include("../_DATA/liste_questions.html"); ?>
</div>

<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.3/jquery.min.js"></script>
<script src="../_INCLUDE/js/jquery.filtertable.min.js"></script>
<script>
$(document).ready(function() {
	$('table').filterTable(	
	); // apply filterTable to all tables on this page
});
</script>