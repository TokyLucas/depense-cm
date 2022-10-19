CREATE SCHEMA rh_cm_local;

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

CREATE TABLE rh_cm_local.baremepersonneltemporaire ( 
	id                   int  NOT NULL  AUTO_INCREMENT  PRIMARY KEY,
	indice               varchar(15)      ,
	taux_par_heure       decimal(15,2)      ,
	`date`               date      
 ) engine=InnoDB;

CREATE TABLE rh_cm_local.charges_social ( 
	id                   int  NOT NULL  AUTO_INCREMENT  PRIMARY KEY,
	designation          varchar(25)      ,
	part_salariale       decimal(3,1)      ,
	part_patronale       decimal(3,1)      
 );

CREATE TABLE rh_cm_local.contrat ( 
	id                   int  NOT NULL  AUTO_INCREMENT  PRIMARY KEY,
	designation          varchar(15)      ,
	duree                int      ,
	alerte               int      
 );

CREATE TABLE rh_cm_local.contrat_chargesocial ( 
	id                   int  NOT NULL  AUTO_INCREMENT  PRIMARY KEY,
	contrat_id           int      ,
	chargesocial_id      int      
 );

CREATE TABLE rh_cm_local.directions ( 
	id                   int  NOT NULL  AUTO_INCREMENT  PRIMARY KEY,
	designation          varchar(25)      
 );

CREATE TABLE rh_cm_local.indemnite ( 
	id                   int  NOT NULL  AUTO_INCREMENT  PRIMARY KEY,
	designation          varchar(15)      
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
	situationmatrimoniale_id int      ,
	lieudenaissance      varchar(50)      ,
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

CREATE TABLE rh_cm_local.avertissement ( 
	id                   int  NOT NULL  AUTO_INCREMENT  PRIMARY KEY,
	motif                text      ,
	`date`               date      ,
	personnel_id         int      
 );

CREATE TABLE rh_cm_local.congee ( 
	id                   int  NOT NULL  AUTO_INCREMENT  PRIMARY KEY,
	motif                text      ,
	duree                int      ,
	datedebut            date      ,
	datefin              date      ,
	personnel_id         int      
 );

CREATE TABLE rh_cm_local.excuse ( 
	id                   int  NOT NULL  AUTO_INCREMENT  PRIMARY KEY,
	motif                text      ,
	`date`               date      ,
	personnel_id         int      
 );

CREATE TABLE rh_cm_local.permission ( 
	id                   int  NOT NULL  AUTO_INCREMENT  PRIMARY KEY,
	motif                text      ,
	duree                int      ,
	datedebut            date      ,
	datefin              date      ,
	personnel_id         int      
 );

CREATE TABLE rh_cm_local.personnel_poste ( 
	id                   int  NOT NULL  AUTO_INCREMENT  PRIMARY KEY,
	personnel_id         int      ,
	poste_id             int      ,
	datedebut            date      ,
	datefin              date      ,
	heureparjour         date      
 );

CREATE INDEX fk_personnel_chargesocial_charges_social_0 ON rh_cm_local.contrat_chargesocial ( chargesocial_id );

CREATE INDEX fk_personnel_chargesocial_personnel_0 ON rh_cm_local.contrat_chargesocial ( contrat_id );

CREATE INDEX fk_personnel_sexe ON rh_cm_local.personnel ( sexe_id );

CREATE INDEX fk_personnel_situationmatrimoniale ON rh_cm_local.personnel ( situationmatrimoniale_id );

CREATE INDEX fk_personnel_indemnite_indemnite ON rh_cm_local.personnel_indemnite ( indemnite_id );

CREATE INDEX fk_personnel_indemnite_personnel ON rh_cm_local.personnel_indemnite ( personnel_id );

CREATE INDEX fk_poste_contrat ON rh_cm_local.poste ( contrat_id );

CREATE INDEX fk_poste_directions ON rh_cm_local.poste ( direction_id );

CREATE INDEX fk_poste_service ON rh_cm_local.poste ( service_id );

CREATE INDEX fk_user_roles_roles ON rh_cm_local.user_roles ( role_id );

CREATE INDEX fk_user_roles_users ON rh_cm_local.user_roles ( user_id );

CREATE INDEX fk_avertissement_personnel ON rh_cm_local.avertissement ( personnel_id );

CREATE INDEX fk_congee_personnel ON rh_cm_local.congee ( personnel_id );

CREATE INDEX fk_excuse_personnel ON rh_cm_local.excuse ( personnel_id );

CREATE INDEX fk_permission_personnel ON rh_cm_local.permission ( personnel_id );

CREATE INDEX fk_personnel_poste_personnel ON rh_cm_local.personnel_poste ( personnel_id );

CREATE INDEX fk_personnel_poste_poste ON rh_cm_local.personnel_poste ( poste_id );

ALTER TABLE rh_cm_local.avertissement ADD CONSTRAINT fk_avertissement_personnel FOREIGN KEY ( personnel_id ) REFERENCES rh_cm_local.personnel( id ) ON DELETE RESTRICT ON UPDATE RESTRICT;

ALTER TABLE rh_cm_local.congee ADD CONSTRAINT fk_congee_personnel FOREIGN KEY ( personnel_id ) REFERENCES rh_cm_local.personnel( id ) ON DELETE RESTRICT ON UPDATE RESTRICT;

ALTER TABLE rh_cm_local.contrat_chargesocial ADD CONSTRAINT fk_contrat_chargesocial_charges_social FOREIGN KEY ( chargesocial_id ) REFERENCES rh_cm_local.charges_social( id ) ON DELETE NO ACTION ON UPDATE NO ACTION;

ALTER TABLE rh_cm_local.contrat_chargesocial ADD CONSTRAINT fk_contrat_chargesocial_contrat FOREIGN KEY ( contrat_id ) REFERENCES rh_cm_local.contrat( id ) ON DELETE NO ACTION ON UPDATE NO ACTION;

ALTER TABLE rh_cm_local.excuse ADD CONSTRAINT fk_excuse_personnel FOREIGN KEY ( personnel_id ) REFERENCES rh_cm_local.personnel( id ) ON DELETE RESTRICT ON UPDATE RESTRICT;

ALTER TABLE rh_cm_local.permission ADD CONSTRAINT fk_permission_personnel FOREIGN KEY ( personnel_id ) REFERENCES rh_cm_local.personnel( id ) ON DELETE RESTRICT ON UPDATE RESTRICT;

ALTER TABLE rh_cm_local.personnel ADD CONSTRAINT fk_personnel_sexe FOREIGN KEY ( sexe_id ) REFERENCES rh_cm_local.sexe( id ) ON DELETE RESTRICT ON UPDATE RESTRICT;

ALTER TABLE rh_cm_local.personnel ADD CONSTRAINT fk_personnel_situationmatrimoniale FOREIGN KEY ( situationmatrimoniale_id ) REFERENCES rh_cm_local.situationmatrimoniale( id ) ON DELETE RESTRICT ON UPDATE RESTRICT;

ALTER TABLE rh_cm_local.personnel_indemnite ADD CONSTRAINT fk_personnel_indemnite_indemnite FOREIGN KEY ( indemnite_id ) REFERENCES rh_cm_local.indemnite( id ) ON DELETE RESTRICT ON UPDATE RESTRICT;

ALTER TABLE rh_cm_local.personnel_indemnite ADD CONSTRAINT fk_personnel_indemnite_personnel FOREIGN KEY ( personnel_id ) REFERENCES rh_cm_local.personnel( id ) ON DELETE RESTRICT ON UPDATE RESTRICT;

ALTER TABLE rh_cm_local.personnel_poste ADD CONSTRAINT fk_personnel_poste_personnel FOREIGN KEY ( personnel_id ) REFERENCES rh_cm_local.personnel( id ) ON DELETE RESTRICT ON UPDATE RESTRICT;

ALTER TABLE rh_cm_local.personnel_poste ADD CONSTRAINT fk_personnel_poste_poste FOREIGN KEY ( poste_id ) REFERENCES rh_cm_local.poste( id ) ON DELETE RESTRICT ON UPDATE RESTRICT;

ALTER TABLE rh_cm_local.poste ADD CONSTRAINT fk_poste_contrat FOREIGN KEY ( contrat_id ) REFERENCES rh_cm_local.contrat( id ) ON DELETE RESTRICT ON UPDATE RESTRICT;

ALTER TABLE rh_cm_local.poste ADD CONSTRAINT fk_poste_directions FOREIGN KEY ( direction_id ) REFERENCES rh_cm_local.directions( id ) ON DELETE RESTRICT ON UPDATE RESTRICT;

ALTER TABLE rh_cm_local.poste ADD CONSTRAINT fk_poste_service FOREIGN KEY ( service_id ) REFERENCES rh_cm_local.service( id ) ON DELETE RESTRICT ON UPDATE RESTRICT;

ALTER TABLE rh_cm_local.user_roles ADD CONSTRAINT fk_user_roles_roles FOREIGN KEY ( role_id ) REFERENCES rh_cm_local.roles( id ) ON DELETE RESTRICT ON UPDATE RESTRICT;

ALTER TABLE rh_cm_local.user_roles ADD CONSTRAINT fk_user_roles_users FOREIGN KEY ( user_id ) REFERENCES rh_cm_local.users( id ) ON DELETE RESTRICT ON UPDATE RESTRICT;
