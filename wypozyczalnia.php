<?php
      session_start();
      if (!isset($_SESSION['zalogowany']))
      {
      	header('Location: index.php');
      	exit();
      }

 ?>
 <!DOCTYPE HTML>
 <html lang="pl" >
 <head>
       <meta charset="utf-8" />
       <title>Biblioteka Internetowa Pawła Mołka</title>
       <meta name="description" content="Masz wgląd do wszystkich dostępnych książek posiadanych przeze mnie" />
       <meta name="keywords" content="książka, biblioteka, biblioteka online, Paweł Mołek, wypożyczalnia, książki" />
      <!-- teraz tag dla Internet explorera -->
      <meta http-http-equiv="X-UA-Compatible" content="IE=edge,chrome=1" />
</head>
<body>
      <?php

      	echo "<p>Witaj ".$_SESSION['user'].'! [ <a href="logout.php">Wyloguj się!</a> ]</p>';
            foreach($_POST['idksiazki'] as $id)
            {
                  echo $id;
            }

      ?>



</body>
</html>
