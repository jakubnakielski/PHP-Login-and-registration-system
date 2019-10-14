<?php
session_start();

if(!isset($_SESSION['logged_in'])){
    header('Location: index.php');
    die();
} 

$time2 = new DateTime();
$time1 = new DateTime($_SESSION['premium']);

$difference = $time1->diff($time2);

if($time1>$time2) {
echo 'Pozostało ci dni: '.$difference->format(' %d dni %h godzin %i minut %S sekund');
}else {
  echo 'Premium nieaktywne od: '.$difference->format(' %d dni %h godzin %i minut %S sekund');
}
// echo $difference->format(' %d dni %h godzin %i minut %S sekund');
?>

<!DOCTYPE html>
<html lang="pl">
  <head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <meta http-equiv="X-UA-Compatible" content="ie=edge" />
    <title>Metropolis - zbuduj swoje miasto</title>
    <link rel="stylesheet" href="main.css" >
    <style>
      body {
        background-image: linear-gradient(rgba(0,0,0,.75), rgba(0,0,0,.75) ), url('https://www.letemsvetemapplem.eu/wp-content/uploads/2018/04/civ6.jpg');
        color: white;
      }
      </style>
  </head>
  <body>

  <div class="gameBox">
    <header class="header">
      <h1 class="header__title">Surowce</h1>
      <header class="header__user"> 
          <?php
          echo $_SESSION['login'];
          ?>
      </header>
    </header>
<div class="timeBox">
  <div class="timeBox__caption">Czas lokalny serwera: &nbsp;</div>
  
  <div class="timeBox__time">
    <?php
      echo $time2->format('Y-m-d h:i:s');
    ?>
  </div>
  
</div>
    
<div class="content">
    <div class="materials">

        <div class="materials__wood">
            <div class="wood__img"></div>
            <div class="wood__number">
              <?php
                echo $_SESSION['wood'];
              ?>
            </div>
        </div>

          <div class="materials__clay">
            <div class="clay__img"></div>
            <div class="clay__number">
              <?php
                echo $_SESSION['iron'];
              ?>
            </div>
        </div>

        

        <div class="materials__corn">
            <div class="corn__img"></div>
            <div class="corn__number">
              <?php
                echo $_SESSION['corn'];
              ?>
            </div>
        </div>

        <div class="materials__stone">
            <div class="stone__img"></div>
            <div class="stone__number">
              <?php
                echo $_SESSION['stone'];
              ?>
            </div>
        </div>

        <div class="materials__premium">
            <div class="premium__img"></div>
            <div class="premium__number">
              <?php
                echo $_SESSION['premium'];
              ?>
            </div>
        </div>
     
          
        
   </div>
   <div class="rightBar">
      
          <div class="logoutBox">
            <a href="logout.php">Wyloguj się</a> 
          </div>
    </div>
  </div>
  </body>
  
</html>
<!-- // $dataczas = new DateTime('2250-05-01 09:33:59');
	
// 	echo "Data i czas serwera: ".$dataczas->format('Y-m-d H:i:s')."<br>";
	
// 	$koniec = DateTime::createFromFormat('Y-m-d H:i:s', '2050-05-01 09:33:59');
	
// 	$roznica = $dataczas->diff($koniec);
	
// 	if($dataczas<$koniec)
// 	echo "Pozostało premium: ".$roznica->format('%y lat, %m mies, %d dni, %h godz, %i min, %s sek');
// 	else
// 	echo "Premium nieaktywne od: ".$roznica->format('%y lat, %m mies, %d dni, %h godz, %i min, %s sek');	 -->








