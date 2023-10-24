CREATE TABLE projects (
    id int PRIMARY KEY AUTO_INCREMENT,
    nazwa_projektu varchar(255) DEFAULT '',
    adres varchar(255) DEFAULT '',
    numer_referencyjny varchar(255) DEFAULT '',
    inwestor varchar(255) DEFAULT '',
    generalny_wykonawca varchar(255) DEFAULT '',
    data_start date NULL,
    data_end date NULL,
    project_manager varchar(255) DEFAULT '',
    handlowiec varchar(255) DEFAULT '',
    przedstawiciel varchar(255) DEFAULT '',
    zleceniewinpro varchar(255) DEFAULT '',
    spw varchar(255) DEFAULT '',
    created_at timestamp null,
    updated_at timestamp null
)