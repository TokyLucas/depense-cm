CREATE TABLE rh_cm_local.bareme ( 
	id                   int  NOT NULL  AUTO_INCREMENT  PRIMARY KEY,
	datebareme           date      ,
	categorie            int      ,
	indice               int      ,
	v500                 decimal(15,2)      ,
	v501                 decimal(15,2)      ,
	v502                 decimal(15,2)      ,
	v503                 decimal(15,2)      ,
	v506                 decimal(15,2)      ,
	solde                decimal(15,2)      
 );

CREATE TABLE rh_cm_local.charges_social ( 
	id                   int  NOT NULL  AUTO_INCREMENT  PRIMARY KEY,
	designation          varchar(25)      
 );

CREATE TABLE rh_cm_local.contrat ( 
	id                   int  NOT NULL  AUTO_INCREMENT  PRIMARY KEY,
	designation          varchar(5)      ,
	duree                int      
 );

CREATE TABLE rh_cm_local.directions ( 
	id                   int  NOT NULL  AUTO_INCREMENT  PRIMARY KEY,
	designation          varchar(25)      
 );

CREATE TABLE rh_cm_local.indemnite ( 
	id                   int  NOT NULL  AUTO_INCREMENT  PRIMARY KEY,
	designation          varchar(15)      
 );

CREATE TABLE rh_cm_local.personnel_chargesocial ( 
	id                   int  NOT NULL  AUTO_INCREMENT  PRIMARY KEY,
	personnel_id         int      ,
	chargesocial_id      int      ,
	montant              decimal(15,2)      
 );

CREATE TABLE rh_cm_local.roles ( 
	id                   int  NOT NULL  AUTO_INCREMENT  PRIMARY KEY,
	designation          varchar(50)      
 );

CREATE TABLE rh_cm_local.service ( 
	id                   int  NOT NULL  AUTO_INCREMENT  PRIMARY KEY,
	designation          varchar(10)      
 );

CREATE TABLE rh_cm_local.sexe ( 
	id                   int  NOT NULL  AUTO_INCREMENT  PRIMARY KEY,
	designation          varchar(10)      
 );

CREATE TABLE rh_cm_local.situationmatrimoniale ( 
	id                   int  NOT NULL  AUTO_INCREMENT  PRIMARY KEY,
	designation          varchar(10)      
 );

CREATE TABLE rh_cm_local.users ( 
	id                   int  NOT NULL  AUTO_INCREMENT  PRIMARY KEY,
	identifiant          varchar(50)      ,
	motdepasse           varchar(60)      
 );

CREATE TABLE rh_cm_local.personnel ( 
	id                   int  NOT NULL  AUTO_INCREMENT  PRIMARY KEY,
	nom                  varchar(50)      ,
	prenom               varchar(50)      ,
	cin                  varchar(15)      ,
	datedenaissance      date      ,
	nbenfant             int      ,
	situationmatrimonial_id int      ,
	lieudenaissance      int      ,
	sexe_id              int      ,
	CONSTRAINT unq_cin UNIQUE ( cin ) 
 );

CREATE TABLE rh_cm_local.personnel_indemnite ( 
	id                   int  NOT NULL  AUTO_INCREMENT  PRIMARY KEY,
	personnel_id         int      ,
	indemnite_id         int      ,
	montant              decimal(15,2)      
 );

CREATE TABLE rh_cm_local.poste ( 
	id                   int  NOT NULL  AUTO_INCREMENT  PRIMARY KEY,
	contrat_id           int      ,
	categorie            int      ,
	indice               int      ,
	direction_id         int      ,
	service_id           int      ,
	matricule            varchar(20)      ,
	designation          varchar(20)      ,
	grade                varchar(5)      
 );

CREATE TABLE rh_cm_local.user_roles ( 
	id                   int  NOT NULL  AUTO_INCREMENT  PRIMARY KEY,
	user_id              int      ,
	role_id              int      
 );

CREATE TABLE rh_cm_local.congee ( 
	id                   int  NOT NULL  AUTO_INCREMENT  PRIMARY KEY,
	motif                text      ,
	duree                int      ,
	datedebut            date      ,
	datefin              date      ,
	personnel_id         int      
 ) engine=InnoDB;

CREATE TABLE rh_cm_local.permission ( 
	id                   int  NOT NULL  AUTO_INCREMENT  PRIMARY KEY,
	motif                text      ,
	duree                int      ,
	datedebut            date      ,
	datefin              date      ,
	personnel_id         int      
 ) engine=InnoDB;

CREATE TABLE rh_cm_local.personnel_poste ( 
	id                   int  NOT NULL  AUTO_INCREMENT  PRIMARY KEY,
	personnel_id         int      ,
	poste_id             int      ,
	datedebut            date      
 );

CREATE INDEX fk_personnel_chargesocial_charges_social ON rh_cm_local.personnel_chargesocial ( chargesocial_id );

CREATE INDEX fk_personnel_chargesocial_personnel ON rh_cm_local.personnel_chargesocial ( personnel_id );

CREATE INDEX fk_personnel_sexe ON rh_cm_local.personnel ( sexe_id );

CREATE INDEX fk_personnel_situationmatrimoniale ON rh_cm_local.personnel ( situationmatrimonial_id );

CREATE INDEX fk_personnel_indemnite_indemnite ON rh_cm_local.personnel_indemnite ( indemnite_id );

CREATE INDEX fk_personnel_indemnite_personnel ON rh_cm_local.personnel_indemnite ( personnel_id );

CREATE INDEX fk_poste_contrat ON rh_cm_local.poste ( contrat_id );

CREATE INDEX fk_poste_directions ON rh_cm_local.poste ( direction_id );

CREATE INDEX fk_poste_service ON rh_cm_local.poste ( service_id );

CREATE INDEX fk_user_roles_roles ON rh_cm_local.user_roles ( role_id );

CREATE INDEX fk_user_roles_users ON rh_cm_local.user_roles ( user_id );

CREATE INDEX fk_personnel_poste_personnel ON rh_cm_local.personnel_poste ( personnel_id );

CREATE INDEX fk_personnel_poste_poste ON rh_cm_local.personnel_poste ( poste_id );

ALTER TABLE rh_cm_local.congee ADD CONSTRAINT fk_congee_personnel FOREIGN KEY ( personnel_id ) REFERENCES rh_cm_local.personnel( id ) ON DELETE NO ACTION ON UPDATE NO ACTION;

ALTER TABLE rh_cm_local.permission ADD CONSTRAINT fk_permission_personnel FOREIGN KEY ( personnel_id ) REFERENCES rh_cm_local.personnel( id ) ON DELETE NO ACTION ON UPDATE NO ACTION;

ALTER TABLE rh_cm_local.personnel ADD CONSTRAINT fk_personnel_sexe FOREIGN KEY ( sexe_id ) REFERENCES rh_cm_local.sexe( id ) ON DELETE RESTRICT ON UPDATE RESTRICT;

ALTER TABLE rh_cm_local.personnel ADD CONSTRAINT fk_personnel_situationmatrimoniale FOREIGN KEY ( situationmatrimonial_id ) REFERENCES rh_cm_local.situationmatrimoniale( id ) ON DELETE RESTRICT ON UPDATE RESTRICT;

ALTER TABLE rh_cm_local.personnel_indemnite ADD CONSTRAINT fk_personnel_indemnite_indemnite FOREIGN KEY ( indemnite_id ) REFERENCES rh_cm_local.indemnite( id ) ON DELETE RESTRICT ON UPDATE RESTRICT;

ALTER TABLE rh_cm_local.personnel_indemnite ADD CONSTRAINT fk_personnel_indemnite_personnel FOREIGN KEY ( personnel_id ) REFERENCES rh_cm_local.personnel( id ) ON DELETE RESTRICT ON UPDATE RESTRICT;

ALTER TABLE rh_cm_local.personnel_poste ADD CONSTRAINT fk_personnel_poste_personnel FOREIGN KEY ( personnel_id ) REFERENCES rh_cm_local.personnel( id ) ON DELETE RESTRICT ON UPDATE RESTRICT;

ALTER TABLE rh_cm_local.personnel_poste ADD CONSTRAINT fk_personnel_poste_poste FOREIGN KEY ( poste_id ) REFERENCES rh_cm_local.poste( id ) ON DELETE RESTRICT ON UPDATE RESTRICT;

ALTER TABLE rh_cm_local.poste ADD CONSTRAINT fk_poste_contrat FOREIGN KEY ( contrat_id ) REFERENCES rh_cm_local.contrat( id ) ON DELETE RESTRICT ON UPDATE RESTRICT;

ALTER TABLE rh_cm_local.poste ADD CONSTRAINT fk_poste_directions FOREIGN KEY ( direction_id ) REFERENCES rh_cm_local.directions( id ) ON DELETE RESTRICT ON UPDATE RESTRICT;

ALTER TABLE rh_cm_local.poste ADD CONSTRAINT fk_poste_service FOREIGN KEY ( service_id ) REFERENCES rh_cm_local.service( id ) ON DELETE RESTRICT ON UPDATE RESTRICT;

ALTER TABLE rh_cm_local.user_roles ADD CONSTRAINT fk_user_roles_roles FOREIGN KEY ( role_id ) REFERENCES rh_cm_local.roles( id ) ON DELETE RESTRICT ON UPDATE RESTRICT;

ALTER TABLE rh_cm_local.user_roles ADD CONSTRAINT fk_user_roles_users FOREIGN KEY ( user_id ) REFERENCES rh_cm_local.users( id ) ON DELETE RESTRICT ON UPDATE RESTRICT;


insert into personnel (nom, prenom, cin, datedenaissance, nbEnfant) values ('Rakoto', 'Be', '401', '1990-01-01', 2);
insert into personnel (nom, prenom, cin, datedenaissance, nbEnfant) values ('Rabe', 'Be', '4011', '1988-01-01', 3);
insert into personnel (nom, prenom, cin, datedenaissance, nbEnfant) values ('Soa', 'Be', '4012', '1984-01-01', 0);

insert into sexe (designation) values ('Homme'), ('Femme');
insert into situationmatrimoniale (designation) values ('Célibataire'), ('Marié');

insert into contrat (designation) values ('ECD'),('ELD');
insert into directions (designation) values ('Finance'), ('RH');
insert into service (designation) values ('Recette'), ('Securité');

insert into poste (contrat_id, categorie, indice, direction_id) values (1,1,231,1), (2,1,231,2), (2,1,231,1);
insert into personnel_poste (personnel_id, poste_id) values (1,1), (2,2), (3,3);

insert into users (identifiant, motdepasse) value('admin', sha1('admin'));
insert into roles (designation) value('admin');
insert into user_roles (user_id, role_id) values (1,1);

insert into charges_social (designation, part_salariale, part_patronale) values ("CNAPS", 1, 13), ("CPR", 5, 19), ("CRCM", 5, 19);

insert into contrat_chargesocial (contrat_id, chargesocial_id) values (1, 1), (6, 3), (2, 2), (5, 2);

insert into baremepersonneltemporaire (indice, taux_par_heure, date) values ('OS-2B-1485', 994.36, now());

create or replace view maxdate as
	SELECT id, max(datedebut) AS datedebut FROM personnel_poste GROUP BY id ORDER BY id desc;

create or replace view last_job as
	SELECT t1.*
	FROM personnel_poste t1
	INNER JOIN maxdate t2 ON t1.id = t2.id AND t1.datedebut = t2.datedebut group by personnel_id;

create or replace view v_personnel_details as
select 
	personnel.*,
	sexe.designation as sexe,
	situationmatrimoniale.designation as situationmatrimoniale,
	contrat.id contrat_id, 
	contrat.designation contrat, 
	directions.designation as direction,
	poste.id as poste_id, 
	poste.categorie,
	poste.indice, 
	poste.direction_id, 
	poste.matricule, 
	poste.designation poste,
	poste.grade grade, 
	service.designation service, 
	TIMESTAMPDIFF(YEAR, datedenaissance, CURDATE()) as age,
	TIMESTAMPDIFF(DAY, curdate(),  date_add(datedenaissance, interval 60 year)) as date_avant_retraite,
	date_add(datedenaissance, interval 60 year) as date_retraite,
	last_job.datedebut as date_debut_contrat,
	date_add(last_job.datedebut, interval contrat.duree month) as date_fin_contrat,
	TIMESTAMPDIFF(DAY, CURDATE(), date_add(last_job.datedebut, interval contrat.duree month)) as duree_contrat_restant,
	contrat.alerte as alerte_fin_contrat,
	last_job.datefin as date_fin_contrat_temporaire,
	last_job.nbjourdetravailtemporaire as duree_contrat_temporaire,
	last_job.heureparjour as heure_par_jour_temporaire
from personnel 
	left join last_job on personnel.id = last_job.personnel_id
	left join poste on poste.id = last_job.poste_id
	left join contrat on contrat.id = poste.contrat_id
	left join directions on directions.id = poste.direction_id
	left join service on poste.service_id = service.id
	left join sexe on personnel.sexe_id = sexe.id
	left join situationmatrimoniale on personnel.situationmatrimoniale_id = situationmatrimoniale.id
;

create or replace view v_bareme_par_personnel as
select 
	bareme.*, 
	v_personnel_details.id personnel_id,
	v_personnel_details.nom,
	v_personnel_details.prenom,
	v_personnel_details.direction_id,
	v_personnel_details.direction 
from v_personnel_details
	join bareme on bareme.indice = v_personnel_details.indice and bareme.categorie = v_personnel_details.categorie
;

create or replace view v_bareme_par_personnel_temporaire as
select 
	baremepersonneltemporaire.*, 
	v_personnel_details.id personnel_id,
	v_personnel_details.nom,
	v_personnel_details.prenom,
	v_personnel_details.direction_id,
	v_personnel_details.direction 
from v_personnel_details
	join baremepersonneltemporaire on baremepersonneltemporaire.indice = v_personnel_details.indice
;

create or replace view v_chargessocial_personnel as
select
	charges_social.*,
	personnel_chargesocial.id personnel_id
from charges_social
	join personnel_chargesocial on charges_social.id = personnel_chargesocial.chargesocial_id
;

create or replace view v_indemnite_personnel as
select
	indemnite.designation,
	personnel_indemnite.*
from indemnite
	join personnel_indemnite on indemnite.id = personnel_indemnite.indemnite_id
;

create or replace view v_user_roles as
select 
	user_roles.*,
	roles.designation
from user_roles
	join roles on user_roles.role_id = roles.id
;

create or replace view v_contratchargesocial as
select 
	contrat_chargesocial.*,
	charges_social.designation,
	charges_social.part_salariale,
	charges_social.part_patronale
from contrat_chargesocial
	join charges_social on contrat_chargesocial.chargesocial_id = charges_social.id
;


INSERT INTO personnel 
	(`nom`, `prenom`, `cin`, `datedenaissance`, `nbenfant`, `situationmatrimoniale_id`, `lieudenaissance`, `sexe_id`)
SELECT 
	"Toky", `prenom`, null, `datedenaissance`, `nbenfant`, `situationmatrimoniale_id`, `lieudenaissance`, `sexe_id`
FROM 
	personnel
WHERE 
	nom = 'Toky';
	
-- select 
-- 	bareme.*, 
-- 	v.id as personnel_id,
-- 	v.nom,
-- 	v.direction_id,
-- 	v.direction
-- from v_personnel_details as v
-- join bareme on bareme.indice = v.indice and bareme.categorie = v.categorie
-- and datebareme <= '2023-01-27' group by personnel_id

-- select 
-- 	bareme.*, 
-- 	v_personnel_details.id as personnel_id,
-- 	v_personnel_details.nom,
-- 	v_personnel_details.direction_id,
-- 	v_personnel_details.direction
-- from v_personnel_details
-- join bareme on bareme.indice = v_personnel_details.indice and bareme.categorie = v_personnel_details.categorie
-- and datebareme <= '2024-10-01' group by personnel_id
-- ;  
            


-- select * from personnel_poste where personnel_id = 1 order by datedebut desc ,  id desc;

-- insert into bareme (datebareme , categorie , indice , v500) values ('2020-01-05', 1, 231, 69), ('2023-01-05', 1, 231, 420);

-- SELECT * FROM v_bareme_par_personnel v WHERE datebareme <= '2022-01-05';     

-- SELECT id, datebareme AS datebareme FROM bareme where indice = 231 and categorie = 1 and datebareme <= '2022-01-04' GROUP BY id ORDER BY id desc;


-- create or replace view last_job as
-- 	SELECT t1.*
-- 	FROM bareme t1
-- 	INNER JOIN maxdate t2 
-- 	ON t1.id = t2.id AND t1.datedebut = t2.datedebut group by personnel_id;


-- test bareme en date d
select * from (
	select 
		bareme.*, 
		v.id as personnel_id,
		v.nom,
		v.direction_id,
		v.direction,
		v.contrat_id,
		v.contrat
	from v_personnel_details as v
	join bareme on bareme.indice = v.indice and bareme.categorie = v.categorie
	where datebareme <= '2023-02-02' order by datebareme desc 
) as v group by personnel_id;



-- test bareme par direction en date d
-- select * from (
-- 	select 
-- 		bareme.*, 
-- 		v.id as personnel_id,
-- 		v.nom,
-- 		v.direction_id,
-- 		v.direction
-- 	from v_personnel_details as v
-- 	join bareme on bareme.indice = v.indice and bareme.categorie = v.categorie
-- 	where datebareme <= '2023-02-02' and direction_id = 2 order by datebareme desc 
-- ) as v  group by personnel_id;