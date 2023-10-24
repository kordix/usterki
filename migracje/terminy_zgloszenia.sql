CREATE TABLE terminy_zgloszenia (
    id int PRIMARY KEY AUTO_INCREMENT,
    code varchar(255),
    description varchar(255),
    INDEX codeindex(code)
);



INSERT INTO terminy_zgloszenia values (default,'P','Pomontażowa');
INSERT INTO terminy_zgloszenia values (default,'O','Odbiorowa');
INSERT INTO terminy_zgloszenia values (default,'L','Lokatorska');