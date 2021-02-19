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
      $polaczenie = @new mysqli($host, $db_user, $db_password, $db_name);
      //przepuszczenie jej przez funkcję zabezpieczającą wstrzykiwanie SQL
      mysqli_query($polaczenie, "SET CHARSET utf8");
      mysqli_query($polaczenie, "SET NAMES 'utf8' COLLATE 'utf8_polish_ci'");
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
            //opcja wylogowania
      	echo "<p>Witaj ".$_SESSION['user'].'! [ <a href="logout.php">Wyloguj się!</a> ]</p>';
            //pętla dodająca każdego checkboxa z wypożyczeniem z pliku index do tabeli wypożyczenie
            foreach($_POST['idksiazki'] as $id)
            {
                  //polaczenie z baza rownoczesnie z wyslaniem wpisu do tabeli
                  $wypozyczenie = @mysqli_query($polaczenie,("INSERT INTO wypozyczenia VALUES (NULL, {$_SESSION['userid']}, $id , CURRENT_TIMESTAMP)"));
                  if($wypozyczenie)
                  {
                        $wypozyczona = @mysqli_query($polaczenie,("UPDATE ksiazki SET available=0 WHERE bookid=$id"));
                        echo "Rekord został dodany poprawnie";
                  }
                  else
                  {
                        echo "Błąd nie udało się dodać nowego rekordu";
                  }
            }
            echo "<br />";

            echo $_SESSION['userid'];
            /*
            //wyswietlenie 1 rekordu -- dramat
            echo $_SESSION['user'];
            $temp = $_SESSION['user'];
            $tests = "SELECT userid FROM uzytkownicy WHERE user = $temp";
            $result = mysqli_query($polaczenie, $tests) or die("Problemy z odczytem danych!");
            while ($row = $result->fetch_assoc())
            {
                echo $row['userid']."<br>";
          }*/

      ?>



</body>
</html>
