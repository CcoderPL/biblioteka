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
      foreach($_POST['idzwroconejksiazki'] as $id)
      {
            //polaczenie z baza rownoczesnie z wyslaniem wpisu do tabeli
            $dataWypozyczenia = mysqli_query($polaczenie, ("SELECT borrowdate FROM wypozyczenia WHERE bookid=$id"));
            $row = mysqli_fetch_assoc($dataWypozyczenia);
            $date = $row['borrowdate'];
            $zwrot = @mysqli_query($polaczenie,("INSERT INTO zwroty VALUES (NULL, $id , {$_SESSION['userid']}, '$date', CURRENT_TIMESTAMP)"));
            if($zwrot)
            {
                  $zwrocona = @mysqli_query($polaczenie,("UPDATE ksiazki SET available=1 WHERE bookid=$id"));
                  $usuniecieWpisuWwypozyczonych = @mysqli_query($polaczenie,("DELETE FROM wypozyczenia WHERE bookid=$id AND userid={$_SESSION['userid']}"));
            }
            else
            {
                  echo "Błąd nie udało się dodać nowego rekordu";
            }
      }




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



      mysqli_close($zwrocona, $usuniecieWpisuWwypozyczonych, $zwrot, $dataWypozyczenia);
      header('Location: wypozyczalnia.php');
      exit();
?>
