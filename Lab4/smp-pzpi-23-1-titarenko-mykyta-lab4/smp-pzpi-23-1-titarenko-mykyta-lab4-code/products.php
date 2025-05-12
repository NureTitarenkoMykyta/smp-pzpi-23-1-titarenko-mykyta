<?php
require_once('db_products_functions.php');
?>

<!DOCTYPE html>
<html lang="en" style="height: 100%; display:flex; flex-direction: column;">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Products</title>
</head>
<body style="height: 100%; display:flex; flex-direction: column;">
    <main style="display: flex; flex: 1; justify-content: center; align-items: center;">
        <form method="POST" action="index.php?product_purchased" style="display: flex; flex-direction: column;">
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
</body>
</html>