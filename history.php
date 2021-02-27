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
            	echo "<p style='margin-left:60%'>Witaj ".$_SESSION['user'].'! <a href="logout.php">Wyloguj się!</a> &nbsp;&nbsp; <a href="index.php">Strona Główna</a> &nbsp;&nbsp;<a href="wypozyczalnia.php">Moje konto</a></p>';

                  $zapytanieHistorii = "SELECT zwroty.returndate, zwroty.borrowdate, zwroty.userid, zwroty.bookid, ksiazki.bookid, ksiazki.title, uzytkownicy.user, uzytkownicy.userid
                  FROM zwroty, ksiazki, uzytkownicy WHERE uzytkownicy.userid = zwroty.userid AND
                  ksiazki.bookid = zwroty.bookid AND zwroty.userid = {$_SESSION['userid']}";

                  //wysłanie zapytania do bazy, konieczne jest do tego korzystanie z zmiennej weryfikującej połączenie z bazą
                  $rezultatHistorii = mysqli_query($polaczenie, $zapytanieHistorii);

                  //zmienna potrzebna do wykonania pętli, sprawdza ile rzędów zwróciło zapytanie
                  $ile = mysqli_num_rows($rezultatHistorii);
                        // uporzadkować tabelę w indexie !!!!!!!!!!!!!!!!!
                        if ($ile>=1)
                        {
                              echo "<table width='700px' align='center' border='1'>
                                   <tr>
                                          <th width='250px'>Tytuł</th>
                                          <th width='250px'>Data wypożyczenia</th>
                                          <th width='200px'>Data Zwrotu</th>
                                   </tr>";
                              //pętle wyświetlająca wszystkie zwrócone z zapytania wpisy
                              for ($i = 1; $i <= $ile; $i++)
                              {
                                    //pobranie rzędu jako tablicę asocjacyjną
                                    $row = mysqli_fetch_assoc($rezultatHistorii);
                                    //przypisanie zmiennych
                                    $title = $row['title'];
                                    $borrowDate = $row['borrowdate'];
                                    $returnDate = $row['returndate'];
                                    echo
                                          "<tr>
                                                <td> ".$title." </td>
                                                <td> ".$borrowDate." </td>
                                                <td> ".$returnDate." </td>
                                          </tr>";
                              }
                              echo "</table>";
                        }
                        else
                        {
                              echo "Nie wypożyczałeś żadnej książki";
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
                  ?>
      </body>
</html>
