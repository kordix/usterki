CREATE TABLE usterki (
    id int PRIMARY KEY AUTO_INCREMENT,
    typ_niezgodnosci varchar(255) default '',
    opis_niezgodnosci text,
    adres_admin text,
    nr_admin varchar(255),
    kontakt_klient varchar(255),
    data_klient varchar(255),
    uwagi_inwestora text,
    termin_zgloszenia varchar(255) default '',
    klasyfikacja varchar(255) default '',
    komentarz_serwisu text,
    `status` varchar(255) default '',
    komentarz_budowy text,
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
)

ALTER TABLE usterki
ADD COLUMN usterka_numer INT;

ALTER TABLE usterki
ADD COLUMN `hidden` varchar(1) default '';

ALTER TABLE usterki
ADD COLUMN `nr_pozycji` varchar(255) default '';