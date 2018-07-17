CREATE OR REPLACE FUNCTION update_bdd() RETURNS varchar AS 
$BODY$ 
DECLARE phase varchar;
DECLARE user_codex varchar;
BEGIN
----------------------------------------------------
------VARIABLES A DÉFINIR---------------------------
---## Pour tester la fonction. Une fois que vous souhaiter enregistrer la modif dans la table update_bdd, mettre la phase en "prod" ##--
phase = 'test';
-- phase = 'prod';
---## user_codex est l'utilisateur du codex (décommentez la ligne suivante si besoin) ##--
-- user_codex = 'pg_user';
----------------------------------------------------


--- 1. Code permettant la mise à jour
-- ex : update...
-- ex : insert...


--- 2. Pour le suivi des mises à jours
INSERT INTO applications.update_bdd (id, commit, descr, date) VALUES (
	--- Numero du fichier (à modifier)
	'0xx',
	--- Numéro du dernier commit (à modifier)
	'd6b1c1215bc1c79fa78ea9978d60e686ded8de1b', 
	--- Description de la modif BDD (à modifier)
	'Ajout de la description lors de la mise à jour de la base de donnée',
	--- Date (à ne pas modifier)
	NOW());

RETURN 'OK';END;$BODY$ LANGUAGE plpgsql;SELECT * FROM update_bdd();