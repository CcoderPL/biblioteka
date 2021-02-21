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

      //pętla dodająca każdego checkboxa z wypożyczeniem z pliku index do tabeli wypożyczenie
      foreach($_POST['idksiazki'] as $id)
      {
            //polaczenie z baza rownoczesnie z wyslaniem wpisu do tabeli
            $wypozyczenie = @mysqli_query($polaczenie,("INSERT INTO wypozyczenia VALUES (NULL, {$_SESSION['userid']}, $id , CURRENT_TIMESTAMP)"));
            if($wypozyczenie)
            {
                  $wypozyczona = @mysqli_query($polaczenie,("UPDATE ksiazki SET available=0 WHERE bookid=$id"));
            }
            else
            {
                  echo "Błąd nie udało się dodać nowego rekordu";
            }
      }
      mysqli_close($wypozyczenie, $wypozyczona);
      header('Location: wypozyczalnia.php');
      exit();
?>
