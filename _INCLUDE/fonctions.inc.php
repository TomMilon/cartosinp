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
    echo ("<link rel=\"shortcut icon\" href=\"../_GRAPH/SINP.png\" type=\"image/x-icon\" />");
    echo ("<meta http-equiv=\"Content-type\" content=\"text/html; charset=".$charset."\" />");
    echo ("<meta http-equiv=\"Content-Script-Type\" content=\"text/javascript\" />");
    echo ("<meta http-equiv=\"Content-Style-Type\" content=\"text/css\" />");
    echo ("<meta name=\"description\" content=\"Expérimentation de la cartograhie du SINP\">");
    
    echo ("<link rel=\"stylesheet\" type=\"text/css\" href=\"../_INCLUDE/css/global.css\" />");

    if ($css != "") echo ("<link rel=\"stylesheet\" type=\"text/css\" media=\"screen\" href=\"../_INCLUDE/css/".$css."\"  />");
    echo ("</head>");
	Echo "<a href=\"index.php\"><h1>Cartographie du SINP - expérimentation</h1></a>";
	Echo "<BR>";
}


function sqlConst ($theme,$idObjet) {

if ($theme == "plateforme") {
	$reqSql = "
	WITH list_org as (
		  SELECT id_ptf, outil_org as org FROM hab.outil
	UNION SELECT id_ptf, reseau_org  as org FROM hab.reseau
	UNION SELECT id_ptf, pilote_org  as org FROM hab.pilote
	UNION SELECT id_ptf, interf_org  as org FROM hab.interface
	UNION SELECT id_ptf, interf_contact_org  as org FROM hab.interface
	), list_org_groupe as (
	SELECT id_ptf, string_agg(list_org.org,' / ') as orglist FROM list_org GROUP BY id_ptf
	) , liste_outil as (
	SELECT id_ptf, string_agg(outil_nom,' / ') as toollist FROM hab.outil GROUP BY id_ptf
	)
	SELECT 
		nom_region as \"Nom de la région\",
		CASE WHEN hab_decision IS NULL THEN 'Habilitation en cours' ELSE hab_decision END as \"Statut de la plateforme\",
		e.val_nmc as \"Dynamique de la plateforme\",
		c3.val_nmc as \"Plateforme pérène?\",
		crit3_desc as \"compléments sur la pérénité\",
		c2.val_nmc as \"Charte SINP?\",
		crit2_desc as \"compléments sur la charte\",
		CASE WHEN charte.pj_nom IS NOT NULL THEN charte.pj_nom||' : '||charte.pj_ressource ELSE 'Pas de lien vers la charte disponible' END as \"lien vers la charte\",
		CASE WHEN std.pj_nom IS NOT NULL THEN std.pj_nom||' : '||std.pj_ressource ELSE 'Pas standard régional' END as \"Standard de données régional SINP\",
		c8.val_nmc as \"Échange avec la plateforme nationale?\",
		crit8_desc as \"compléments sur les échanges\",
		null as \"liste des jeux de données échangés\",
		c11.val_nmc as \"validation scientifique des données?\",
		crit11_desc as \"compléments sur la validation scientifique\",
		CASE WHEN ref_sensi.pj_nom IS NOT NULL THEN ref_sensi.pj_nom||' : '||ref_sensi.pj_ressource ELSE 'Pas de réféentiel de sensibilité SINP' END as \"Référentiel de sensibilité\",
		toollist as \"liste des outils\",
		orglist as \"liste des organismes\"
	FROM hab.plateforme a
	JOIN hab.habilitation z ON a.id_ptf = z.id_ptf
	JOIN hab.reseau ON a.id_ptf = hab.reseau.id_ptf
	JOIN nomenc.dynq_general e ON a.dynq_general = e.lib_nmc
	JOIN nomenc.crit_rea c2 ON z.crit2_rea = c2.lib_nmc
	JOIN nomenc.crit_rea c3 ON z.crit3_rea = c3.lib_nmc
	JOIN nomenc.crit_rea c8 ON z.crit8_rea = c8.lib_nmc
	JOIN nomenc.crit_rea c11 ON z.crit11_rea = c11.lib_nmc
	LEFT JOIN hab.piece_jointe charte ON a.id_ptf = charte.id_ptf AND charte.pj_type = 'charte'
	LEFT JOIN hab.piece_jointe std ON a.id_ptf = std.id_ptf AND std.pj_type = 'std'
	LEFT JOIN hab.piece_jointe ref_sensi ON a.id_ptf = ref_sensi.id_ptf AND ref_sensi.pj_type = 'ref_sensi'
	JOIN list_org_groupe ON a.id_ptf = list_org_groupe.id_ptf
	JOIN liste_outil ON a.id_ptf = liste_outil.id_ptf
	WHERE a.id_ptf = $idObjet
	GROUP BY nom_region,hab_decision, e.val_nmc,c3.val_nmc, crit3_desc, c2.val_nmc,crit2_desc, charte.pj_nom, charte.pj_ressource, std.pj_nom, std.pj_ressource, ref_sensi.pj_nom, ref_sensi.pj_ressource,c8.val_nmc,crit8_desc, c11.val_nmc , crit11_desc, ref_sensi.pj_nom, ref_sensi.pj_ressource, list_org_groupe.orglist, liste_outil.toollist
	;
	";
	}
elseif ($theme == "organisme") {
	$reqSql = "
	
	;";
	}
elseif ($theme == "outil") {
	$reqSql = "
	
	;
	";
	}
elseif ($theme == "jdd") {
	$reqSql = "
	
	;
	";
	}
elseif ($theme == "question") {
	$reqSql[0] = "
	SELECT
		nom_region as \"Nom de la région\",
		CASE WHEN hab_decision IS NULL THEN 'Habilitation en cours' ELSE hab_decision END as \"Statut de la plateforme\"
	FROM hab.plateforme a
	JOIN hab.habilitation z ON a.id_ptf = z.id_ptf
	;
	";
	$reqSql[1] = "
	SELECT
		nom_region as \"Nom de la région\",
		c3.val_nmc as \"Plateforme pérène?\"
	FROM hab.plateforme a
	JOIN hab.habilitation z ON a.id_ptf = z.id_ptf
	JOIN nomenc.crit_rea c3 ON z.crit3_rea = c3.lib_nmc
	;
	";
	}
else {
	$reqSql = "
	SELECT 1
	;
	";
	}
return $reqSql;
}

?>