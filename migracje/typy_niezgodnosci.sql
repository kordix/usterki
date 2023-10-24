CREATE TABLE typy_niezgodnosci (
    id int PRIMARY KEY AUTO_INCREMENT,
    code varchar(255),
    description varchar(255),
    INDEX codeindex(code)
);

INSERT INTO typy_niezgodnosci values (default,'S','Wada szyby');
INSERT INTO typy_niezgodnosci values (default,'U','Uszkodzone / wada powierzchni');
INSERT INTO typy_niezgodnosci values (default,'A','Niezgodność asortymentowa');
INSERT INTO typy_niezgodnosci values (default,'B','Brak / niekompletność');
INSERT INTO typy_niezgodnosci values (default,'F','Wada funkcjonowania');
INSERT INTO typy_niezgodnosci values (default,'W','Wada wymiarowa');





