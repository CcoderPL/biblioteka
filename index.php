<?php
      //POZAMYKAĆ WSZYSTKIE POŁĄCZENIA Z BAZĄ DANYCH
      //POSPRAWDZAĆ WSZYSTKIE PRZEKIEROWANIA Z INDEXU I LOGOWANIA BO PRZY PRÓBIE LOGOWANIA I PODANIU BŁĘDNYCH DANYCH PRZERZUCA DO INDEXU
      //ZROBIĆ Z TYM PORZADEK
      //dzięki zmiennej sesyjnej jest możliwość przekazywania zmiennych np. loginu i hasła, używamy wtedy $_SESSION['nazwa_zmiennej']
      session_start();
      //jeśli istnieje zmienna zalogowany to wtedy już ktoś się zalogował i nie wyświetlamy mu wtedy ekranu logowania tylko przyznajemy
      //mu dostęp do strony ale aktualnie tego nie aktywujemy bo przekierowania są dobre.
      /*
      if(isset($_SESSION['zalogowany']) && ($_SESSION['zalogowany']==true))
          {
                header('Location: wypozyczalnia.php');
            exit();
      }
      */
      //wymaganie pliku konfiguracyjnego do połączenia z bazą MySQL
      require_once 'connect.php';
      //utworzenie zmiennej do weryfikacji połączenia z bazą
      $polaczenie = @new mysqli($host, $db_user, $db_password, $db_name);
      //przepuszczenie jej przez funkcję zabezpieczającą wstrzykiwanie SQL
      mysqli_query($polaczenie, "SET CHARSET utf8");
      mysqli_query($polaczenie, "SET NAMES 'utf8' COLLATE 'utf8_polish_ci'");
      //zapytanie można wysłać za pomocą zmiennej jak w tym przypadku $zapytanie albo bezpośredno wpisać po przecinku
      $zapytanieKsiazki = "SELECT ksiazki.bookid, ksiazki.title, ksiazki.author, ksiazki.description, ksiazki.available FROM ksiazki";
      //wysłanie zapytania do bazy, konieczne jest do tego korzystanie z zmiennej weryfikującej połączenie z bazą
      $rezultatKsiazki = mysqli_query($polaczenie, $zapytanieKsiazki);
      //zmienna potrzebna do wykonania pętli, sprawdza ile rzędów zwróciło zapytanie
      $ile = mysqli_num_rows($rezultatKsiazki);

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
                        //wyświetlanie opcji zależnie od stanu zalogowania - jeśli niezalogowany to wyświetl możliwość logowania, w przeciwnym razie możliwość
                        //wylogowania
                              if(isset($_SESSION['zalogowany']) && ($_SESSION['zalogowany']==true))
                              {
                                    echo "<a href='wypozyczalnia.php'>Moje Konto</a> &nbsp;&nbsp; <a href='logout.php'>Wyloguj się!</a>";
                              }
                              else
                              {
                                    echo "<a href='logowanie.php'>Zaloguj się</a>";
                              }
                              //sprawdzanie czy taka zmienna jest ustawiona w sesji, jest to dla nowo wchodzących na stronę
                              if(isset($_SESSION['blad']))	echo $_SESSION['blad'];
                        ?>
                  </div>
                  <div id='content'>
                      <form action='wypozyczenie.php' method='post'>
                            <table width="1000px" align="center" border="1">
                                  <tr>
                                        <th width="250px">Tytuł</th>
                                        <th width="250px">Autor</th>
                                        <th width="300px">Opis</th>
                                        <th width="100px">Dostępność</th>
                                        <th width="100px">Wypożycz</th>
                                  </tr>
                                  <?php
                                        //tworzenie klasy na wpis, co ułatwi przekazanie dalej oraz ewentualne wyświetlenie
                                          class ksiazka
                                          {
                                                public $title;
                                                public $author;
                                                public $description;
                                                public $available;
                                                public $bookid;
                                          };
                                          //wyświetlanie wyników gdy zmienna ile zwróciła przynajmniej 1 rząd
                                          if ($ile>=1)
                                          {
                                                //utworzenie obiektu klasy
                                                $ksiazka = new ksiazka();
                                                //pętle wyświetlająca wszystkie zwrócone z zapytania wpisy
                                          	for ($i = 1; $i <= $ile; $i++)
                                          	{
                                                      //pobranie rzędu jako tablicę asocjacyjną
                                                      $row = mysqli_fetch_assoc($rezultatKsiazki);
                                                      //przypisanie zmiennych
                                                      $ksiazka->title = $row['title'];
                                                      $ksiazka->author = $row['author'];
                                                      $ksiazka->description = $row['description'];
                                                      $ksiazka->available = $row['available'];
                                                      $ksiazka->bookid = $row['bookid'];
                                                      //wyświetlenie pojedyńczego wiersza w tabeli w formie jaka jest poniżej
                                                      echo
                                                            "<tr>
                                                                  <td> ".$ksiazka->title." </td>
                                                                  <td> ".$ksiazka->author." </td>
                                                                  <td> ".$ksiazka->description." </td>
                                                                  <td>";
                                                                        if($ksiazka->available==1)
                                                                        {
                                                                              echo "TAK";
                                                                        }
                                                                        else
                                                                        {
                                                                              echo "NIE";
                                                                        }
                                                                  echo "</td>" ;
                                                                  //jeżeli użytkownik zalogowany i książka jest dostępna to wyświetl pole wyboru z wypożyczeniem
                                                                  //jeżeli nie jest zalogowany to wyświetl * do której przypisana jest informacja o tym, że musi się zalogować
                                                                  //aby móc wypożyczyć książkę
                                                                  echo "<td>";
                                                                  if(isset($_SESSION['zalogowany']) && ($_SESSION['zalogowany']==true) && $ksiazka->available==1)
                                                                  {
                                                                        echo "<input type='checkbox' name='idksiazki[]' value='$ksiazka->bookid' />";
                                                                  }
                                                                  elseif(!isset($_SESSION['zalogowany']))
                                                                  {
                                                                        echo " * ";
                                                                  }
                                                            echo "</td></tr>";
                                                }
                                          }
                                    ?>
                              </table>
                              <?php
                              //jeżeli jest ktoś zalogowany to wyświetl guzik - wypożyczam , jeżeli nie to wtedy wyświetl * z informacją o tym że musi się zalogować
                              if(isset($_SESSION['zalogowany']) && ($_SESSION['zalogowany']==true))
                              {
                                    echo "<br />";
                                    echo "<input type='submit' name='zapis' value='wypożyczam' style='margin-left:75%'></form>";
                              }
                              else
                              {
                                    echo "* Musisz być zalogowany aby móc wypożyczyć <br />";
                              }
                              ?>
                        </form>
                  </div>
                  <div id='footer'>
                        <a href='http://pawelmolek.pl'>Paweł Mołek</a>
                  </div>
            </div>
      </body>
</html>
