<header style="display: flex; justify-content: space-around;">
    <nav style="display: flex; align-items:center">
        <img src="images/home_icon.png" width="50px" height="50px">
        <a href="index.php">Home</a>
    </nav>
    <nav style="display: flex; align-items:center">
        <img src="images/menu_icon.png" width="50px" height="50px">
        <a href="index.php">Products</a>
    </nav>
    <?php if (isset($_SESSION['user_id'])){ ?>
    <nav style="display: flex; align-items:center">
        <img src="images/cart_icon.png" width="50px" height="50px">
        <a href="index.php?product_purchased">Cart</a>
    </nav>
    <nav style="display: flex; align-items:center">
        <img src="images/profile_icon.png" width="50px" height="50px">
        <a href="index.php?profile">Profile</a>
    </nav>
    <?php }?>
    <nav style="display: flex; align-items:center">
        <img src="images/profile_icon.png" width="50px" height="50px">
        <a href="index.php?login"> <?php echo isset($_SESSION['user_id']) ? 'logout' : 'login' ?></a>
    </nav> 

</header>