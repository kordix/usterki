CREATE TABLE usterki (
    id int PRIMARY KEY AUTO_INCREMENT,
    typ_niezgodnosci varchar(255) default '',
    opis_niezgodnosci text NULL,
    adres_admin text NULL,
    nr_admin varchar(255) default '',
    kontakt_klient varchar(255) default '',
    data_klient varchar(255) default '',
    uwagi_inwestora text NULL,
    termin_zgloszenia varchar(255) default '',
    klasyfikacja varchar(255) default '',
    komentarz_serwisu text NULL,
    `status` varchar(255) default '',
    komentarz_budowy text NULL,
    `lokal` varchar(255) default '',
    `odbiorca` varchar(255) default '',
    project_id int default null,
    plan_id int default null,
    x float default null,
    y float default NULL,
    SPW varchar(255) default '',
    nr_oferty varchar(255) default '',
    nr_zlecenia varchar(255) default '',
    created_at timestamp null,
    updated_at timestamp NULL,  
    link text,
    INDEX idx_project_id(project_id)
);

ALTER TABLE usterki
ADD COLUMN usterka_numer INT;

ALTER TABLE usterki
ADD COLUMN `hidden` varchar(1) default '';

ALTER TABLE usterki
ADD COLUMN `nr_pozycji` varchar(255) default '';

ALTER TABLE usterki
ADD COLUMN `opis_niezgodnosci_serwis` varchar(255) default '';

ALTER TABLE usterki
ADD COLUMN `typ_niezgodnosci_serwis` varchar(255) default '';

ALTER TABLE usterki
ADD COLUMN `column0` varchar(255) default '';

ALTER TABLE usterki
ADD COLUMN `column1` varchar(255) default '';

ALTER TABLE usterki
ADD COLUMN `column2` varchar(255) default '';

ALTER TABLE usterki
ADD COLUMN `column3` varchar(255) default '';

ALTER TABLE usterki
ADD COLUMN `column4` varchar(255) default '';

ALTER TABLE usterki
ADD COLUMN `column5` varchar(255) default '';

ALTER TABLE usterki
ADD COLUMN `column6` varchar(255) default '';

ALTER TABLE usterki
ADD COLUMN `column7` varchar(255) default '';

ALTER TABLE usterki
ADD COLUMN `column8` varchar(255) default '';

ALTER TABLE usterki
ADD COLUMN `column9` varchar(255) default '';

ALTER TABLE usterki
ADD COLUMN `column10` varchar(255) default '';