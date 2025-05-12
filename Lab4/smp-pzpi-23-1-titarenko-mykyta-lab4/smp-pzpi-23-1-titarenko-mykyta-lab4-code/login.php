<!DOCTYPE html>
<html lang="en" style="height: 100%; display: flex; flex-direction: column">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>
</head>
<body style="height: 100%; display: flex; flex-direction: column">
    <form method="POST" action="index.php?login" style="display: flex; align-items: center; justify-content: center; flex: 1; flex-direction: column">
        <input type="text" name="userName" placeholder="User Name" style="font-size: 30px; margin: 50px">
        <input type="text" name="password" placeholder="Password" style="font-size: 30px; margin: 50px">
        <input type="submit" value="login" style="font-size: 30px; margin-left: 300px;">
        <p style="font-size: 30px; margin: 50px; color: red; visibility: <?php echo !isset($dataSuccess) || $dataSuccess ? 'collapse' : 'visible'?>;">Логін і пароль повинні складатися з двох або більше символів</p>
        <p style="font-size: 30px; margin: 50px; color: red; visibility: <?php echo !isset($passwordSuccess) || $passwordSuccess ? 'collapse' : 'visible'?>;">Пароль неправильний</p>
    </form>
</body>
</html>