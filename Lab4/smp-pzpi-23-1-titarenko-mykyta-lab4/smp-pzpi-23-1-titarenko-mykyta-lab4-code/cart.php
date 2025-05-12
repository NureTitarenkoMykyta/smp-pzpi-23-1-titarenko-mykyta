<?php
require_once('db_products_functions.php');
?>
<!DOCTYPE html>
<html lang="en" style="height: 100%; display: flex; flex-direction: column;">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cart</title>
</head>

<body style="height: 100%; display: flex; flex-direction: column;">
    <?php
    $productsFromCart = getProductsFromCart();
    //Відображення товарів
    if (count($productsFromCart) > 0) { ?>
        <main style="display: flex; justify-content: center; align-items: center; flex: 1; flex-direction: column">
            <form method="POST" action="index.php?product_deleted">
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
</body>

</html>