<?php
//------------------------------------------------------------------------------//
//   _INCLUDE/fonctions.inc.php                                                 //
//------------------------------------------------------------------------------//
function sql_connect ($base) {
    global $db;

    if ($base != "") {
        $db=pg_connect ("host=".SQL_server." port=".SQL_port." user=".SQL_user." password=".SQL_pass." dbname=".$base);
        return ($db);
    }
    else
        return (false);
}

function sql_connect_admin ($base) {
    global $db;
	
    if ($base != "") {
        $db=pg_connect ("host=".SQL_server." port=".SQL_port." user=".SQL_admin_user." password=".SQL_admin_pass." dbname=".$base);
        return ($db);
    }
    else
        return (false);
}

function html_header ($charset,$css) {
    echo ("<head><title>Cartographie du SINP - Expérimentation</title>");
    echo ("<link rel=\"shortcut icon\" href=\"../_GRAPH/icones/sinp_ico.png\" type=\"image/x-icon\" />");
    echo ("<meta http-equiv=\"Content-type\" content=\"text/html; charset=".$charset."\" />");
    echo ("<meta http-equiv=\"Content-Script-Type\" content=\"text/javascript\" />");
    echo ("<meta http-equiv=\"Content-Style-Type\" content=\"text/css\" />");
    echo ("<meta name=\"description\" content=\"Expérimentation de la cartograhie du SINP\">");

	echo ("<link rel=\"stylesheet\" href=\"https://unpkg.com/leaflet@1.3.3/dist/leaflet.css\"/>");
	echo ("<script src=\"https://unpkg.com/leaflet@1.3.3/dist/leaflet.js\"></script>");
    
    // echo ("<link rel=\"stylesheet\" type=\"text/css\" href=\"../_INCLUDE/css/global.css\" />");
    echo ("<link rel=\"stylesheet\" type=\"text/css\" href=\"../_INCLUDE/css/global_v2.css\" />");

    if ($css != "") echo ("<link rel=\"stylesheet\" type=\"text/css\" media=\"screen\" href=\"../_INCLUDE/css/".$css."\"  />");
    echo ("</head>");
	Echo "<a href=\"index.php\"><h1>Cartographie du SINP - expérimentation</h1></a>";
	Echo "<BR>";
}


function sqlConst ($theme,$idObjet) {

if ($theme == "plateforme") {
	$reqSql[0] = "
	SELECT		
		nom_region,
		CASE 
			WHEN hab_decision IS NOT NULL THEN habs.val_nmc 
			WHEN date_depot IS NOT NULL THEN 'Processus d''habilitation en cours'
			WHEN referent_nom IS NOT NULL THEN 'Test d''habilitation'
			ELSE 'Processus non lancé' END as \"hab_decision\",
		e.val_nmc as \"dynamique\",
		c3.val_nmc \"perenne\",
		crit3_desc \"perenne_desc\",
		c2.val_nmc \"charte\",
		crit2_desc \"charte_desc\",
		CASE WHEN charte.pj_nom IS NOT NULL THEN charte.pj_nom||' : '||charte.pj_ressource ELSE 'Pas de lien vers la charte disponible' END as \"charte_pj\",
		CASE WHEN std.pj_nom IS NOT NULL THEN std.pj_nom||' : '||std.pj_ressource ELSE 'Pas standard régional' END as \"standard\",
		c8.val_nmc \"echange\",
		crit8_desc \"echange_desc\",
		c11.val_nmc \"validation\",
		crit11_desc\"validation_desc\",
		CASE WHEN ref_sensi.pj_nom IS NOT NULL THEN ref_sensi.pj_nom||' : '||ref_sensi.pj_ressource ELSE 'Pas de réféentiel de sensibilité SINP' END as \"ref_sensibilité\"
	FROM hab.plateforme a
	LEFT JOIN hab.habilitation z ON a.id_ptf = z.id_ptf
	LEFT JOIN hab.reseau ON a.id_ptf = hab.reseau.id_ptf
	LEFT JOIN nomenc.hab_decision habs ON z.hab_decision = habs.lib_nmc
	LEFT JOIN nomenc.dynq_general e ON a.dynq_general = e.lib_nmc
	LEFT JOIN nomenc.crit_rea c2 ON z.crit2_rea = c2.lib_nmc
	LEFT JOIN nomenc.crit_rea c3 ON z.crit3_rea = c3.lib_nmc
	LEFT JOIN nomenc.crit_rea c8 ON z.crit8_rea = c8.lib_nmc
	LEFT JOIN nomenc.crit_rea c11 ON z.crit11_rea = c11.lib_nmc
	LEFT JOIN hab.piece_jointe charte ON a.id_ptf = charte.id_ptf AND charte.pj_type = 'charte'
	LEFT JOIN hab.piece_jointe std ON a.id_ptf = std.id_ptf AND std.pj_type = 'std'
	LEFT JOIN hab.piece_jointe ref_sensi ON a.id_ptf = ref_sensi.id_ptf AND ref_sensi.pj_type = 'ref_sensi'
	WHERE a.id_ptf = $idObjet
	GROUP BY nom_region,hab_decision, e.val_nmc,c3.val_nmc, crit3_desc, c2.val_nmc,crit2_desc, charte.pj_nom, charte.pj_ressource, std.pj_nom, std.pj_ressource, ref_sensi.pj_nom, ref_sensi.pj_ressource,c8.val_nmc,crit8_desc, c11.val_nmc , crit11_desc, ref_sensi.pj_nom, ref_sensi.pj_ressource, habs.val_nmc, z.date_depot, z.referent_nom
	;
	";
	
	$reqSql[1] = "
	WITH list_org as (SELECT pilote_org_nom  as nom, pilote_org_id  as id, 1 as role FROM hab.pilote WHERE id_ptf = $idObjet
		UNION SELECT outil_org_nom as nom, outil_org_id  as id, 2 as role FROM hab.outil WHERE id_ptf = $idObjet
		UNION SELECT reseau_org_nom  as nom, reseau_org_id  as id, 3 as role FROM hab.reseau WHERE id_ptf = $idObjet
		UNION SELECT interf_org_nom  as nom, interf_org_id  as id, 4 as role FROM hab.interface WHERE id_ptf = $idObjet)
	SELECT nom, id, role FROM list_org ORDER BY nom, role
	;
	";
	
	$reqSql[2] = "
	SELECT id_outil, outil_nom FROM hab.outil WHERE id_ptf = $idObjet
	ORDER BY outil_nom
	;
	";
	
	}
elseif ($theme == "organisme") {
	$reqSql[0] = "
	WITH list_ptf as (SELECT a.id_ptf , nom_region FROM hab.pilote a JOIN hab.plateforme z ON a.id_ptf = z.id_ptf WHERE pilote_org_id = '$idObjet'
		UNION SELECT a.id_ptf , nom_region FROM hab.outil a JOIN hab.plateforme z ON a.id_ptf = z.id_ptf WHERE outil_org_id = '$idObjet'
		UNION SELECT a.id_ptf , nom_region FROM hab.reseau a JOIN hab.plateforme z ON a.id_ptf = z.id_ptf  WHERE reseau_org_id = '$idObjet'
		UNION SELECT a.id_ptf , nom_region FROM hab.interface a JOIN hab.plateforme z ON a.id_ptf = z.id_ptf  WHERE interf_org_id = '$idObjet')
	SELECT id_ptf, nom_region FROM list_ptf ORDER BY nom_region
	;";
	$reqSql[1] = "
	;";
	$reqSql[2] = "
	SELECT id_outil, outil_nom FROM hab.outil WHERE outil_org_id = '$idObjet'
	;";
	}
elseif ($theme == "outil") {
	$reqSql[0] = "
	SELECT a.*, z.nom_region FROM hab.outil a
	JOIN hab.plateforme z ON a.id_ptf = z.id_ptf
	WHERE id_outil = $idObjet
	;";
	
	$reqSql[1] = "
	SELECT * FROM nomenc.fct_outil_desc
	;";

	$reqSql[2] = "
	SELECT outil_org_nom, outil_org_id FROM hab.outil WHERE id_outil = '$idObjet'
	;";
	}
elseif ($theme == "jdd") {
	$reqSql = "

	;
	";
	}
elseif ($theme == "question") {
// Quelles sont les plateformes en cours d’habilitation/habilitées SINP?
	$Sql[1] = "
	SELECT
		a.id_ptf,
		nom_region,
		CASE WHEN hab_decision IS NULL THEN 'Habilitation en cours' ELSE hab_decision END as \"statut\"
	FROM hab.plateforme a
	JOIN hab.habilitation z ON a.id_ptf = z.id_ptf
	;";
// Quelles sont les dynamiques des plateformes ainsi que leur pérennité ?
	$Sql[2] = "
	SELECT
		a.id_ptf,
		nom_region,
		e.val_nmc as \"dynamique\",
		c3.val_nmc \"perenne\"
	FROM hab.plateforme a
	JOIN hab.habilitation z ON a.id_ptf = z.id_ptf
	JOIN nomenc.dynq_general e ON a.dynq_general = e.lib_nmc
	JOIN nomenc.crit_rea c3 ON z.crit3_rea = c3.lib_nmc
	;";
// Quelles sont les plateformes qui possèdent une charte SINP compatible avec le protocole SINP?
	$Sql[3] = "
	SELECT
		a.id_ptf,
		nom_region,
		c2.val_nmc \"charte\",
		crit2_desc \"charte_desc\",
		CASE WHEN charte.pj_nom IS NOT NULL THEN charte.pj_nom||' : '||charte.pj_ressource ELSE 'Pas de lien vers la charte disponible' END as \"charte_pj\"
	FROM hab.plateforme a
	JOIN hab.habilitation z ON a.id_ptf = z.id_ptf
	JOIN nomenc.crit_rea c2 ON z.crit2_rea = c2.lib_nmc
	LEFT JOIN hab.piece_jointe charte ON a.id_ptf = charte.id_ptf AND charte.pj_type = 'charte'
	;";
// Quelles sont les plateformes qui possèdent un standard de données régional? 
	$Sql[4] = "
	SELECT
		a.id_ptf,
		nom_region,
		CASE WHEN std.pj_nom IS NOT NULL THEN std.pj_nom||' : '||std.pj_ressource ELSE 'Pas standard régional' END as \"standard\"
	FROM hab.plateforme a
	JOIN hab.habilitation z ON a.id_ptf = z.id_ptf
	LEFT JOIN hab.piece_jointe std ON a.id_ptf = std.id_ptf AND std.pj_type = 'std'
	;";
// Quelles sont les plateformes qui échangent leurs données avec la plateforme nationale?
	$Sql[5] = "
	SELECT
		a.id_ptf,
		nom_region,
		c8.val_nmc \"echange\",
		crit8_desc \"echange_desc\"
	FROM hab.plateforme a
	JOIN hab.habilitation z ON a.id_ptf = z.id_ptf
	JOIN nomenc.crit_rea c8 ON z.crit8_rea = c8.lib_nmc
	;";
// Quelles sont les plateformes qui organisent la validation scientifique des données?
	$Sql[6] = "
	SELECT
		a.id_ptf,
		nom_region,
		c11.val_nmc \"validation\",
		crit11_desc\"validation_desc\"
	FROM hab.plateforme a
	JOIN hab.habilitation z ON a.id_ptf = z.id_ptf
	JOIN nomenc.crit_rea c11 ON z.crit11_rea = c11.lib_nmc
	;";
// Quelles sont les plateformes qui possèdent un référentiel régional de sensibilité?
	$Sql[7] = "
	SELECT
		a.id_ptf,
		nom_region,
		CASE WHEN ref_sensi.pj_nom IS NOT NULL THEN ref_sensi.pj_nom||' : '||ref_sensi.pj_ressource ELSE 'Pas de réféentiel de sensibilité SINP' END as \"ref_sensibilité\"
	FROM hab.plateforme a
	JOIN hab.habilitation z ON a.id_ptf = z.id_ptf
	LEFT JOIN hab.piece_jointe ref_sensi ON a.id_ptf = ref_sensi.id_ptf AND ref_sensi.pj_type = 'ref_sensi'
	;";
// Quels sont les organismes qui participent au SINP? Quel est le rôle de ces organismes dans le SINP?
	$Sql[8] = "SELECT 'affaire à suivre';";
	
// Quels sont les organismes adhérents au protocole SINP?
	$Sql[9] = "SELECT 'affaire à suivre';";
	
// Quelles sont les données produites/gérées/financées par ces organismes?
	$Sql[10] = "SELECT 'affaire à suivre';";
	
// Quels sont les outils utilisés  par les plateformes dans le cadre du SINP? Quelle plateforme utilise/gère ces outils? 
	$Sql[11] = "SELECT 'affaire à suivre';";
	
// Quelles sont les fonctionnalités remplies par ces outils?
	$Sql[12] = "SELECT 'affaire à suivre';";
	
// Quels sont les jeux de données disponibles dans le SINP ? Dans l’INPN ?
	$Sql[13] = "SELECT 'affaire à suivre';";
	
// Quels sont les organismes impliqués dans ces jeux de données ?
	$Sql[14] = "SELECT 'affaire à suivre';";
	
// Quel est le « chemin parcouru » par ces jeux de données ?
	$Sql[15] = "SELECT 'affaire à suivre';";
	

	// récupération de la question d'intérêt
	$reqSql = $Sql[$idObjet];
	
	}
else {
	$reqSql = "
	SELECT 1
	;
	";
	}
return $reqSql;
}


function disp_org ($tab, $role)
{
global $URLAPI_organisme;
echo "<div id = ".$role["val_nmc"].">";
foreach ($tab as $unit) 
	{
	if ($unit["role"] == $role["lib_nmc"])
		{
		if ($unit["id"]!= null) 
			{
			$json_nom = json_decode(file_get_contents($URLAPI_organisme."&q=codeOrganisme:".$unit["id"]),true);
			$nom = ucfirst(strtolower($json_nom["response"]["docs"][0]["libelleLong"]));
			// $role = ;
			echo "<li><a href=\"organisme.php?id=".$unit["id"]."\">".$nom." (".$role["val_nmc"].")</a></li>";
			}
			else echo "<li>".$unit["nom"]." (".$role["val_nmc"].")</a></li>";
		}
	}
echo "</div>";
}

function recup_ref ($nom_ref)
	{
	global $db;
	$sql = "SELECT lib_nmc, val_nmc FROM nomenc.$nom_ref";
	$pgresult = pg_query ($db,$sql) or fatal_error ("Erreur pgSQL : ".pg_result_error ($pgresult),false);$ref = pg_fetch_all($pgresult);
	foreach ($ref as $unit) $referentiel[$unit["lib_nmc"]] = $unit["val_nmc"];
	return $referentiel;
	}
	
?>