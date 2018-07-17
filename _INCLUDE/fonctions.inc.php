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
    
    echo ("<link rel=\"stylesheet\" type=\"text/css\" href=\"../_INCLUDE/css/global.css\" />");

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
		CASE WHEN hab_decision IS NULL THEN 'Habilitation en cours' ELSE hab_decision END as \"hab_decision\",
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
	WHERE a.id_ptf = $idObjet
	GROUP BY nom_region,hab_decision, e.val_nmc,c3.val_nmc, crit3_desc, c2.val_nmc,crit2_desc, charte.pj_nom, charte.pj_ressource, std.pj_nom, std.pj_ressource, ref_sensi.pj_nom, ref_sensi.pj_ressource,c8.val_nmc,crit8_desc, c11.val_nmc , crit11_desc, ref_sensi.pj_nom, ref_sensi.pj_ressource
	;
	";
	
	$reqSql[1] = "
	WITH list_org as (SELECT pilote_org  as nom, 'pilote' as role FROM hab.pilote WHERE id_ptf = $idObjet
		UNION SELECT outil_org as nom, 'porteur d''outil' as role FROM hab.outil WHERE id_ptf = $idObjet
		UNION SELECT reseau_org  as nom, 'tête de réseau' as role FROM hab.reseau WHERE id_ptf = $idObjet
		UNION SELECT interf_org  as nom, 'contact données' as role FROM hab.interface WHERE id_ptf = $idObjet)
	SELECT nom, role FROM list_org ORDER BY nom, role
	;
	";
	
	$reqSql[2] = "
	SELECT id_outil, outil_nom FROM hab.outil WHERE id_ptf = $idObjet
	ORDER BY outil_nom
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