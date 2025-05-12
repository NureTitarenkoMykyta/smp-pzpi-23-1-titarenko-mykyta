<?php
$db = new PDO('sqlite:shop.db');
ini_set('session.save_path', '.');
session_start();
if (!isset($_SESSION['user_id'])) {
    $db->exec('INSERT INTO user DEFAULT VALUES');
    $user_id = $db->lastInsertId();
    $_SESSION['user_id'] = $user_id;
} else {
    $user_id = $_SESSION['user_id'];
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

//Перевірка на наявність товару у кошику користувача
function isExist($index){
    global $db;
    global $user_id;
    $statement = $db->prepare('SELECT EXISTS(SELECT 1 FROM user_product WHERE user_id = :user_id AND product_id = :product_id) AS found');
    $statement->bindParam(':user_id', $user_id);
    $statement->bindParam(':product_id', $index);
    $statement->execute();
    $test = $statement->fetchAll(PDO::FETCH_ASSOC);
    return $test;
}

//Оновлення кількості товару у кошику користувача
function updateProductFromCart($index, $quantity){
    global $db;
    global $user_id;
    $statement = $db->prepare('UPDATE user_product SET quantity = :quantity WHERE user_id = :user_id AND product_id = :product_id');
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

//Зміна товарів у сесії при завершенні купівлі зі сторінки products.php
if (isset($_GET["product_purchased"])) {
    foreach ($_POST['quantity'] as $index => $quantity) {
        if ($quantity != "") {
            if ($quantity > 0) {
                if (isExist($index)[0]['found'] == 1){
                    updateProductFromCart($index, $quantity);
                } else {
                    insertProduct($index, $quantity);
                }
            } else if ($quantity == 0 && isExist($index)) {
                deleteFromCart($index);
            }
        }
    }
    header('Location: cart.php');

    //Видалення товару з сесії зі сторінки cart.php
} else if (isset($_GET["product_deleted"])) {
    deleteFromCart($_POST["index"]);
    header('Location: cart.php');
    //Перенаправлення на сторінку products.php
} else {
    header('Location: products.php');
}
exit;
?>