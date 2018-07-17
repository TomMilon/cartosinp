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
}

?>