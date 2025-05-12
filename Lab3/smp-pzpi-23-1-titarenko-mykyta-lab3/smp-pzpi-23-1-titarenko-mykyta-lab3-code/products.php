<?php
$db = new PDO('sqlite:shop.db');
ini_set('session.save_path', '.');
session_start();

if (isset($_SESSION['user_id'])) {
    $user_id = $_SESSION['user_id'];
} 

//Отримання всіх товарів з магазину
function getProducts()
{
    global $db;
    global $user_id;
    $statement = $db->query('SELECT product_id, product_name, product_price FROM product');
    $statement->bindParam(':user_id', $user_id);
    return $statement->fetchAll(PDO::FETCH_ASSOC);
}
?>

<!DOCTYPE html>
<html lang="en" style="height: 100%; display:flex; flex-direction: column;">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
</head>
<body style="height: 100%; display:flex; flex-direction: column;">
    <header>
        <?php
            require_once 'header.html';
        ?>
    </header>
    <main style="display: flex; flex: 1; justify-content: center; align-items: center;">
        <form method="POST" action="index.php?product_purchased=true" style="display: flex; flex-direction: column;">
                <table>
                <!-- Відображення товарів -->
                <?php foreach (getProducts() as $product) {?>
                    <tr>
                        <th><img src="images/product<?php echo $product['product_id'] ?>.png" width="200"></th>
                        <th><?php echo $product['product_name']?></th>
                        <th><input type="number" min="0" name="quantity[<?php echo $product['product_id']?>]"></th>
                        <th><?php echo $product['product_price']?></th>
                    </tr>
                <?php }?>
                </table>
                <input type="submit" style="margin-left: auto;" value="Send">
        </form>
    </main>
    <?php require_once 'footer.html'; ?>
</body>
</html>