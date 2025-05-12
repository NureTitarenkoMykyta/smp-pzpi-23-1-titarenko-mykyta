<?php
//Отримання всіх товарів з магазину
function getProducts()
{
    global $db;
    $statement = $db->query('SELECT product_id, product_name, product_price FROM product');
    return $statement->fetchAll(PDO::FETCH_ASSOC);
}

//Отримання товарів з кошику користувача
function getProductsFromCart()
{
    global $db;
    global $user_id;
    $statement = $db->prepare('
    SELECT 
        up.product_id, 
        p.product_name, 
        p.product_price, 
        up.quantity, 
        p.product_price * up.quantity AS total_price 
    FROM user_product up 
    LEFT JOIN product p
    ON p.product_id = up.product_id
    WHERE up.user_id = :user_id');
    $statement->bindParam(':user_id', $user_id);
    $statement->execute();
    return $statement->fetchAll(PDO::FETCH_ASSOC);
}

//Отримання загальної ціни кошика
function getTotalPrice(){
    global $db;
    global $user_id;
    $statement = $db->prepare('SELECT SUM(p.product_price * up.quantity) AS total_sum 
        FROM user_product up 
        LEFT JOIN product p
        ON up.product_id = p.product_id
        WHERE up.user_id = :user_id');
    $statement->bindParam(':user_id', $user_id);
    $statement->execute();
    return $statement->fetch(PDO::FETCH_COLUMN);
}

//Додавання товару у кошик користувача
function insertProduct($index, $quantity)
{
    global $db;
    global $user_id;
    $statement = $db->prepare('INSERT INTO user_product (user_id, product_id, quantity) VALUES (:user_id, :product_id, :quantity)');
    $statement->bindParam(':user_id', $user_id);
    $statement->bindParam(':product_id', $index);
    $statement->bindParam(':quantity', $quantity);
    $statement->execute();
}

//Видалення товару з кошика користувача
function deleteFromCart($index)
{
    global $db;
    global $user_id;
    $statement = $db->prepare('DELETE FROM user_product WHERE user_id = :user_id AND product_id = :product_id');
    $statement->bindParam(':user_id', $user_id);
    $statement->bindParam(':product_id', $index);
    $statement->execute();
}

//Перевірка на наявність товару у кошику користувача
function productInCartExist($index)
{
    global $db;
    global $user_id;
    $statement = $db->prepare('SELECT EXISTS(SELECT 1 FROM user_product WHERE user_id = :user_id AND product_id = :product_id) AS found');
    $statement->bindParam(':user_id', $user_id);
    $statement->bindParam(':product_id', $index);
    $statement->execute();
    $test = $statement->fetchAll(PDO::FETCH_ASSOC);
    return $test;
}