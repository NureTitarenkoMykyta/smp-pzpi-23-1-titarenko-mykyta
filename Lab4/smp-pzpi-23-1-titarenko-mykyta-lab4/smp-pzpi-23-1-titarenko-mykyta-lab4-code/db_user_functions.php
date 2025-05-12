<?php
//Оновлення кількості товару у кошику користувача
function updateProductFromCart($index, $quantity)
{
    global $db;
    global $user_id;
    $statement = $db->prepare('UPDATE user_product SET quantity = :quantity WHERE user_id = :user_id AND product_id = :product_id');
    $statement->bindParam(':user_id', $user_id);
    $statement->bindParam(':product_id', $index);
    $statement->bindParam(':quantity', $quantity);
    $statement->execute();
}

//Реєстрація нового користувача
function registration($login, $password)
{
    global $db;
    $statement = $db->query('INSERT INTO user (login, password) VALUES (:login, :password)');
    $statement->bindParam(':login', $login);
    $statement->bindParam(':password', $password);
    $statement->execute();
    $user_id = $db->lastInsertId();
    $_SESSION['user_id'] = $user_id;
    lastLoginChange();
}

//Зміна дати і часу останнього входження користувача у систему
function lastLoginChange(){
    global $db;
    global $user_id;
    $statement = $db->query("UPDATE user SET last_login = datetime('now') WHERE user_id = :user_id");
    $statement->bindParam(':user_id', $user_id);
    $statement->execute();
}

//Перевірка на існування користувача з вказаним логіном
function userExist($login)
{
    global $db;
    $statement = $db->prepare('SELECT EXISTS(SELECT 1 FROM user WHERE login = :login) AS found');
    $statement->bindParam(':login', $login);
    $statement->execute();
    return $statement->fetch(PDO::FETCH_ASSOC);
}

//Входження користувача в акаунт. Якщо пароль неправильний, повертає false
function login($login, $password)
{
    global $db;
    $statement = $db->prepare('SELECT user_id FROM user WHERE login = :login AND password = :password');
    $statement->bindParam(':login', $login);
    $statement->bindParam(':password', $password);
    $statement->execute();
    $data = $statement->fetch(PDO::FETCH_ASSOC);
    if ($data) {
        $user_id = $data['user_id'];
        $_SESSION['user_id'] = $user_id;
        lastLoginChange();
    }
    return $data;
}

//Зміна особистих даних користувача
function changeUserInformation($name, $surname, $birthdayDate, $description)
{
    $currentDate = new DateTime();
    $userDate = new DateTime($birthdayDate);
    $ageDifference = $currentDate->diff($userDate);
    if (strlen($name) <= 1 || strlen($surname) <= 1 || $ageDifference->y < 18 || strlen($description) < 50) {
        return false;
    }
    global $db;
    global $user_id;
    $statement = $db->prepare('UPDATE user SET user_name = :user_name, user_surname = :user_surname, birthday_date = :birthday_date, description = :description WHERE user_id = :user_id');
    $statement->bindParam(':user_id', $user_id);
    $statement->bindParam(':user_name', $name);
    $statement->bindParam(':user_surname', $surname);
    $statement->bindParam(':birthday_date', $birthdayDate);
    $statement->bindParam(':description', $description);
    $statement->execute();
    return true;
}

//Отримання особистих даних користувача
function getUserInformation(){
    global $db;
    global $user_id;
    $statement = $db->prepare('SELECT user_name, user_surname, birthday_date, description FROM user WHERE user_id = :user_id');
    $statement->bindParam(':user_id', $user_id);
    $statement->execute();
    return $statement->fetch(PDO::FETCH_ASSOC);
}