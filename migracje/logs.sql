CREATE TABLE logs (
    id int PRIMARY KEY AUTO_INCREMENT,
    user_id int null,
    usterka_id int null,
    `action` varchar(255) null,
    created_at date
)