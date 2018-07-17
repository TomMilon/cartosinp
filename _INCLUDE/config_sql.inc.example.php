<?php
//------------------------------------------------------------------------------//
//    /_INCLUDE/CONFIG_SQL.INC.PHP                                              //
//                                                                              //
//  Application WEB 'EVAL'                                                      //
//  Outil d’aide à l’évaluation de la flore                                     //
//                                                                              //
//  Version 1.00  10/08/14 - DariaNet                                           //
//  Version 1.01  13/08/14 - MaJ schémas                                        //
//  Version 1.02  18/08/14 - Aj sql_format_num                                  //
//  Renseigner les paramètre de connexion et remplacer le nom du fichier par confi_sql.inc.php//
//------------------------------------------------------------------------------//

    define ("ON_Server", "no");

	// BDD
    define ("SQL_server", "");
    define ("SQL_port", "");
    define ("SQL_base", "");

	// User public    
	define ("SQL_user", "");
    define ("SQL_pass", "");	
	// User admin
	define ("SQL_admin_user", "");
    define ("SQL_admin_pass", "");
	
	// Data path
	define ("DATA_path", "../_DATA/");


?>
