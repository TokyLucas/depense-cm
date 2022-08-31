CREATE SCHEMA depense_cm;

CREATE TABLE depense_cm.bareme ( 
	id                   int  NOT NULL  AUTO_INCREMENT  PRIMARY KEY,
	datebareme           date  NOT NULL    ,
	categorie            int  NOT NULL    ,
	indice               int  NOT NULL    ,
	v500                 decimal(10,4)      ,
	v501                 decimal(10,4)      ,
	v502                 decimal(10,4)      ,
	v503                 decimal(10,4)      ,
	v506                 decimal(10,4)      ,
	solde                decimal(10,4)      
 ) engine=InnoDB;

CREATE TABLE depense_cm.charges_social ( 
	id                   int  NOT NULL  AUTO_INCREMENT  PRIMARY KEY,
	designation          varchar(10)  NOT NULL    
 ) engine=InnoDB;

CREATE TABLE depense_cm.contrat ( 
	id                   int  NOT NULL  AUTO_INCREMENT  PRIMARY KEY,
	designation          varchar(10)      
 ) engine=InnoDB;

CREATE TABLE depense_cm.indemnite ( 
	id                   int  NOT NULL  AUTO_INCREMENT  PRIMARY KEY,
	designation          varchar(15)  NOT NULL    
 ) engine=InnoDB;

CREATE TABLE depense_cm.personnel ( 
	id                   int  NOT NULL  AUTO_INCREMENT  PRIMARY KEY,
	nom                  varchar(50)  NOT NULL    ,
	prenom               varchar(50)  NOT NULL    ,
	cin                  varchar(15)  NOT NULL    ,
	matricule            varchar(50)  NOT NULL    ,
	CONSTRAINT unq_personnel_cin UNIQUE ( cin ) 
 ) engine=InnoDB;

CREATE TABLE depense_cm.poste ( 
	id                   int  NOT NULL  AUTO_INCREMENT  PRIMARY KEY,
	contrat_id           int  NOT NULL    ,
	categorie            int,
	indice 				 int
 ) engine=InnoDB;

CREATE TABLE depense_cm.roles ( 
	id                   int  NOT NULL  AUTO_INCREMENT  PRIMARY KEY,
	designation          varchar(10)  NOT NULL    
 ) engine=InnoDB;

CREATE TABLE depense_cm.users ( 
	id                   int  NOT NULL  AUTO_INCREMENT  PRIMARY KEY,
	identifiant          varchar(50)  NOT NULL    ,
	mot_de_passe         varchar(50)  NOT NULL    
 ) engine=InnoDB;

CREATE TABLE depense_cm.personnel_poste ( 
	id                   int  NOT NULL  AUTO_INCREMENT  PRIMARY KEY,
	personnel_id         int      ,
	poste_id             int      
 ) engine=InnoDB;

CREATE TABLE depense_cm.user_roles ( 
	id                   int  NOT NULL  AUTO_INCREMENT  PRIMARY KEY,
	user_id              int      ,
	role_id              int      
 ) engine=InnoDB;

ALTER TABLE depense_cm.personnel_poste ADD CONSTRAINT fk_personnel_poste_poste FOREIGN KEY ( poste_id ) REFERENCES depense_cm.poste( id ) ON DELETE NO ACTION ON UPDATE NO ACTION;

ALTER TABLE depense_cm.personnel_poste ADD CONSTRAINT fk_personnel_poste_personnel FOREIGN KEY ( personnel_id ) REFERENCES depense_cm.personnel( id ) ON DELETE NO ACTION ON UPDATE NO ACTION;

ALTER TABLE depense_cm.poste ADD CONSTRAINT fk_poste_contrat FOREIGN KEY ( contrat_id ) REFERENCES depense_cm.contrat( id ) ON DELETE NO ACTION ON UPDATE NO ACTION;

ALTER TABLE depense_cm.user_roles ADD CONSTRAINT fk_user_roles_users FOREIGN KEY ( user_id ) REFERENCES depense_cm.users( id ) ON DELETE NO ACTION ON UPDATE NO ACTION;

ALTER TABLE depense_cm.user_roles ADD CONSTRAINT fk_user_roles_roles FOREIGN KEY ( role_id ) REFERENCES depense_cm.roles( id ) ON DELETE NO ACTION ON UPDATE NO ACTION;

insert into personnel (nom, prenom, cin, matricule) values ('Rakoto', 'Be', '401', '401');
insert into contrat (designation) values ('ECD');
insert into direction (designation) values ('Finance'), ('RH');
insert into poste (contrat_id, categorie, indice) values (1,1,231);
insert into personnel_poste (personnel_id, poste_id) values (1,1);

create or replace view v_personnel_poste as
	select personnel.*, poste.categorie, contrat.designation contrat, poste.indice from personnel 
		join personnel_poste on personnel.id = personnel_poste.personnel_id
		join poste on poste.id = personnel_poste.poste_id
		join contrat on contrat.id = poste.contrat_id
;

select bareme.* from v_personnel_poste
	join bareme on bareme.indice = v_personnel_poste.indice and bareme.categorie = v_personnel_poste.categorie
;