CREATE TABLE logs (
    id int PRIMARY KEY AUTO_INCREMENT,
    user_id int null,
    usterka_id int null,
    `action` varchar(255) null,
    `kolumna` varchar(255) null,
    'old_value' varchar(255) null,
    'new_value' varchar(255) null,
    created_at timestamp
)