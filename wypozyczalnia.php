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
      	echo "<p>Witaj ".$_SESSION['user'].'! [ <a href="logout.php">Wyloguj się!  </a> ] <a href="index.php">Strona Główna</a></p>';

            $zapytanieWypozyczenia = "SELECT wypozyczenia.borrowdate, wypozyczenia.userid, wypozyczenia.bookid, ksiazki.bookid, ksiazki.title, uzytkownicy.user, uzytkownicy.userid
            FROM wypozyczenia, ksiazki, uzytkownicy WHERE uzytkownicy.userid = wypozyczenia.userid AND
            ksiazki.bookid = wypozyczenia.bookid AND wypozyczenia.userid = {$_SESSION['userid']}";

            //wysłanie zapytania do bazy, konieczne jest do tego korzystanie z zmiennej weryfikującej połączenie z bazą
            $rezultatWypozyczenia = mysqli_query($polaczenie, $zapytanieWypozyczenia);

            //zmienna potrzebna do wykonania pętli, sprawdza ile rzędów zwróciło zapytanie
            $ile = mysqli_num_rows($rezultatWypozyczenia);
      ?>
      <form action='zwrot.php' method='post'>
      <table width="600px" align="center" border="1">
           <tr>
                  <th width="250px">Tytuł</th>
                  <th width="250px">Data wypożyczenia</th>
                  <th width="100px">Zwróć</th>

           </tr>
      <?php
            //okodować cały zwrot i uporzadkować tabelę w indexie
            if ($ile>=1)
            {
                  //pętle wyświetlająca wszystkie zwrócone z zapytania wpisy
                  for ($i = 1; $i <= $ile; $i++)
                  {
                        //pobranie rzędu jako tablicę asocjacyjną
                        $row = mysqli_fetch_assoc($rezultatWypozyczenia);
                        //przypisanie zmiennych
                        $title = $row['title'];
                        $date = $row['borrowdate'];
                        echo
                              "<tr>
                                    <td> ".$title." </td>
                                    <td> ".$date." </td>
                                    <td>
                                          <input type='checkbox' name='idzwrotu' value='' />
                                    </td>
                              </tr>";
                  }
            }
            echo "<br /> <input type='submit' name='zapis' value='Zwracam'></form>";


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


      </form>
</body>
</html>
