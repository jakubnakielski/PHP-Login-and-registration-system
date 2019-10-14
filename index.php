<?php
session_start();


if(isset($_SESSION['logged_in'])){
    header('Location: game.php');
    die();
}
?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Document</title>
    <link rel="stylesheet" href="main.css" >
    <link href="https://fonts.googleapis.com/css?family=Montserrat&display=swap" rel="stylesheet">
</head>
<body>

<div class="box">
<h3>METROPOLIS</h3>
    <form action="log_in.php" method="post" autocomplete="off">

    <div class="loginInput">
        <label for="login">Login: </label>
        <input type="text" id="login" name="login">
    </div>

    <div class="passInput">
        <label for="password">Hasło: </label>
        <input type="password" id="password" name="password">
    </div>
    <?php
    if(isset($_SESSION['error'])){
        echo $_SESSION['error'];
        unset ($_SESSION['error']);
    }
    ?>
    <div class="submitInput">
    <input class="submit" type="submit" value="ZALOGUJ SIĘ">

    </div>
    </form>

    <div class="bottomBox">
        <a class="bottomBox__link" href="register.php">Zarejestruj się!</a>
        <a class="bottomBox__link bottomBox__link--red" href="register.php">Zapomniałem hasła</a>
    </div>
</div>  
</body>
</html>