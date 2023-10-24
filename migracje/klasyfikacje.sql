CREATE TABLE klasyfikacje (
    id int PRIMARY KEY AUTO_INCREMENT,
    code varchar(255),
    description varchar(255),
    INDEX codeindex(code)
);


INSERT INTO klasyfikacje values (default,'G','Pomontażowa');
INSERT INTO klasyfikacje values (default,'N','Odbiorowa');
INSERT INTO klasyfikacje values (default,'L','Lokatorska');