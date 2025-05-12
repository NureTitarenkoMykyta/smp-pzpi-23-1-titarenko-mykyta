<?php
$db = new PDO('sqlite:shop.db');
$db->exec("CREATE TABLE IF NOT EXISTS user (
    user_id INTEGER PRIMARY KEY AUTOINCREMENT
)");

$db->exec("CREATE TABLE IF NOT EXISTS product (
    product_id INTEGER PRIMARY KEY AUTOINCREMENT, 
    product_name TEXT, 
    product_price TEXT
)");

$db->exec("CREATE TABLE IF NOT EXISTS user_product (
    user_id INTEGER, 
    product_id INTEGER, 
    quantity INTEGER,
    PRIMARY KEY(user_id, product_id),
    FOREIGN KEY(user_id) REFERENCES user(user_id),
    FOREIGN KEY(product_id) REFERENCES product(product_id)
)");

$db->exec("INSERT INTO product (product_name, product_price) VALUES 
    ('Молоко пастеризоване', 12),
    ('Хліб чорний', 9),
    ('Сир білий',  2),
    ('Сметана 20%', 25),
    ('Кефір 1%', 19),
    ('Вода газована', 18),
    ('Печиво \"Весна\"', 14)");