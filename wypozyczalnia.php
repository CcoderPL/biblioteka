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
            <link rel="stylesheet" href="css/style.css" type="text/css" />
      </head>
      <body>
            <div id='container'>
                  <div id='options'>
                        <?php
                              //opcja wylogowania
                        	echo "<a href='history.php'>Historia Wypożyczeń</a> &nbsp; <a href='index.php'>Strona Główna</a> &nbsp;<a href='logout.php'>Wyloguj się!</a>";

                              $zapytanieWypozyczenia = "SELECT wypozyczenia.borrowid, wypozyczenia.borrowdate, wypozyczenia.userid, wypozyczenia.bookid, ksiazki.bookid, ksiazki.title, uzytkownicy.user, uzytkownicy.userid
                              FROM wypozyczenia, ksiazki, uzytkownicy WHERE uzytkownicy.userid = wypozyczenia.userid AND
                              ksiazki.bookid = wypozyczenia.bookid AND wypozyczenia.userid = {$_SESSION['userid']}";

                              //wysłanie zapytania do bazy, konieczne jest do tego korzystanie z zmiennej weryfikującej połączenie z bazą
                              $rezultatWypozyczenia = mysqli_query($polaczenie, $zapytanieWypozyczenia);

                              //zmienna potrzebna do wykonania pętli, sprawdza ile rzędów zwróciło zapytanie
                              $ile = mysqli_num_rows($rezultatWypozyczenia);
                        ?>
                  </div>
                  <div id='content'>
                        <form action='zwrot.php' method='post'>
                              <?php
                                    // uporzadkować tabelę w indexie !!!!!!!!!!!!!!!!!
                                    if ($ile>=1)
                                    {
                                          echo "<table width='700px' align='center' border='1'>
                                               <tr>
                                                      <th width='250px'>Tytuł</th>
                                                      <th width='250px'>Data wypożyczenia</th>
                                                      <th width='200px'>Zwróć</th>
                                               </tr>";
                                          //pętle wyświetlająca wszystkie zwrócone z zapytania wpisy
                                          for ($i = 1; $i <= $ile; $i++)
                                          {
                                                //pobranie rzędu jako tablicę asocjacyjną
                                                $row = mysqli_fetch_assoc($rezultatWypozyczenia);
                                                //przypisanie zmiennych
                                                $title = $row['title'];
                                                $date = $row['borrowdate'];
                                                $bookid = $row['bookid'];
                                                echo
                                                      "<tr>
                                                            <td> ".$title." </td>
                                                            <td> ".$date." </td>
                                                            <td>
                                                                  <input type='checkbox' name='idzwroconejksiazki[]' value='$bookid'/>
                                                            </td>
                                                      </tr>";
                                          }
                                          echo "</table>";
                                          echo "<br/>";
                                          echo "<input type='submit' id='ZwrotWypozyczenieButton' name='zapis' value='Zwracam'/>";
                                    }
                                    else
                                    {
                                          echo "Aktualnie nie masz wypożyczonej żadnej książki";
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
                        </form>
                  </div>
                  <div id='footer'>
                        <a href='http://pawelmolek.pl' target='_blank'>Paweł Mołek</a>
                  </div>
            </div>
      </body>
</html>
