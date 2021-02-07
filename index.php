<?php
      session_start();
      //jeśli istnieje zmienna zalogowany to wtedy już ktoś się zalogował i nie wyświetlamy mu wtedy ekranu logowania tylko przyznajemy
      //mu dostęp do strony
      if(isset($_SESSION['zalogowany']) && ($_SESSION['zalogowany']==true))
      {
            header('Location: wypozyczalnia.php');
            exit();
      }
      //wymaganie pliku konfiguracyjnego do połączenia z bazą MySQL
      require_once 'connect.php';
      //utworzenie zmiennej do weryfikacji połączenia z bazą
      $polaczenie = @new mysqli($host, $db_user, $db_password, $db_name);
      //przepuszczenie jej przez funkcję zabezpieczającą wstrzykiwanie SQL
      mysqli_query($polaczenie, "SET CHARSET utf8");
      mysqli_query($polaczenie, "SET NAMES 'utf8' COLLATE 'utf8_polish_ci'");
      //zapytanie można wysłać za pomocą zmiennej jak w tym przypadku $zapytanie albo bezpośredno wpisać po przecinku
      $zapytanie = "SELECT title, author, description, available FROM ksiazki";
      //wysłanie zapytania do bazy, konieczne jest do tego korzystanie z zmiennej weryfikującej połączenie z bazą
      $rezultat = mysqli_query($polaczenie, $zapytanie);
      //zmienna potrzebna do wykonania pętli, sprawdza ile rzędów zwróciło zapytanie
      $ile = mysqli_num_rows($rezultat);

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
    <div id="logowanie" style="margin-left:90%;"><a href="logowanie.php">Zaloguj się</a></div>
    <br/><br />
    <table width="70%" align="center" border="1">
          <tr>
                <th width="30%">Tytuł</th>
                <th width="30%">Autor</th>
                <th width="35%">Opis</th>
                <th width="5%">Dostępność</th>
          </tr>
    <?php
      //sprawdzanie czy taka zmienna jest ustawiona w sesji, jest to dla nowo wchodzących na stronę
      if(isset($_SESSION['blad']))	echo $_SESSION['blad'];
      //wyświetlanie wyników gdy zmienna ile zwróciła przynajmniej 1 rząd
      if ($ile>=1)
      {
            //pętle wyświetlająca wszystkie zwrócone z zapytania wpisy
      	for ($i = 1; $i <= $ile; $i++)
      	{
                  //pobranie rzędu jako tablicę asocjacyjną
      		$row = mysqli_fetch_assoc($rezultat);
                  //przypisanie każdej kolumny do odpowiedniej zmiennej
      		$title = $row['title'];
      		$author = $row['author'];
      		$description = $row['description'];
      		$available = $row['available'];

                  echo
                  "<tr>
                        <th> ".$title." </th>
                        <th> ".$author." </th>
                        <th> ".$description." </th>
                        <th>";
                              if($available==1)
                              {
                                    echo "TAK";
                              }
                              else
                              {
                                    echo "NIE";
                              }
                        "</th>
                  </tr>";


            }
      }
      //wyswietlenie 1 rekordu -- dramat
      /*$result = mysqli_query($polaczenie, "SELECT title FROM ksiazki WHERE bookid=1") or die("Problemy z odczytem danych!");
      while ($row = $result->fetch_assoc())
      {
            echo $row['title']."<br>";
      }*/


      ?>
      </table>
</body>
</html>
