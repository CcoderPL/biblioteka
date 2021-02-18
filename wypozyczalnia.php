<?php
      session_start();
      if (!isset($_SESSION['zalogowany']))
      {
      	header('Location: index.php');
      	exit();
      }
      //wymaganie pliku konfiguracyjnego do połączenia z bazą MySQL
      require_once 'connect.php';
      //utworzenie zmiennej do weryfikacji połączenia z bazą
      $connection = @new mysqli($host, $db_user, $db_password, $db_name);
      //przepuszczenie jej przez funkcję zabezpieczającą wstrzykiwanie SQL
      mysqli_query($connection, "SET CHARSET utf8");
      mysqli_query($connection, "SET NAMES 'utf8' COLLATE 'utf8_polish_ci'");
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
            echo $_SESSION['bookid'];
            foreach($_POST['idksiazki'] as $id)
            {
                  echo $id;
            }
      /*      $wypozyczenie = @mysqli_query($connection,("INSERT INTO wypozyczenia VALUES (NULL, 33, 5, '2021-02-15 14:04:50')"));
            if($wypozyczenie)
            {
                  echo "Rekord został dodany poprawnie";
            }
            else
            {
                  echo "Błąd nie udało się dodać nowego rekordu";
            }*/
      ?>



</body>
</html>
