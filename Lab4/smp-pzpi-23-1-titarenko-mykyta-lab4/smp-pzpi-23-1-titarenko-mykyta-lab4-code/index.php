<?php
$db = new PDO('sqlite:shop.db');
ini_set('session.save_path', '.');
session_start();
if (isset($_SESSION['user_id'])) {
    $user_id = $_SESSION['user_id'];
}

require_once('db_products_functions.php');
require_once('db_user_functions.php');

switch (true) {
    //Зміна товарів у сесії при завершенні купівлі зі сторінки products.php
    case isset($_GET["product_purchased"]):
        require_once('header.php');
        if (!isset($_SESSION['user_id'])) {
            require_once('page404.php');
            break;
        }
        if (isset($_POST['quantity'])) {
            foreach ($_POST['quantity'] as $index => $quantity) {
                if ($quantity != "") {
                    if ($quantity > 0) {
                        if (productInCartExist($index)[0]['found'] == 1) {
                            updateProductFromCart($index, $quantity);
                        } else {
                            insertProduct($index, $quantity);
                        }
                    } else if ($quantity == 0 && productInCartExist($index)) {
                        deleteFromCart($index);
                    }
                }
            }
        }
        require_once('cart.php');
        break;
    //Видалення товару з сесії зі сторінки cart.php
    case isset($_GET["product_deleted"]):
        require_once('header.php');
        deleteFromCart($_POST["index"]);
        require_once('cart.php');
        break;
    case isset($_GET["login"]):
        //Вихід з акаунту
        if (isset($_SESSION['user_id'])) {
            session_unset();
            session_destroy();
            require_once('header.php');
            require_once('login.php');
        } else {
            if (isset($_POST['userName']) && isset($_POST['password'])) {
                $login = $_POST['userName'];
                $password = $_POST['password'];
                if (strlen($login) <= 1 || strlen($password) <= 1) {
                    $dataSuccess = false;
                    require_once('header.php');
                    require_once('login.php');
                    break;
                }
                //Входження в акаунт
                if (userExist($login)['found'] == 1) {
                    $passwordSuccess = login($login, $password);
                    if ($passwordSuccess) {
                        require_once('header.php');
                        require_once('products.php');
                    } else {
                        require_once('header.php');
                        require_once('login.php');
                    }
                } else {
                    //Створення нового акаунту
                    registration($login, $password);
                    require_once('header.php');
                    require_once('products.php');
                }
            } else {
                require_once('header.php');
                require_once('login.php');
            }
        }
        break;
    case isset($_GET["profile"]):
        //Завантаження зображення
        if (isset($_FILES['userfile']) && $_FILES['userfile']['name'] != "") {
            $extension = pathinfo($_FILES['userfile']['name'], PATHINFO_EXTENSION);
            $file = glob('uploads/' . $user_id . '.*');
            if (!empty($file[0])) {
                unlink($file[0]);
            }
            move_uploaded_file($_FILES['userfile']['tmp_name'], 'uploads/' . $user_id . '.' . $extension);
        } else if (isset($_POST['name'])) {
            //Зміна особистих даних користувача
            $changeSuccess = changeUserInformation($_POST['name'], $_POST['surname'], $_POST['birthdayDate'], $_POST['description']);
        }
        require_once('header.php');
        require_once('profile.php');
        break;
    //Перенаправлення на сторінку products.php
    default:
        require_once('header.php');
        if (isset($_SESSION['user_id'])) {
            require_once('products.php');
        } else {
            require_once('page404.php');
        }
}
require_once('footer.php');
exit;
?>