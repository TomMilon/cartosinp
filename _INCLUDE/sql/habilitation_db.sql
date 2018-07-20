-- Database: habilitation

-- DROP DATABASE habilitation;
/*
CREATE DATABASE habilitation
    WITH 
    OWNER = postgres
    ENCODING = 'UTF8'
    LC_COLLATE = 'French_France.1252'
    LC_CTYPE = 'French_France.1252'
    TABLESPACE = pg_default
    CONNECTION LIMIT = -1;
*/
----------------Schema habilitation -------------

CREATE OR REPLACE FUNCTION public.create_db() RETURNS integer AS 
$BODY$ 
BEGIN
DROP SCHEMA IF EXISTS hab CASCADE;
CREATE SCHEMA hab AUTHORIZATION postgres;
CREATE TABLE hab.plateforme
(
    id_ptf serial NOT NULL,
    nom_region character varying,
	dynq_general  character varying,
	dynq_desc  character varying,
	mission_ptf1_rea character varying,
	mission_ptf2_rea character varying,
	mission_ptf3_rea character varying,
	mission_ptf4_rea character varying,
	mission_ptf5_rea character varying,
	mission_ptf6_rea character varying,
	mission_ptf7_rea character varying,
	mission_ptf1_com character varying,
	mission_ptf2_com character varying,
	mission_ptf3_com character varying,
	mission_ptf4_com character varying,
	mission_ptf5_com character varying,
	mission_ptf6_com character varying,
	mission_ptf7_com character varying,
    PRIMARY KEY (id_ptf)
)
WITH (OIDS = FALSE);
ALTER TABLE hab.plateforme OWNER to postgres;

CREATE TABLE hab.habilitation
(
    id_ptf integer NOT NULL,
	referent_nom character varying,
	date_depot date,
	resp_instrct_nom character varying,
	date_instruction date,
	hab_avis  character varying,
	hab_complement character varying,
	date_avis date,
	hab_decision character varying,
	hab_condition character varying,
	date_decision date,
	crit1_rea character varying,
	crit2_rea character varying,
	crit3_rea character varying,
	crit4_rea character varying,
	crit5_rea character varying,
	crit6_rea character varying,
	crit7_rea character varying,
	crit8_rea character varying,
	crit9_rea character varying,
	crit10_rea character varying,
	crit11_rea character varying,
	crit12_rea character varying,
	crit1_desc character varying,
	crit2_desc character varying,
	crit3_desc character varying,
	crit4_desc character varying,
	crit5_desc character varying,
	crit6_desc character varying,
	crit7_desc character varying,
	crit8_desc character varying,
	crit9_desc character varying,
	crit10_desc character varying,
	crit11_desc character varying,
	crit12_desc character varying,
    PRIMARY KEY (id_ptf)
)
WITH (OIDS = FALSE);
ALTER TABLE hab.habilitation OWNER to postgres;


CREATE TABLE hab.pilote
(
    id_ptf integer NOT NULL,
	id_pilote serial NOT NULL,
	pilote_nom character varying,
	pilote_org_nom character varying,
	pilote_org_id character varying,
    PRIMARY KEY (id_ptf,id_pilote)
)
WITH (OIDS = FALSE);
ALTER TABLE hab.pilote OWNER to postgres;

CREATE TABLE hab.piece_jointe
(
    id_ptf integer NOT NULL,
	id_pj serial NOT NULL,
	pj_nom character varying,
	pj_desc character varying,
	pj_type character varying,
	pj_ressource character varying,
    PRIMARY KEY (id_ptf,id_pj)
)
WITH (OIDS = FALSE);
ALTER TABLE hab.piece_jointe OWNER to postgres;

CREATE TABLE hab.comite
(
    id_ptf integer NOT NULL,
	id_comite serial NOT NULL,
	comite_nom character varying,
	comite_description character varying,
	comite_dynq character varying,
    PRIMARY KEY (id_ptf,id_comite)
)
WITH (OIDS = FALSE);
ALTER TABLE hab.comite OWNER to postgres;

CREATE TABLE hab.comite_role
(
    id_comite integer NOT NULL,
	id_nmc character varying NOT NULL,
    PRIMARY KEY (id_comite,id_nmc)
)
WITH (OIDS = FALSE);
ALTER TABLE hab.comite_role OWNER to postgres;

CREATE TABLE hab.reseau
(
    id_ptf integer NOT NULL,
	id_reseau serial NOT NULL,
	reseau_nom character varying,
	reseau_org_nom character varying,
	reseau_org_id character varying,
	reseau_dynq character varying,
	reseau_peri_geo character varying,
    PRIMARY KEY (id_ptf,id_reseau)
)
WITH (OIDS = FALSE);
ALTER TABLE hab.reseau OWNER to postgres;

CREATE TABLE hab.reseau_peri_taxo
(
    id_reseau integer NOT NULL,
	id_nmc character varying NOT NULL,
    PRIMARY KEY (id_reseau,id_nmc)
)
WITH (OIDS = FALSE);
ALTER TABLE hab.reseau_peri_taxo OWNER to postgres;

CREATE TABLE hab.outil
(
    id_ptf integer NOT NULL,
	id_outil serial NOT NULL,
	outil_nom character varying,
	outil_dynq character varying,
	outil_url character varying,
	outil_org_nom character varying,
	outil_org_id character varying,
	outil_desc character varying,	
	fct_outil_1 character varying,
	fct_outil_2 character varying,
	fct_outil_3 character varying,
	fct_outil_4 character varying,
	fct_outil_5 character varying,
	fct_outil_6 character varying,
	fct_outil_7 character varying,
	fct_outil_8 character varying,
	fct_outil_9 character varying,
	fct_outil_10 character varying,
	fct_outil_11 character varying,
	fct_outil_12 character varying,
	fct_outil_13 character varying,
	fct_outil_14 character varying,
	fct_outil_15 character varying,
	fct_outil_16 character varying,
	fct_outil_17 character varying,
	fct_outil_18 character varying,
	fct_outil_19 character varying,
    PRIMARY KEY (id_ptf,id_outil)
)
WITH (OIDS = FALSE);
ALTER TABLE hab.outil OWNER to postgres;

CREATE TABLE hab.interface
(
    id_ptf integer NOT NULL,
	id_interf serial NOT NULL,
	interf_outil character varying,
	interf_org_nom character varying,
	interf_org_id character varying,
	interf_contact_org_nom character varying,
	interf_contact_org_id character varying,
	interf_contact_mail character varying,
	interf_contact_tel character varying,
	interf_teleservice character varying,
	interf_ptf_nat character varying,
    PRIMARY KEY (id_ptf,id_interf)
)
WITH (OIDS = FALSE);
ALTER TABLE hab.interface OWNER to postgres;

-------- Nomenclatures----------

DROP SCHEMA IF EXISTS nomenc CASCADE;
CREATE SCHEMA nomenc AUTHORIZATION postgres;


CREATE TABLE nomenc.hab_avis(id_nmc serial NOT NULL,lib_nmc character varying NOT NULL,	val_nmc character varying NOT NULL, PRIMARY KEY (id_nmc)) WITH (OIDS = FALSE);
ALTER TABLE nomenc.hab_avis OWNER to postgres;
INSERT INTO nomenc.hab_avis VALUES
	(DEFAULT, 'ok', 'FAVORABLE'),
	(DEFAULT, 'nok', 'DÉFAVORABLE');

CREATE TABLE nomenc.hab_decision(id_nmc serial NOT NULL,lib_nmc character varying NOT NULL,	val_nmc character varying NOT NULL, PRIMARY KEY (id_nmc)) WITH (OIDS = FALSE);
ALTER TABLE nomenc.hab_decision OWNER to postgres;
INSERT INTO nomenc.hab_decision VALUES
	(DEFAULT, 'ok', 'plateforme habilitée'),
	(DEFAULT, 'nok', 'plateforme non habilitée');

CREATE TABLE nomenc.dynq_general(id_nmc serial NOT NULL,lib_nmc character varying NOT NULL,	val_nmc character varying NOT NULL, PRIMARY KEY (id_nmc)) WITH (OIDS = FALSE);
ALTER TABLE nomenc.dynq_general OWNER to postgres;
INSERT INTO nomenc.dynq_general VALUES
	(DEFAULT, 'pref', 'Préfiguration'),
	(DEFAULT, 'cons', 'En construction'),
	(DEFAULT, 'part', 'Partiellement fonctionnelle'),
	(DEFAULT, 'rel', 'Relance'),
	(DEFAULT, 'reor', 'Réorganisation'),
	(DEFAULT, 'fct', 'Fonctionnelle');
	
CREATE TABLE nomenc.mission_ptf_rea(id_nmc serial NOT NULL,lib_nmc character varying NOT NULL,	val_nmc character varying NOT NULL, PRIMARY KEY (id_nmc)) WITH (OIDS = FALSE);
ALTER TABLE nomenc.mission_ptf_rea OWNER to postgres;
INSERT INTO nomenc.mission_ptf_rea VALUES
	(DEFAULT, 'ok', 'oui'),
	(DEFAULT, 'part', 'partiellement'),
	(DEFAULT, 'nok', 'non');
	
CREATE TABLE nomenc.crit_rea(id_nmc serial NOT NULL,lib_nmc character varying NOT NULL,	val_nmc character varying NOT NULL, PRIMARY KEY (id_nmc)) WITH (OIDS = FALSE);
ALTER TABLE nomenc.crit_rea OWNER to postgres;
INSERT INTO nomenc.crit_rea VALUES
	(DEFAULT, 'ok', 'oui'),
	(DEFAULT, 'part', 'partiellement'),
	(DEFAULT, 'nok', 'non');

CREATE TABLE nomenc.comite_dynq(id_nmc serial NOT NULL,lib_nmc character varying NOT NULL,	val_nmc character varying NOT NULL, PRIMARY KEY (id_nmc)) WITH (OIDS = FALSE);
ALTER TABLE nomenc.comite_dynq OWNER to postgres;
INSERT INTO nomenc.comite_dynq VALUES
	(DEFAULT, 'ninst', 'non installé'),
	(DEFAULT, 'refx', 'en réflexion'),
	(DEFAULT, 'const', 'en construction'),
	(DEFAULT, 'exist', 'existant'),
	(DEFAULT, 'actif', 'actif'),
	(DEFAULT, 'somm', 'en sommeil'),
	(DEFAULT, 'rel', 'relance');	

CREATE TABLE nomenc.comite_role(id_nmc serial NOT NULL,lib_nmc character varying NOT NULL,	val_nmc character varying NOT NULL, PRIMARY KEY (id_nmc)) WITH (OIDS = FALSE);
ALTER TABLE nomenc.comite_role OWNER to postgres;
INSERT INTO nomenc.comite_role VALUES
	(DEFAULT, '1', 'Définit les grandes orientations de la plateforme'),
	(DEFAULT, '2', 'Suit la mise en œuvre des plans d actions'),
	(DEFAULT, '3', 'Se prononce sur l adhésion à la plateforme'),
	(DEFAULT, '4', 'Porte la responsabilité scientifique de la plateforme'),
	(DEFAULT, '5', 'S assure de la compatibilité technique / interopérabilité de la plateforme'),
	(DEFAULT, '6', 'Accompagne les acteurs de la plateforme dans leur action'),
	(DEFAULT, '7', 'Organise les partages d expériences');

CREATE TABLE nomenc.reseau_dynq(id_nmc serial NOT NULL,lib_nmc character varying NOT NULL,	val_nmc character varying NOT NULL, PRIMARY KEY (id_nmc)) WITH (OIDS = FALSE);
ALTER TABLE nomenc.reseau_dynq OWNER to postgres;
INSERT INTO nomenc.reseau_dynq VALUES
	(DEFAULT, 'refx', 'en réflexion'),
	(DEFAULT, 'depl', 'en cours de déploiement'),
	(DEFAULT, 'inst', 'installé'),
	(DEFAULT, 'actif', 'actif'),
	(DEFAULT, 'redef', 'redéfinition'),
	(DEFAULT, 'rel', 'relance');

CREATE TABLE nomenc.reseau_peri_taxo(id_nmc serial NOT NULL,lib_nmc character varying NOT NULL,	val_nmc character varying NOT NULL, PRIMARY KEY (id_nmc)) WITH (OIDS = FALSE);
ALTER TABLE nomenc.reseau_peri_taxo OWNER to postgres;
INSERT INTO nomenc.reseau_peri_taxo VALUES
	(DEFAULT, 'fa_par', 'Faune (partiel)'),
	(DEFAULT, 'fa_tot', 'Faune (total)'),
	(DEFAULT, 'fl_part', 'Flore (partiel)'),
	(DEFAULT, 'fl_tot', 'Flore (total)'),
	(DEFAULT, 'fo_part', 'Fonge (partiel)'),
	(DEFAULT, 'fo_tot', 'Fonge (total)');

CREATE TABLE nomenc.reseau_peri_geo(id_nmc serial NOT NULL,lib_nmc character varying NOT NULL,	val_nmc character varying NOT NULL, PRIMARY KEY (id_nmc)) WITH (OIDS = FALSE);
ALTER TABLE nomenc.reseau_peri_geo OWNER to postgres;
INSERT INTO nomenc.reseau_peri_geo VALUES
	(DEFAULT, 'reg', 'régional'),
	(DEFAULT, 'infra_reg', 'infra-régional');	

CREATE TABLE nomenc.outil_dynq(id_nmc serial NOT NULL,lib_nmc character varying NOT NULL,	val_nmc character varying NOT NULL, PRIMARY KEY (id_nmc)) WITH (OIDS = FALSE);
ALTER TABLE nomenc.outil_dynq OWNER to postgres;
INSERT INTO nomenc.outil_dynq VALUES
	(DEFAULT, 'refx', 'en réflexion'),
	(DEFAULT, 'spec', 'en spécification'),
	(DEFAULT, 'demo', 'démonstrateur'),
	(DEFAULT, 'dev', 'en développement'),
	(DEFAULT, 'test', 'en test'),
	(DEFAULT, 'prod', 'en production');	

CREATE TABLE nomenc.fct_outil(id_nmc serial NOT NULL,lib_nmc character varying NOT NULL,	val_nmc character varying NOT NULL, PRIMARY KEY (id_nmc)) WITH (OIDS = FALSE);
ALTER TABLE nomenc.fct_outil OWNER to postgres;
INSERT INTO nomenc.fct_outil VALUES
	(DEFAULT, 'ok', 'oui'),
	(DEFAULT, 'part', 'partiellement'),
	(DEFAULT, 'na', 'N/A');	
	
CREATE TABLE nomenc.pj_type(id_nmc serial NOT NULL,lib_nmc character varying NOT NULL,	val_nmc character varying NOT NULL, PRIMARY KEY (id_nmc)) WITH (OIDS = FALSE);
ALTER TABLE nomenc.pj_type OWNER to postgres;
INSERT INTO nomenc.pj_type VALUES
	(DEFAULT, 'charte', 'Charte régional SINP'),
	(DEFAULT, 'std_reg', 'Standard régional SINP'),
	(DEFAULT, 'ref_sensi', 'Référentiel de sensibilité'),
	(DEFAULT, 'autre', 'Autre document');	

---- LISTES -----
CREATE TABLE nomenc.fct_outil_desc(id_nmc serial NOT NULL,lib_nmc character varying NOT NULL,	val_nmc character varying NOT NULL, PRIMARY KEY (id_nmc)) WITH (OIDS = FALSE);
ALTER TABLE nomenc.fct_outil_desc OWNER to postgres;
INSERT INTO nomenc.fct_outil_desc VALUES
	(DEFAULT,'fct_outil_1','Saisie de données'),
	(DEFAULT,'fct_outil_2','Standardisation/transformation de format'),
	(DEFAULT,'fct_outil_3','Gestion des métadonnées'),
	(DEFAULT,'fct_outil_4','Contrôles de conformité'),
	(DEFAULT,'fct_outil_5','Contrôles de cohérence'),
	(DEFAULT,'fct_outil_6','Gestion de l''identifiant permanent'),
	(DEFAULT,'fct_outil_7','Rattachement aux entités géo-administratives'),
	(DEFAULT,'fct_outil_8','Gestion d’un annuaire des organismes'),
	(DEFAULT,'fct_outil_9','Attribution et gestion de la sensibilité'),
	(DEFAULT,'fct_outil_10','Floutage des données'),
	(DEFAULT,'fct_outil_11','Gestion des doublons'),
	(DEFAULT,'fct_outil_12','Gestion des permissions et droits utilisateurs'),
	(DEFAULT,'fct_outil_13','Gestion de la validation scientifique'),
	(DEFAULT,'fct_outil_14','Visualisation des données'),
	(DEFAULT,'fct_outil_15','Visualisation de métadonnées'),
	(DEFAULT,'fct_outil_16','Téléchargement des données'),
	(DEFAULT,'fct_outil_17','Téléchargement de métadonnées'),
	(DEFAULT,'fct_outil_18','Gestion des demandes de communication'),
	(DEFAULT,'fct_outil_19','Partage avec la plateforme nationale');
	

CREATE TABLE nomenc.carto_question(id_nmc serial NOT NULL,lib_nmc character varying NOT NULL,	val_nmc character varying NOT NULL, PRIMARY KEY (id_nmc)) WITH (OIDS = FALSE);
ALTER TABLE nomenc.carto_question OWNER to postgres;
INSERT INTO nomenc.carto_question VALUES
	(DEFAULT,'1','Quelles sont les plateformes en cours d’habilitation/habilitées SINP?'),
	(DEFAULT,'2','Quelles sont les dynamiques des plateformes ainsi que leur pérennité ?'),
	(DEFAULT,'3','Quelles sont les plateformes qui possèdent une charte SINP compatible avec le protocole SINP?'),
	(DEFAULT,'4','Quelles sont les plateformes qui possèdent un standard de données régional?'),
	(DEFAULT,'5','Quelles sont les plateformes qui échangent leurs données avec la plateforme nationale?'),
	(DEFAULT,'6','Quelles sont les plateformes qui organisent la validation scientifique des données?'),
	(DEFAULT,'7','Quelles sont les plateformes qui possèdent un référentiel régional de sensibilité?'),
	(DEFAULT,'8','Quels sont les organismes qui participent au SINP? Quel est le rôle de ces organismes dans le SINP?'),
	(DEFAULT,'9','Quels sont les organismes adhérents au protocole SINP?'),
	(DEFAULT,'10','Quelles sont les données produites/gérées/financées par ces organismes?'),
	(DEFAULT,'11','Quels sont les outils utilisés  par les plateformes dans le cadre du SINP? Quelle plateforme utilise/gère ces outils?'),
	(DEFAULT,'12','Quelles sont les fonctionnalités remplies par ces outils?'),
	(DEFAULT,'13','Quels sont les jeux de données disponibles dans le SINP ? Dans l’INPN ?'),
	(DEFAULT,'14','Quels sont les organismes impliqués dans ces jeux de données ?'),
	(DEFAULT,'15','Quel est le « chemin parcouru » par ces jeux de données ?')

CREATE TABLE nomenc.type_organisme(id_nmc serial NOT NULL,lib_nmc character varying NOT NULL,	val_nmc character varying NOT NULL, PRIMARY KEY (id_nmc)) WITH (OIDS = FALSE);
ALTER TABLE nomenc.type_organisme OWNER to postgres;
INSERT INTO nomenc.type_organisme VALUES
	(DEFAULT,'1','Administration'),
	(DEFAULT,'2','Association'),
	(DEFAULT,'3','Entreprise privée'),
	(DEFAULT,'4','Entreprise privée individuelle'),
	(DEFAULT,'5','Etablissement public'),
	(DEFAULT,'6','Structure interne'),
	(DEFAULT,'7','Regroupement d''organismes');


CREATE TABLE nomenc.statut_organisme(id_nmc serial NOT NULL,lib_nmc character varying NOT NULL,	val_nmc character varying NOT NULL, PRIMARY KEY (id_nmc)) WITH (OIDS = FALSE);
ALTER TABLE nomenc.statut_organisme OWNER to postgres;
INSERT INTO nomenc.statut_organisme VALUES
	(DEFAULT,'0','Inconnu'),
	(DEFAULT,'1','Public'),
	(DEFAULT,'2','Privé');
	
CREATE TABLE nomenc.perimetre_action(id_nmc serial NOT NULL,lib_nmc character varying NOT NULL,	val_nmc character varying NOT NULL, PRIMARY KEY (id_nmc)) WITH (OIDS = FALSE);
ALTER TABLE nomenc.perimetre_action OWNER to postgres;
INSERT INTO nomenc.perimetre_action VALUES
	(DEFAULT,'1','Européen'),
	(DEFAULT,'2','National'),
	(DEFAULT,'3','Suprarégional'),
	(DEFAULT,'4','Régional'),
	(DEFAULT,'5','Inconnu');


CREATE TABLE nomenc.niveau_adhesion(id_nmc serial NOT NULL,lib_nmc character varying NOT NULL,	val_nmc character varying NOT NULL, PRIMARY KEY (id_nmc)) WITH (OIDS = FALSE);
ALTER TABLE nomenc.niveau_adhesion OWNER to postgres;
INSERT INTO nomenc.niveau_adhesion VALUES
	(DEFAULT,'0','NSP : ne sait pas'),
	(DEFAULT,'1','Adhésion'),
	(DEFAULT,'2','Préadhésion'),
	(DEFAULT,'3','Non adhérent');
	
CREATE TABLE nomenc.domaine_connaissance(id_nmc serial NOT NULL,lib_nmc character varying NOT NULL,	val_nmc character varying NOT NULL, PRIMARY KEY (id_nmc)) WITH (OIDS = FALSE);
ALTER TABLE nomenc.domaine_connaissance OWNER to postgres;
INSERT INTO nomenc.domaine_connaissance VALUES
	(DEFAULT,'1','Faune'),
	(DEFAULT,'2','Fonge'),
	(DEFAULT,'3','Flore'),
	(DEFAULT,'4','Habitats');

CREATE TABLE nomenc.zone_geographique(id_nmc serial NOT NULL,lib_nmc character varying NOT NULL,	val_nmc character varying NOT NULL, PRIMARY KEY (id_nmc)) WITH (OIDS = FALSE);
ALTER TABLE nomenc.zone_geographique OWNER to postgres;
INSERT INTO nomenc.zone_geographique VALUES
	(DEFAULT,'1','Guadeloupe'),
	(DEFAULT,'2','Martinique'),
	(DEFAULT,'3','Guyane'),
	(DEFAULT,'4','La Réunion'),
	(DEFAULT,'6','Mayotte'),
	(DEFAULT,'11','Île-de-France'),
	(DEFAULT,'24','Centre-Val de Loire'),
	(DEFAULT,'27','Bourgogne-Franche-Comté'),
	(DEFAULT,'28','Normandie'),
	(DEFAULT,'32','Nord-Pas-de-Calais-Picardie'),
	(DEFAULT,'44','Alsace-Champagne-Ardenne-Lorraine'),
	(DEFAULT,'52','Pays de la Loire'),
	(DEFAULT,'53','Bretagne'),
	(DEFAULT,'75','Aquitaine-Limousin-Poitou-Charentes'),
	(DEFAULT,'76','Languedoc-Roussillon-Midi-Pyrénées'),
	(DEFAULT,'84','Auvergne-Rhône-Alpes'),
	(DEFAULT,'93','Provence-Alpes-Côte d''Azur'),
	(DEFAULT,'94','Corse'),
	(DEFAULT,'21','Champagne-Ardenne'),
	(DEFAULT,'22','Picardie'),
	(DEFAULT,'23','Haute-Normandie'),
	(DEFAULT,'45','Basse-Normandie'),
	(DEFAULT,'26','Bourgogne'),
	(DEFAULT,'31','Nord-Pas-de-Calais'),
	(DEFAULT,'41','Lorraine'),
	(DEFAULT,'42','Alsace'),
	(DEFAULT,'43','Franche-Comté'),
	(DEFAULT,'54','Poitou-Charentes'),
	(DEFAULT,'72','Aquitaine'),
	(DEFAULT,'73','Midi-Pyrénées'),
	(DEFAULT,'44','Limousin'),
	(DEFAULT,'82','Rhône-Alpes'),
	(DEFAULT,'83','Auvergne'),
	(DEFAULT,'91','Languedoc-Roussillon');
 
 
CREATE TABLE nomenc.role_org(id_nmc serial NOT NULL,lib_nmc character varying NOT NULL,	val_nmc character varying NOT NULL, PRIMARY KEY (id_nmc)) WITH (OIDS = FALSE);
ALTER TABLE nomenc.role_org OWNER to postgres;
INSERT INTO nomenc.role_org VALUES
	(DEFAULT,'1','pilote'),
	(DEFAULT,'2','porteur d''outil'),
	(DEFAULT,'3','tête de réseau'),
	(DEFAULT,'4','contact données');

	
----------------Schema temporaire -------------

DROP SCHEMA IF EXISTS temp CASCADE;
CREATE SCHEMA temp AUTHORIZATION postgres;
	
CREATE TABLE temp.plateforme
(
    nom_region character varying,
	dynq_general  character varying,
	dynq_desc  character varying,
	mission_ptf1_rea character varying,
	mission_ptf2_rea character varying,
	mission_ptf3_rea character varying,
	mission_ptf4_rea character varying,
	mission_ptf5_rea character varying,
	mission_ptf6_rea character varying,
	mission_ptf7_rea character varying,
	mission_ptf1_com character varying,
	mission_ptf2_com character varying,
	mission_ptf3_com character varying,
	mission_ptf4_com character varying,
	mission_ptf5_com character varying,
	mission_ptf6_com character varying,
	mission_ptf7_com character varying
)
WITH (OIDS = FALSE);

ALTER TABLE temp.plateforme OWNER to postgres;

CREATE TABLE temp.habilitation
(
	referent_nom character varying,
	date_depot character varying,
	resp_instrct_nom character varying,
	date_instruction character varying,
	hab_avis  character varying,
	hab_complement character varying,
	date_avis character varying,
	hab_decision character varying,
	hab_condition character varying,
	date_decision character varying,
	crit1_rea character varying,
	crit2_rea character varying,
	crit3_rea character varying,
	crit4_rea character varying,
	crit5_rea character varying,
	crit6_rea character varying,
	crit7_rea character varying,
	crit8_rea character varying,
	crit9_rea character varying,
	crit10_rea character varying,
	crit11_rea character varying,
	crit12_rea character varying,
	crit1_desc character varying,
	crit2_desc character varying,
	crit3_desc character varying,
	crit4_desc character varying,
	crit5_desc character varying,
	crit6_desc character varying,
	crit7_desc character varying,
	crit8_desc character varying,
	crit9_desc character varying,
	crit10_desc character varying,
	crit11_desc character varying,
	crit12_desc character varying
)
WITH (OIDS = FALSE);
ALTER TABLE temp.habilitation OWNER to postgres;


CREATE TABLE temp.pilote
(
	pilote_nom character varying,
	pilote_org character varying
)
WITH (OIDS = FALSE);
ALTER TABLE temp.pilote OWNER to postgres;

CREATE TABLE temp.piece_jointe
(
	pj_nom character varying,
	pj_desc character varying,
	pj_type character varying,
	pj_ressource character varying
)
WITH (OIDS = FALSE);
ALTER TABLE temp.piece_jointe OWNER to postgres;

CREATE TABLE temp.comite
(
	comite_nom character varying,
	comite_description character varying,
	comite_dynq character varying
)
WITH (OIDS = FALSE);
ALTER TABLE temp.comite OWNER to postgres;

CREATE TABLE temp.comite_role
(
    comite_nom character varying,
	id_nmc integer
)
WITH (OIDS = FALSE);
ALTER TABLE temp.comite_role OWNER to postgres;

CREATE TABLE temp.reseau
(
	reseau_nom character varying,
	reseau_org character varying,
	reseau_dynq character varying,
	reseau_peri_geo character varying
)
WITH (OIDS = FALSE);
ALTER TABLE temp.reseau OWNER to postgres;

CREATE TABLE temp.reseau_peri_taxo
(
	reseau_nom character varying,
	reseau_org character varying,
	id_nmc character varying
)
WITH (OIDS = FALSE);
ALTER TABLE temp.reseau_peri_taxo OWNER to postgres;

CREATE TABLE temp.outil
(
	outil_nom character varying,
	outil_dynq character varying,
	outil_url character varying,
	outil_org character varying,
	outil_desc character varying,
	fct_outil_1 character varying,
	fct_outil_2 character varying,
	fct_outil_3 character varying,
	fct_outil_4 character varying,
	fct_outil_5 character varying,
	fct_outil_6 character varying,
	fct_outil_7 character varying,
	fct_outil_8 character varying,
	fct_outil_9 character varying,
	fct_outil_10 character varying,
	fct_outil_11 character varying,
	fct_outil_12 character varying,
	fct_outil_13 character varying,
	fct_outil_14 character varying,
	fct_outil_15 character varying,
	fct_outil_16 character varying,
	fct_outil_17 character varying,
	fct_outil_18 character varying,
	fct_outil_19 character varying
)
WITH (OIDS = FALSE);
ALTER TABLE temp.outil OWNER to postgres;

CREATE TABLE temp.interface
(
	interf_outil character varying,
	interf_org character varying,
	interf_contact_org character varying,
	interf_contact_mail character varying,
	interf_contact_tel character varying,
	interf_teleservice character varying,
	interf_ptf_nat character varying
)
WITH (OIDS = FALSE);
ALTER TABLE temp.interface OWNER to postgres;
RETURN 1;
END; $BODY$ LANGUAGE plpgsql;	
																															
																
------------------------------ FUNCTIONs-------------------------
																														
---habilitation_refresh_shema
CREATE OR REPLACE FUNCTION hab.truncate_shema(nomSchema varchar) RETURNS integer AS 
$BODY$ 
DECLARE nomTable varchar;
BEGIN
FOR nomTable IN EXECUTE 'SELECT table_name FROM information_schema.tables WHERE table_schema = '''||nomschema||''';'
	LOOP EXECUTE 'TRUNCATE '||nomschema||'.'||nomTable||';';
END LOOP;
RETURN 1;
END; $BODY$ LANGUAGE plpgsql;

---habilitation_refresh_seq
CREATE OR REPLACE FUNCTION hab.refresh_sequence(nomSchema varchar) RETURNS integer AS 
$BODY$ 
DECLARE nomSeq varchar;
BEGIN
FOR nomSeq IN EXECUTE 'SELECT sequence_name FROM information_schema.sequences WHERE sequence_schema = '''||nomSchema||''''
	LOOP EXECUTE 'ALTER SEQUENCE '||nomSchema||'.'||nomSeq||' RESTART WITH 1;';
	END LOOP;
RETURN 1;
END; $BODY$ LANGUAGE plpgsql;	

--- import des données
CREATE OR REPLACE FUNCTION hab.import(path varchar) RETURNS integer AS 
$BODY$ 
DECLARE out varchar;
DECLARE ptf integer;
DECLARE listFiles varchar[];
DECLARE nomFiles varchar;
BEGIN

--- import dans les tables temporaires
listFiles = ARRAY['plateforme','habilitation','piece_jointe','comite','comite_role','reseau','reseau_peri_taxo','pilote','outil','interface'];
FOREACH nomFiles IN ARRAY listFiles 
	LOOP EXECUTE 'COPY temp.'||nomFiles||' FROM '''||path||nomFiles||'.csv'' HEADER CSV ENCODING ''UTF8'' DELIMITER '';''';
END LOOP;

-- plateforme
EXECUTE 'INSERT INTO hab.plateforme (nom_region, dynq_general, dynq_desc, mission_ptf1_rea, mission_ptf2_rea, mission_ptf3_rea, mission_ptf4_rea, mission_ptf5_rea, mission_ptf6_rea, mission_ptf7_rea, mission_ptf1_com, mission_ptf2_com, mission_ptf3_com, mission_ptf4_com, mission_ptf5_com, mission_ptf6_com, mission_ptf7_com) SELECT * FROM temp.plateforme;';
SELECT max(id_ptf) into ptf FROM hab.plateforme;

--- habilitation
EXECUTE 'INSERT INTO hab.habilitation SELECT '||ptf||', referent_nom, date_depot::date, resp_instrct_nom, date_instruction::date, hab_avis, hab_complement, date_avis::date, hab_decision, hab_condition, date_decision::date, crit1_rea, crit2_rea, crit3_rea, crit4_rea, crit5_rea, crit6_rea, crit7_rea, crit8_rea, crit9_rea, crit10_rea, crit11_rea, crit12_rea, crit1_desc, crit2_desc, crit3_desc, crit4_desc, crit5_desc, crit6_desc, crit7_desc, crit8_desc, crit9_desc, crit10_desc, crit11_desc, crit12_desc FROM temp.habilitation;';

--- piece jointe
EXECUTE 'INSERT INTO hab.piece_jointe (id_ptf, pj_nom, pj_desc, pj_type, pj_ressource) SELECT '||ptf||', pj_nom, pj_desc, pj_type, pj_ressource FROM temp.piece_jointe;';

--- comite
EXECUTE 'INSERT INTO hab.comite (id_ptf, comite_nom, comite_description, comite_dynq) SELECT '||ptf||', comite_nom, comite_description, comite_dynq FROM temp.comite;';

--- comite_role
EXECUTE 'INSERT INTO hab.comite_role (id_comite,id_nmc) SELECT a.id_comite, id_nmc FROM hab.comite a JOIN temp.comite_role z ON a.comite_nom=z.comite_nom WHERE id_ptf = '||ptf||' ;';

--- reseau
EXECUTE 'INSERT INTO hab.reseau (id_ptf, reseau_nom, reseau_org, reseau_dynq, reseau_peri_geo) SELECT '||ptf||', reseau_nom, reseau_org, reseau_dynq, reseau_peri_geo FROM temp.reseau;';

--- reseau_peri_taxo
EXECUTE 'INSERT INTO hab.reseau_peri_taxo (id_reseau, id_nmc) SELECT a.id_reseau, z.id_nmc FROM hab.reseau a JOIN temp.reseau_peri_taxo z ON a.reseau_nom=z.reseau_nom AND a.reseau_org = z.reseau_org  WHERE id_ptf = '||ptf||' ;';

-- pilote
EXECUTE 'INSERT INTO hab.pilote (id_ptf, pilote_nom, pilote_org) SELECT '||ptf||', pilote_nom, pilote_org FROM temp.pilote;';

-- outil
EXECUTE 'INSERT INTO hab.outil (id_ptf, outil_nom, outil_dynq, outil_url, outil_org, fct_outil_1, fct_outil_2, fct_outil_3, fct_outil_4, fct_outil_5, fct_outil_6, fct_outil_7, fct_outil_8, fct_outil_9, fct_outil_10, fct_outil_11, fct_outil_12, fct_outil_13, fct_outil_14, fct_outil_15, fct_outil_16, fct_outil_17, fct_outil_18, fct_outil_19) SELECT '||ptf||', outil_nom, outil_dynq, outil_url, outil_org, fct_outil_1, fct_outil_2, fct_outil_3, fct_outil_4, fct_outil_5, fct_outil_6, fct_outil_7, fct_outil_8, fct_outil_9, fct_outil_10, fct_outil_11, fct_outil_12, fct_outil_13, fct_outil_14, fct_outil_15, fct_outil_16, fct_outil_17, fct_outil_18, fct_outil_19 FROM temp.outil;';

-- interface
EXECUTE 'INSERT INTO hab.interface (id_ptf, interf_outil, interf_org, interf_contact_org, interf_contact_mail, interf_contact_tel, interf_teleservice, interf_ptf_nat) SELECT '||ptf||',  interf_outil, interf_org, interf_contact_org, interf_contact_mail, interf_contact_tel, interf_teleservice, interf_ptf_nat FROM temp.interface;';

PERFORM hab.truncate_shema('temp');
RETURN 1;
END; $BODY$ LANGUAGE plpgsql;
