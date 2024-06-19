CREATE TABLE logs (
    id int PRIMARY KEY AUTO_INCREMENT,
    user_id int null,
    usterka_id int null,
    `action` varchar(255) default '',
    `kolumna` varchar(255) default '',
    `old_value` varchar(255) default '',
    `new_value` varchar(255) default '',
    created_at timestamp
)