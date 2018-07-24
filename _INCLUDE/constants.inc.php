<?php
//------------------------------------------------------------------------------//
//   _INCLUDE/constants.inc.php                                                 //
//                                                                              //
//------------------------------------------------------------------------------//
global $URLAPI_organisme;
$URLAPI_organisme = "https://odata-inpn.mnhn.fr/solr-ws/organismes/records?wt=json";
$URLAPI_geocode = "https://api-adresse.data.gouv.fr/search/?q=";
$valeur_non_renseigne = "<i>non renseigné</i>";
$valeur_non_applicable = "<i>N/A</i>";
global $limit_json;
$limit_json = "&rows=800";
?>