<?php

session_start();

$login = trim($_POST['login']);
$pass = $_POST['password'];
$pass_hash = password_hash($pass, PASSWORD_BCRYPT); 

require_once 'db_connect.php';

$connection = new mysqli($host, $db_user,$db_pass, $db_name);
if($connection->connect_errno != 0){
    echo "ERROR: ".$connection->connect_errno; 
    die();
}
else {
    
    $query= "SELECT * FROM users WHERE BINARY login = '$login'";
    
    if($queryResult = $connection->query($query)) {
       
        if($users_number = $queryResult->num_rows) {
            $row = $queryResult->fetch_assoc();
        
            if(password_verify($pass, $row['password'])) {

                unset($_SESSION['error']);
                $_SESSION['logged_in'] = true;
                $_SESSION['login'] = $row['login'];
                $_SESSION['wood'] = $row['wood'];
                $_SESSION['iron'] = $row['iron'];
                $_SESSION['stone'] = $row['stone'];
                $_SESSION['corn'] = $row['corn'];
                $_SESSION['premium'] = $row['premium'];

                $queryResult->free_result();
                header('Location: game.php');
            } 
            else { //bad password

                $_SESSION['error'] = '<span class="error">Nie znaleziono takiego użytkownika</span>';
                header('Location: index.php');
            }
        }
        else { //bad login

            $_SESSION['error'] = '<span class="error">Nie znaleziono takiego użytkownika</span>';
            header('Location: index.php');
        }

    }

    $connection->close();
}


?>