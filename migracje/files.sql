CREATE TABLE files(
    id int PRIMARY KEY AUTO_INCREMENT,
    filename varchar(255),
    title varchar(255) default '',
    description varchar(255) default '',
    service bit null,
    usterka_id int null,
    user_id int null
);


