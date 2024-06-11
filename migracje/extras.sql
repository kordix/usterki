CREATE TABLE extras (
    id int PRIMARY KEY AUTO_INCREMENT,
    usterka_id int NULL,
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

ALTER TABLE extras add column extra_numer varchar(10);

ALTER TABLE extras
ADD COLUMN `opis_niezgodnosci_serwis` varchar(255) default '';

ALTER TABLE extras
ADD COLUMN `typ_niezgodnosci_serwis` varchar(255) default '';