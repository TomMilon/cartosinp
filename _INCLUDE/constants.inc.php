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

// STYLE                                                                              //
//------------------------------------------------------------------------------//
// Habilitation
$style[1]['Dossier non déposé'] = '{"color": "#cfcfcf","weight": 5,"opacity": 0.65}';
$style[1]['Habilitation en cours'] = '{"color": "#00cc60","weight": 5,"opacity": 0.65}';
$style[1]['plateforme habilitée'] = '{"color": "#ff7800","weight": 5,"opacity": 0.65}';
$style[1]['plateforme non habilitée'] = '{"color": "#ff0008","weight": 5,"opacity": 0.65}';


?>