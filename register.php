<?php
// header('Content-Type: application/json');
session_start();
error_reporting(0);

if(isset($_SESSION['logged_in']) && ($_SESSION['logged_in'] == true)){
    header('Location: game.php');
    die();
}

if(isset($_POST['email'])) {

    $login = trim($_POST['login']);
    $email = trim($_POST['email']);
    $password1 = trim($_POST['password1']);
    $password2 = trim($_POST['password2']);
    $captcha_id = $_POST['g-recaptcha-response'];

   //test jak by ktoś na froncie wywalił required z checkboxa | remembering written data | what is header('Content-Type: application/json') sql INjections | login button JS(apka klikanie + oraz - )
                            //credentials to db / PE timer / throw and catch in login.php | Capital letters the same         
    $OK = true;

        if((strlen($login) < 4) || (strlen($login) > 16) ) {
            $OK = false;
            $_SESSION['e_login'] = 'Login musi mieć od 4 do 16 znaków!';
        }

        if(ctype_alnum($login) == false) {
            $OK = false;
            $_SESSION['e_login'] = 'Login może składać się tylko z liter i cyfr!';
        }

        if(strlen($password1) < 8) {
            $OK = false;
            $_SESSION['e_pass'] = 'Hasło musi mieć przynajmniej 8 znaków!'; // 20 max?
        }

        if($password1 != $password2) {
            $OK = false;
            $_SESSION['e_pass'] = 'Hasła nie są identyczne!';
        }

        if(filter_var($email, FILTER_VALIDATE_EMAIL)== false) { // add SANITIZE EMAIL?
            $OK = false;
            $_SESSION['e_mail'] = 'Podaj poprawny e-mail!';
        }
        
        if(!isset($_POST['checkbox'])) { 
            $OK = false;
            $_SESSION['e_checkbox'] = 'Potwierdź akceptacje regulaminu!';
        }
      
        //captcha
        $secret_key ='6Lel-LwUAAAAAMSDWe2T2pnHA_7iQbnYHKZJJ5R9';

        $api_request = "https://www.google.com/recaptcha/api/siteverify?secret=$secret_key&response=$captcha_id";

        $api_response = json_decode(file_get_contents($api_request));
        
        if(!$api_response->success) {
            $OK = false;
            $_SESSION['e_captcha'] = 'Potwierdź recaptche';
        }

             require_once 'db_connect.php';

            // mysqli_report(MYSQLI_REPORT_STRICT);
                try {
                    $connection = new mysqli($host, $db_user, $db_pass, $db_name); // PDO better
                        if($connection->connect_errno != 0) {
                            throw new Exception($connection->connect_errno);
                        }
                        else {
                            
                            //Does login already exist?
                            $sql_query = "SELECT id FROM users WHERE login='$login'";
                            if($query_result = $connection->query($sql_query)){
                                
                                if($query_result->num_rows > 0) {
                                    $OK = false;
                                    $_SESSION['e_login'] = 'Istnieje już użytkownik o podanym nicku!';   
                                }

                                $query_result->close(); 
                            }
                            else {
                                throw new Exception('Błąd przy wykonywaniu kwerendy');
                            }
                            
                            //Does e-mail already exist?
                            $sql_query = "SELECT id FROM users WHERE login='$email'";
                            if($query_result = $connection->query($sql_query)){
                                
                                if($query_result->num_rows > 0) {
                                    $OK = false;
                                    $_SESSION['e_mail'] = 'Ten adres e-mail jest już przypisany do innego konta!';   
                                }

                                $query_result->close(); 
                            }
                            else {
                                throw new Exception('Błąd przy wykonywaniu kwerendy');
                            }
                            
                            //INSERT USER INTO TABLE   
                            if($OK == true ) {
                                
                                $pass_hash = password_hash($password2, PASSWORD_BCRYPT); 

                                $sql_insert = "INSERT INTO users VALUES (NULL, '$login', '$email', '$pass_hash', '100','100','100','100', NOW() + INTERVAL 7 DAY)";
                                if($query_result = $connection->query($sql_insert)) {
                                        header('Location: index.php');
                                }
                                else {
                                    throw new Exception('Błąd przy wstawianiu usera do bazy');
                                }
                            }
                            else { // Remembering entered data
                                $_SESSION['var_login'] = $login;
                                $_SESSION['var_email'] = $email;
                                if(isset($_POST['checkbox'])){
                                    $_SESSION['var_checkbox'] = $_POST['checkbox'];
                                } 
                            }
                            
                            $connection->close();
                        } 
                }
            
                catch(Exception $e) {
                    echo $e;
                    die();
                }
            
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>METROPOLIS - Zarejestruj się</title>
    <link href="https://fonts.googleapis.com/css?family=Montserrat&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="register.css">
    <script src="https://www.google.com/recaptcha/api.js" async defer></script>
</head>
<body>

<div class="box">
    <h1>Rejestracja</h1>
    <form method="post" autocomplete="off">

        <div class="loginInput">
            <input type="text" id="login" name="login" placeholder="Login"  value="<?php 
                if(isset($_SESSION['var_login'])) {
                    echo $_SESSION['var_login'];
                    unset ($_SESSION['var_login']);
                }
            ?>"> 
        </div>
        <!-- OPTIONAL required oninvalid="this.setCustomValidity('Podaj login')" oninput="setCustomValidity('')" -->
            <?php
            if(isset($_SESSION['e_login'])) {
                echo '<span class="error">'.$_SESSION['e_login'].'</span>';
                unset($_SESSION['e_login']);
            }
            ?>

        <div class="emailInput">
            <input type="email" id="password" name="email" placeholder="E-mail" value="<?php 
                if(isset($_SESSION['var_email'])) {
                    echo $_SESSION['var_email'];
                    unset($_SESSION['var_email']);
                }
            ?>"> 
        </div>
<!-- OPTIONAL required oninvalid="this.setCustomValidity('Podaj e-mail')"  $_POST['var_checkbox']
             oninput="setCustomValidity('')" -->
            <?php 
            if(isset($_SESSION['e_mail'])) {
                echo '<span class="error">'.$_SESSION['e_mail'].'</span>';
                unset($_SESSION['e_mail']);
            }
            ?>
        <div class="passInput">
            <input type="password" id="password" name="password1" placeholder="Hasło" >
        </div>
<!-- OPTIONAL required oninvalid="this.setCustomValidity('Podaj hasło')"
             oninput="setCustomValidity('')" -->
            <?php
            if(isset($_SESSION['e_pass'])) {
                echo '<span class="error">'.$_SESSION['e_pass'].'</span>';
                unset($_SESSION['e_pass']);
            }
            ?>

        <div class="passInput">
            <input type="password" id="password" name="password2" placeholder="Powtórz hasło" >
        </div>
<!-- OPTIONAL required oninvalid="this.setCustomValidity('Powtórz hasło')"
            oninput="setCustomValidity('')" -->
        <div class="checkboxContainer">
            <input id="checkbox" type="checkbox" name="checkbox" <?php 
                    if(isset($_SESSION['var_checkbox'])) {
                        echo 'checked';
                        unset($_SESSION['var_checkbox']);
                    }
                ?>
            >
            <label for="checkbox">Akceptuję <a href="#">regulamin</a> </label>
        </div>
<!-- OPTIONAL required oninvalid="this.setCustomValidity('Aby się zarejestrować musisz zaakceptować regulamin')"
            oninput="setCustomValidity('')" -->
                <?php
                if(isset($_SESSION['e_checkbox'])) {
                    // echo '<span class="error checkboxError">'.$_SESSION['e_checkbox'].'</span>'; //!!!!!!!!!!!!!!!!!!!!!!!!!!!!
                    unset($_SESSION['e_checkbox']);
                }
                ?>

        <div class="g-recaptcha" data-sitekey="6Lel-LwUAAAAAK7_RSBOLcqimD7fTsCJ-gIIqklw"></div>
                <?php
                if(isset($_SESSION['e_captcha'])) {
                    echo '<span class="error">'.$_SESSION['e_captcha'].'</span>';
                    unset($_SESSION['e_captcha']);
                }
                ?>
        <div class="submitInput">
            <input class="submit" type="submit" value="ZAREJESTRUJ SIĘ">
        </div>
    </form>

    <div class="outerLinkBox">
        <a class="outerLink" href="index.php">Zaloguj się </a>
    </div>
    
</div>  
</body>
</html>

