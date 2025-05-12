<!DOCTYPE html>
<html lang="en" style="display:flex; height:100%; flex-direction:column">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Profile</title>
</head>
<?php 
require_once('db_user_functions.php');
?>
<body style="display:flex; height:100%; flex-direction:column">
    <div style="display: flex; flex: 1;">
        <form method="POST" enctype="multipart/form-data" action="index.php?profile" style="display: flex">
            <div style="display: flex; flex: 1; flex-direction: column;">
                <img src="<?php $file = glob('uploads/' . $user_id . '.*'); 
                echo empty($file) ? "images/image_icon.png" : $file[0] ?>" width="500">
                <input id="file" type="file" name="userfile" style="display: none" accept="image/*" onchange="this.form.submit()"/>
                <button onclick="document.getElementById('file').click()" type="button">
                    Upload
                </button>
            </div>
        <div style="display: flex; flex: 3; flex-direction: column;">
            <div style="display: flex; flex-direction: row;">
                <?php $userInformation = getUserInformation() ?>
                <input type="text" value="<?php echo $userInformation['user_name']?>" name="name" placeholder="Name" style="font-size: 30px; margin: 50px">
                <input type="text" value="<?php echo $userInformation['user_surname']?>" name="surname" placeholder="Surname" style="font-size: 30px; margin: 50px">
                <input type="date" value="<?php echo $userInformation['birthday_date']?>" name="birthdayDate" style="font-size: 30px; margin: 50px">
            </div>
            <textarea name="description" placeholder="description" style="font-size: 30px; margin: 50px; height: 300px;"><?php echo $userInformation['description']?></textarea>
            <p style="font-size: 30px; margin: 50px; color: red; visibility: <?php echo !isset($changeSuccess) || $changeSuccess ? 'collapse' : 'visible'?>;">Поля заповнені неправильно</p>
            <input type="submit" value="save" style="font-size: 30px; margin-left: 1000px;">
        </div>
        </form>
    </div>
</body>
</html>