CREATE SCHEMA rh_cm_local;


CREATE TABLE rh_cm_local.bareme ( 
	id                   int  NOT NULL  AUTO_INCREMENT  PRIMARY KEY,
	datebareme           date  NOT NULL    ,
	categorie            int  NOT NULL    ,
	indice               int  NOT NULL    ,
	v500                 decimal(10,4)      ,
	v501                 decimal(10,4)      ,
	v502                 decimal(15,4)      ,
	v503                 decimal(10,4)      ,
	v506                 decimal(10,4)      ,
	solde                decimal(10,4)      
 );

CREATE TABLE rh_cm_local.charges_social ( 
	id                   int  NOT NULL  AUTO_INCREMENT  PRIMARY KEY,
	designation          varchar(10)  NOT NULL    
 );

CREATE TABLE rh_cm_local.contrat ( 
	id                   int  NOT NULL  AUTO_INCREMENT  PRIMARY KEY,
	designation          varchar(10)      
 );

CREATE TABLE rh_cm_local.directions ( 
	id                   int  NOT NULL  AUTO_INCREMENT  PRIMARY KEY,
	designation          varchar(20)      
 );

CREATE TABLE rh_cm_local.indemnite ( 
	id                   int  NOT NULL  AUTO_INCREMENT  PRIMARY KEY,
	designation          varchar(15)  NOT NULL    
 );

CREATE TABLE rh_cm_local.personnel ( 
	id                   int  NOT NULL  AUTO_INCREMENT  PRIMARY KEY,
	nom                  varchar(50)  NOT NULL    ,
	prenom               varchar(50)  NOT NULL    ,
	cin                  varchar(15)  NOT NULL    ,
	matricule            varchar(50)  NOT NULL    ,
	CONSTRAINT unq_personnel_cin UNIQUE ( cin ) 
 );

CREATE TABLE rh_cm_local.personnel_chargesocial ( 
	id                   int  NOT NULL  AUTO_INCREMENT  PRIMARY KEY,
	chargesocial_id      int      ,
	peronnel_id          int      
 );

CREATE TABLE rh_cm_local.personnel_indemnite ( 
	id                   int  NOT NULL  AUTO_INCREMENT  PRIMARY KEY,
	personnel_id         int      ,
	indemnite_id         int      ,
	montant              decimal(10,4)      
 );

CREATE TABLE rh_cm_local.poste ( 
	id                   int  NOT NULL  AUTO_INCREMENT  PRIMARY KEY,
	contrat_id           int  NOT NULL    ,
	categorie            int      ,
	indice               int      ,
	direction_id         int      
 );

CREATE TABLE rh_cm_local.roles ( 
	id                   int  NOT NULL  AUTO_INCREMENT  PRIMARY KEY,
	designation          varchar(10)  NOT NULL    
 );

CREATE TABLE rh_cm_local.users ( 
	id                   int  NOT NULL  AUTO_INCREMENT  PRIMARY KEY,
	identifiant          varchar(50)  NOT NULL    ,
	mot_de_passe         varchar(50)  NOT NULL    
 );

CREATE TABLE rh_cm_local.personnel_poste ( 
	id                   int  NOT NULL  AUTO_INCREMENT  PRIMARY KEY,
	personnel_id         int      ,
	poste_id             int      
 );

CREATE TABLE rh_cm_local.user_roles ( 
	id                   int  NOT NULL  AUTO_INCREMENT  PRIMARY KEY,
	user_id              int      ,
	role_id              int      
 );

CREATE INDEX fk_personnel_chargesocial_charges_social ON rh_cm_local.personnel_chargesocial ( chargesocial_id );

CREATE INDEX fk_personnel_chargesocial_personnel ON rh_cm_local.personnel_chargesocial ( peronnel_id );

CREATE INDEX fk_personnel_indemnite_indemnite ON rh_cm_local.personnel_indemnite ( indemnite_id );

CREATE INDEX fk_personnel_indemnite_personnel ON rh_cm_local.personnel_indemnite ( personnel_id );

CREATE INDEX fk_user_roles_roles ON rh_cm_local.user_roles ( role_id );

CREATE INDEX fk_user_roles_users ON rh_cm_local.user_roles ( user_id );

ALTER TABLE rh_cm_local.personnel_chargesocial ADD CONSTRAINT fk_personnel_chargesocial_charges_social FOREIGN KEY ( chargesocial_id ) REFERENCES rh_cm_local.charges_social( id ) ON DELETE RESTRICT ON UPDATE RESTRICT;

ALTER TABLE rh_cm_local.personnel_chargesocial ADD CONSTRAINT fk_personnel_chargesocial_personnel FOREIGN KEY ( peronnel_id ) REFERENCES rh_cm_local.personnel( id ) ON DELETE RESTRICT ON UPDATE RESTRICT;

ALTER TABLE rh_cm_local.personnel_indemnite ADD CONSTRAINT fk_personnel_indemnite_indemnite FOREIGN KEY ( indemnite_id ) REFERENCES rh_cm_local.indemnite( id ) ON DELETE RESTRICT ON UPDATE RESTRICT;

ALTER TABLE rh_cm_local.personnel_indemnite ADD CONSTRAINT fk_personnel_indemnite_personnel FOREIGN KEY ( personnel_id ) REFERENCES rh_cm_local.personnel( id ) ON DELETE RESTRICT ON UPDATE RESTRICT;

ALTER TABLE rh_cm_local.personnel_poste ADD CONSTRAINT fk_personnel_poste_personnel FOREIGN KEY ( personnel_id ) REFERENCES rh_cm_local.personnel( id ) ON DELETE NO ACTION ON UPDATE NO ACTION;

ALTER TABLE rh_cm_local.personnel_poste ADD CONSTRAINT fk_personnel_poste_poste FOREIGN KEY ( poste_id ) REFERENCES rh_cm_local.poste( id ) ON DELETE NO ACTION ON UPDATE NO ACTION;

ALTER TABLE rh_cm_local.poste ADD CONSTRAINT fk_poste_directions FOREIGN KEY ( direction_id ) REFERENCES rh_cm_local.directions( id ) ON DELETE NO ACTION ON UPDATE NO ACTION;

ALTER TABLE rh_cm_local.poste ADD CONSTRAINT fk_poste_contrat FOREIGN KEY ( contrat_id ) REFERENCES rh_cm_local.contrat( id ) ON DELETE NO ACTION ON UPDATE NO ACTION;

ALTER TABLE rh_cm_local.user_roles ADD CONSTRAINT fk_user_roles_roles FOREIGN KEY ( role_id ) REFERENCES rh_cm_local.roles( id ) ON DELETE RESTRICT ON UPDATE RESTRICT;

ALTER TABLE rh_cm_local.user_roles ADD CONSTRAINT fk_user_roles_users FOREIGN KEY ( user_id ) REFERENCES rh_cm_local.users( id ) ON DELETE RESTRICT ON UPDATE RESTRICT;

insert into personnel (nom, prenom, cin, matricule) values ('Rakoto', 'Be', '401', '401');
insert into personnel (nom, prenom, cin, matricule) values ('Rabe', 'Be', '4011', '4011');
insert into personnel (nom, prenom, cin, matricule) values ('Soa', 'Be', '4012', '4012');
insert into contrat (designation) values ('ECD'),('ELD');
insert into directions (designation) values ('Finance'), ('RH');
insert into poste (contrat_id, categorie, indice, direction_id) values (1,1,231,1), (2,1,231,2), (2,1,231,1);
insert into personnel_poste (personnel_id, poste_id) values (1,1), (2,2), (3,3);

create or replace view v_personnel_poste as
	select personnel.*, poste.categorie, contrat.designation contrat, poste.indice, poste.direction_id, directions.designation as direction from personnel 
		join personnel_poste on personnel.id = personnel_poste.personnel_id
		join poste on poste.id = personnel_poste.poste_id
		join contrat on contrat.id = poste.contrat_id
		join directions on directions.id = poste.direction_id
;

create or replace view v_bareme_par_personnel as
select 
	bareme.*, 
	v_personnel_poste.nom,
	v_personnel_poste.direction_id,
	v_personnel_poste.direction 
from v_personnel_poste
	join bareme on bareme.indice = v_personnel_poste.indice and bareme.categorie = v_personnel_poste.categorie
;