<?php
$db = new PDO('sqlite:shop.db');
ini_set('session.save_path', '.');
session_start();
if (isset($_SESSION['user_id'])) {
    $user_id = $_SESSION['user_id'];
} 
require_once 'header.html';

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
?>
<!DOCTYPE html>
<html lang="en" style="height: 100%; display: flex; flex-direction: column;">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
</head>

<body style="height: 100%; display: flex; flex-direction: column;">
    <?php
    $productsFromCart = getProductsFromCart();
    //Відображення товарів
    if (count($productsFromCart) > 0) { ?>
        <main style="display: flex; justify-content: center; align-items: center; flex: 1; flex-direction: column">
            <form method="POST" action="index.php?product_deleted=true">
                <table style="border: 3px solid black; border-collapse: collapse;">
                    <tr>
                        <th style="border: 3px solid black">id</th>
                        <th style="border: 3px solid black">name</th>
                        <th style="border: 3px solid black">price</th>
                        <th style="border: 3px solid black">count</th>
                        <th style="border: 3px solid black">sum</th>
                        <th style="border: 3px solid black"></th>
                    </tr>
                    <?php
                    foreach ($productsFromCart as $product) { ?>
                        <tr>
                            <th style="border: 3px solid black"><?php echo $product['product_id'] ?></th>
                            <th style="border: 3px solid black"><?php echo $product['product_name'] ?></th>
                            <th style="border: 3px solid black"><?php echo $product['product_price'] ?></th>
                            <th style="border: 3px solid black"><?php echo $product['quantity'] ?></th>
                            <th style="border: 3px solid black"><?php echo $product['total_price'] ?>
                            </th>
                            <th style="border: 3px solid black">
                                <button type="submit" name="index" value="<?php echo $product['product_id'] ?>">Delete</button>
                            </th>
                        </tr>
                    <?php } ?>
                    <tr>
                        <th style="border: 3px solid black">Total</th>
                        <th style="border: 3px solid black"></th>
                        <th style="border: 3px solid black"></th>
                        <th style="border: 3px solid black"></th>
                        <th style="border: 3px solid black"><?php echo getTotalPrice() ?></th>
                        <th style="border: 3px solid black"></th>
                    </tr>
                </table>
                <div style="display: flex; flex-direction: row; margin-left: 150px;">
                    <button style="margin: 10px"><a href="products.html">cancle</a></button>
                    <button style="margin: 10px" type="button">pay</button>
                </div>
                <?php
    } else {
        ?>
                <main style="display: flex; justify-content: center; align-items: center; flex: 1;">
                    <a href="product.html">Перейти до покупок</a>
                    <?php
    } ?>
        </form>
    </main>
    <?php require_once 'footer.html'; ?>
</body>

</html>