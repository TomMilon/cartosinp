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
	echo ("<script src=\"../_INCLUDE/js/jquery-3.3.1.min.js\"></script>");
	echo ("<script src=\"../_INCLUDE/js/jquery.filtertable.min.js\"></script>");

    // echo ("<link rel=\"stylesheet\" type=\"text/css\" href=\"../_INCLUDE/css/global.css\" />");
    // echo ("<link rel=\"stylesheet\" type=\"text/css\" href=\"../_INCLUDE/css/global_v2.css\" />");
    echo ("<link rel=\"stylesheet\" type=\"text/css\" href=\"../_INCLUDE/css/filtertable.css\" />");
    echo ("<link rel=\"stylesheet\" type=\"text/css\" href=\"../_INCLUDE/css/global_v3.css\" />");

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
	WITH list_org as (SELECT pilote_org_nom as nom, pilote_org_id  as id, 'Pilote' as role FROM hab.pilote WHERE id_ptf = $idObjet
		UNION SELECT outil_org_nom as nom, outil_org_id  as id, 'Porteur d''outil' as role FROM hab.outil WHERE id_ptf = $idObjet
		UNION SELECT reseau_org_nom  as nom, reseau_org_id  as id, 'Tête de réseau' as role FROM hab.reseau WHERE id_ptf = $idObjet
		UNION SELECT interf_org_nom  as nom, interf_org_id  as id, 'Contact données' as role FROM hab.interface WHERE id_ptf = $idObjet)
	SELECT id,nom,role FROM list_org ORDER BY nom
	;
	";
	
	$reqSql[2] = "
	SELECT id_outil, outil_nom FROM hab.outil WHERE id_ptf = $idObjet
	ORDER BY outil_nom
	;
	";

	$reqSql[3] = "
	SELECT * FROM nomenc.ref_jdd WHERE id_ptf = $idObjet
	ORDER BY lib_jdd
	;
	";	
	}
elseif ($theme == "organisme") {
	$reqSql[0] = "
		SELECT *, adresse||' '||codepostal||'  '||ville as adresse FROM nomenc.ref_org WHERE codeorganisme = '$idObjet'
	;";
	$reqSql[1] = "
	WITH list_ptf as (SELECT a.id_ptf , nom_region FROM hab.pilote a JOIN hab.plateforme z ON a.id_ptf = z.id_ptf WHERE pilote_org_id = '$idObjet'
		UNION SELECT a.id_ptf , nom_region FROM hab.outil a JOIN hab.plateforme z ON a.id_ptf = z.id_ptf WHERE outil_org_id = '$idObjet'
		UNION SELECT a.id_ptf , nom_region FROM hab.reseau a JOIN hab.plateforme z ON a.id_ptf = z.id_ptf  WHERE reseau_org_id = '$idObjet'
		UNION SELECT a.id_ptf , nom_region FROM hab.interface a JOIN hab.plateforme z ON a.id_ptf = z.id_ptf  WHERE interf_org_id = '$idObjet')
	SELECT id_ptf, nom_region FROM list_ptf ORDER BY nom_region
	;";
	$reqSql[2] = "
	SELECT id_outil, outil_nom FROM hab.outil WHERE outil_org_id = '$idObjet'
	;";
	$reqSql[3] = "
	SELECT * FROM nomenc.ref_org_jdd a
	JOIN nomenc.ref_jdd z ON a.id_jdd = z.id_jdd
	WHERE a.id_org = '$idObjet'
	;";
	}
elseif ($theme == "outil") {
	$reqSql[0] = "
	SELECT a.*, z.nom_region FROM hab.outil a
	LEFT JOIN hab.plateforme z ON a.id_ptf = z.id_ptf
	WHERE id_outil = $idObjet
	;";
	
	$reqSql[1] = "
	SELECT * FROM nomenc.fct_outil_desc
	;";

	$reqSql[2] = "
	SELECT outil_org_nom, outil_org_id FROM hab.outil WHERE id_outil = '$idObjet'
	;";
	$reqSql[3] = "
	SELECT z.* FROM hab.outil a
	JOIN nomenc.ref_jdd z ON a.id_outil = z.id_outil
	WHERE a.id_outil = '$idObjet'
	;";
	}
elseif ($theme == "jdd") {
	$reqSql[0] = "
		SELECT a.*, z.nom_region FROM nomenc.ref_jdd a
			LEFT JOIN hab.plateforme z ON a.id_ptf = z.id_ptf
			WHERE id_jdd = $idObjet
	;
	";
	$reqSql[1] = "
	SELECT * FROM nomenc.ref_org_jdd WHERE id_jdd = $idObjet
	;
	";
	
	}
elseif ($theme == "question") {
// Quelles sont les plateformes en cours d’habilitation/habilitées SINP?
	$Sql[1] = "
	SELECT
		insee_reg,
		CASE WHEN z.id_ptf IS NULL THEN 'Dossier non déposé' WHEN hab_decision IS NULL THEN 'Habilitation en cours' ELSE hab_decision END as \"statut\"
	FROM hab.plateforme a
	LEFT JOIN hab.habilitation z ON a.id_ptf = z.id_ptf
	;";
// Quelles sont les dynamiques des plateformes ainsi que leur pérennité ? // CASE WHEN c3.val_nmc IS NULL THEN 'Dossier non déposé' ELSE c3.val_nmc END \"perenne\" + LEFT JOIN nomenc.crit_rea c3 ON z.crit3_rea = c3.lib_nmc
	$Sql[2] = "
	SELECT
		insee_reg,
		CASE WHEN z.id_ptf IS NULL THEN 'Dossier non déposé' WHEN e.val_nmc IS NULL THEN 'Dossier non déposé' ELSE e.val_nmc END as \"statut\"
	FROM hab.plateforme a
	LEFT JOIN hab.habilitation z ON a.id_ptf = z.id_ptf
	LEFT JOIN nomenc.dynq_general e ON a.dynq_general = e.lib_nmc
	;";
// Quelles sont les plateformes qui possèdent une charte SINP compatible avec le protocole SINP?
	$Sql[3] = "
	SELECT
		insee_reg,
		CASE WHEN z.id_ptf IS NULL THEN 'Dossier non déposé' WHEN c2.val_nmc IS NULL THEN 'Dossier non déposé' ELSE c2.val_nmc END as \"statut\"
	FROM hab.plateforme a
	LEFT JOIN hab.habilitation z ON a.id_ptf = z.id_ptf
	LEFT JOIN nomenc.crit_rea c2 ON z.crit2_rea = c2.lib_nmc
	;";
// Quelles sont les plateformes qui possèdent un standard de données régional? 
	$Sql[4] = "
	SELECT
		insee_reg,
		CASE WHEN z.id_ptf IS NULL THEN 'Dossier non déposé' WHEN std.pj_nom IS NOT NULL THEN 'oui' ELSE 'non' END as \"statut\"
	FROM hab.plateforme a
	LEFT JOIN hab.habilitation z ON a.id_ptf = z.id_ptf
	LEFT JOIN hab.piece_jointe std ON a.id_ptf = std.id_ptf AND std.pj_type = 'std'
	;";
// Quelles sont les plateformes qui échangent leurs données avec la plateforme nationale?
	$Sql[5] = "
	SELECT
		insee_reg,
		CASE WHEN z.id_ptf IS NULL THEN 'Dossier non déposé' WHEN c8.val_nmc IS NOT NULL THEN c8.val_nmc ELSE 'Dossier non déposé' END as \"statut\"
	FROM hab.plateforme a
	LEFT JOIN hab.habilitation z ON a.id_ptf = z.id_ptf
	LEFT JOIN nomenc.crit_rea c8 ON z.crit8_rea = c8.lib_nmc
	;";
// Quelles sont les plateformes qui organisent la validation scientifique des données?
	$Sql[6] = "
	SELECT
		insee_reg,
		CASE WHEN z.id_ptf IS NULL THEN 'Dossier non déposé' WHEN c11.val_nmc IS NOT NULL THEN c11.val_nmc ELSE 'Dossier non déposé' END as \"statut\"
	FROM hab.plateforme a
	LEFT JOIN hab.habilitation z ON a.id_ptf = z.id_ptf
	LEFT JOIN nomenc.crit_rea c11 ON z.crit11_rea = c11.lib_nmc
	;";
// Quelles sont les plateformes qui possèdent un référentiel régional de sensibilité?
	$Sql[7] = "
	SELECT
		insee_reg,
		CASE WHEN z.id_ptf IS NULL THEN 'Dossier non déposé' WHEN ref_sensi.pj_nom IS NOT NULL THEN 'oui' ELSE 'non' END as \"statut\"
	FROM hab.plateforme a
	LEFT JOIN hab.habilitation z ON a.id_ptf = z.id_ptf
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

// ------------API ref organisme
function api_org($id_sinp)
{
	global $URLAPI_organisme;
	$json =file_get_contents($URLAPI_organisme."&q=codeOrganisme:".$id_sinp);
	$jsondec = json_decode($json, true);
	$org = $jsondec["response"]["docs"][0];
	return $org;
}

// ------------API Geocode
function geocoder($adresse)	
{
global $URLAPI_geocode;
if (isset($adresse)) 
	{
	$URL = str_replace(" ","+",$URLAPI_geocode.$adresse);

	$ch = curl_init();
	curl_setopt($ch, CURLOPT_URL, $URL);
	curl_setopt($ch, CURLOPT_RETURNTRANSFER , TRUE);
	$output = curl_exec($ch); 
	$output = json_decode($output,true);
	if(isset($output["features"][0]["geometry"]["coordinates"][0])) $localisation["x"]=$output["features"][0]["geometry"]["coordinates"][0];
	if(isset($output["features"][1]["geometry"]["coordinates"][0])) $localisation["y"]=$output["features"][0]["geometry"]["coordinates"][1];
	}
if(!isset($localisation)) $localisation = null;
return 	$localisation;
}

// ------------API ref organisme
function api_to_pgsql($mode)
{
	global $URLAPI_organisme;global $URLAPI_metadata;global $db;global $limit_json;
	switch ($mode) {
		case "org" :
			$json =file_get_contents($URLAPI_organisme.$limit_json);
			$jsondec = json_decode($json, true);
			$org = $jsondec["response"]["docs"];
			$liste_id = array();
			$sql = "TRUNCATE nomenc.ref_org CASCADE;";
			$champ = array("codeOrganisme", "siretSiege", "pays", "adresseMessagerie", "id", "ville", "adresse", "uRL", "libelleCourt", "dateAdhesion", "libelleLong", "gel", "dateCreationFiche", "dateModif", "codePostal", "codePerimetreAction", "codeTypeOrganisme", "codeStatutOrganisme","codeNiveauAdhesion","siret", "x", "y");
			$sql .= "INSERT INTO nomenc.ref_org(".implode(",", $champ).") VALUES ";
			foreach ($org as $unit)
			{	
				if(!in_array($unit["codeOrganisme"],$liste_id))
				{
					// contre les doublons
					array_push($liste_id,$unit["codeOrganisme"]);
					//construction sql
					foreach ($champ as $cle)	
						$resp[$cle] = (isset($unit[$cle]) AND $unit[$cle] !="(null)") ? "'".str_replace("'","''",$unit[$cle])."'" : "null";
					$sql .= "
						(";
					foreach ($champ as $cle) 
						$sql .= $resp[$cle].",";
					$sql = rtrim($sql,',');
					$sql .= "),";
				}
		
			}
			$sql = rtrim($sql,',');
			$sql .= ";";
			$pgresult=pg_query ($db,$sql) or fatal_error ("Erreur pgSQL : ".pg_result_error ($pgresult),false);
			break;
		case "geo" :
			$ref = "SELECT codeOrganisme, adresse||' '||codepostal||'  '||ville as adresse FROM nomenc.ref_org;";
			$sql = "";
			$pgresult=pg_query ($db,$ref) or fatal_error ("Erreur pgSQL : ".pg_result_error ($pgresult),false);
			while ($id = pg_fetch_row($pgresult))
			{
				if (!is_null($id[1])) 
				{
					$localisation = geocoder($id[1]);
					if (!is_null($localisation))
						$sql .= "
						UPDATE nomenc.ref_org SET x=".$localisation["x"]." ,y=".$localisation["y"]." WHERE codeOrganisme='".$id[0]."';";
				}
				
			}
			$pgresult=pg_query ($db,$sql) or fatal_error ("Erreur pgSQL : ".pg_result_error ($pgresult),false);
		break;
		case "jdd" :
			// à reprendre totalement
			$ref = "SELECT id_sinp_jdd FROM nomenc.ref_jdd;";
			$pgresult=pg_query ($db,$ref) or fatal_error ("Erreur pgSQL : ".pg_result_error ($pgresult),false);
			$sql = "";
			
			foreach (pg_fetch_all($pgresult) as $item)
				{
				$id = $item['id_sinp_jdd'];
				// $id = '4A9DDA1F-B8E4-3E13-E053-2614A8C02B7C';
				$url = $URLAPI_metadata.$id;
				$xmlDoc = new DOMDocument();
				
				/* test l'URL*/
				$file_headers = @get_headers($url);
				if($file_headers[0] != 'HTTP/1.1 404 Introuvable')			
					{
					$xmlDoc->load($url);
					$x = $xmlDoc->documentElement;
		
					/* Initialisation */
					$motcle = "";
					$i = 0;
					$acteur = null;
					$var = array(
						"libelleCourt",
						"description",
						"typeDonnees",
						"objectifJdd",
						"domaineMarin",
						"domaineTerrestre",
						"territoire",
						"protocoles",
						"dateCreation",
						"dateRevision"
						);
					
					/* Récupération des données*/
					foreach ($var as $o)
					{
						$tag = $x->getElementsByTagName($o)->item(0);
						if (!empty($tag)) $val[$o] = $tag->nodeValue;
						else $val[$o] = null;
					}


					foreach ($x->getElementsByTagName('motCle') as $item) {
						  $motcle .= ",".$item->nodeValue;
						}			
					$motcle = ltrim($motcle,',');

					foreach ($x->getElementsByTagName('ActeurType') as $item) {
						$acteur['mail'][$i] = $x->getElementsByTagName('mail')->item(0)->nodeValue; 
						$acteur['nomPrenom'][$i] = $x->getElementsByTagName('mail')->item(0)->nodeValue; 
						$acteur['roleActeur'][$i] = $x->getElementsByTagName('roleActeur')->item(0)->nodeValue; 
						$acteur['organisme'][$i] = $x->getElementsByTagName('organisme')->item(0)->nodeValue;
						$i++;
					}	

					/* Mise à jour SQL - JDD */
					$sql .= "UPDATE nomenc.ref_jdd SET 
						libelleCourt = '".str_replace("'","''",$val["libelleCourt"])."',
						description = '".str_replace("'","''",$val["description"])."',
						typeDonnees = '".$val["typeDonnees"]."',
						objectifJdd = '".$val["objectifJdd"]."',
						domaineMarin = '".$val["domaineMarin"]."',
						domaineTerrestre  = '".$val["domaineTerrestre"]."',
						territoire  = '".$val["territoire"]."',
						protocoles = '".str_replace("'","''",$val["protocoles"])."',
						dateCreation  = '".$val["dateCreation"]."',
						dateRevision = '".$val["dateRevision"]."'
						WHERE id_sinp_jdd = '$id';
						";
					$sql .= "<BR><BR>";
					// pg_query ($db,$sql) or fatal_error ("Erreur pgSQL : ".pg_result_error ($pgresult),false);
					}
					
					/* Mise à jour SQL - Acteur */
					/* Vérification de l'existant à partir de la PK */
					$sql = "SELECT 1 FROM nomenc.ref_org_jdd WHERE id_jdd = ".$val["objectifJdd"]." AND id_org = '".$acteur['organisme'][$i]."';";
					
					/* Si non existant = INSERT */
					// INSERT INTO nomenc.ref_org_jdd(
					// id_jdd, typ_org, id_org, lib_org, nom_contact)
					// VALUES (?, ?, ?, ?, ?);
					
					/* Si existant = UPDATE */
					// UPDATE nomenc.ref_org_jdd
					// SET id_jdd=?, typ_org=?, id_org=?, lib_org=?, nom_contact=?
					// WHERE <condition>;
					
					
				}
					
		break;
	}	
	// return $pgresult;
	return $sql;
	// return "Ok";
}


?>