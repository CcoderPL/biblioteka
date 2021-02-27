<?php
      //wszystko to co tutaj to obowiązek przy skrypcie logowania

      //rozpoczęcie działania SESSION - konieczne przy przekazywaniu danych między plikami
      session_start();

      //jeśli nie ma ustawionych poniższych zmiennych to znaczy że ktoś próbuje wpisać adres z ręki i nie możemy go wpuścić do tego pliku
      // i przekierowujemy go do index.php
      if ((!isset($_POST['login'])) || (!isset($_POST['password'])))
	{
		header('Location: index.php');
		exit();
	}
      //za pomocą require once dołączamy plik dzięki któremu połączymy się z bazą MySQL
      //można użyć require albo include, ale przy include gdy włączanego pliku nie uda się otworzyć to wygeneruje tylko ostrzeżenie
      //natomiast require wyrzuci błąd krytyczny i dalsze działanie skryptu zostanie wstrzymane
      require_once "connect.php";

      //otwarcie połączenia z bazą danych ! wszystkie dane w nawiasie mysqli to są dane pobrane z pliku connect.php bo ich potrzebujemy żeby dostać
      //się do bazy
      $polaczenie = @new mysqli($host, $db_user, $db_password, $db_name);

      //trzeba się zabezpieczyć w razie awarii gdyby nie udało się połączyć z bazą i ten if wyświetli kod błędu w takim wypadku
      if ($polaczenie->connect_errno!=0)
	{
		echo "Error: ".$polaczenie->connect_errno;
	}
	else
	{
            //odebranie zmiennych
            $login = $_POST['login'];
		$password = $_POST['password'];

            //użycie htmlentities która ma chronić przed wstrzykiwaniem sql, funkcja ta przepuszcza przez siebie podany przez użytkownika tekst
            //i to co może być jako skrypt to przerabia na encje i czym blokuje wysłanie przez użytkownika skrypt/ funkcja ta wstawi encje wszędzie
            //tam gdzie to możliwe
            $login = htmlentities($login, ENT_QUOTES, "UTF-8");
            $password = htmlentities($password, ENT_QUOTES, "UTF-8");

            //wysłanie zapytania do bazy
            //za pomocą funkcji sprintf, sprawdza ona poprawność danych , która w każdym miejscu gdzie mamy %s wie że będzie znajdować się tam
            //string czyli ciąg znaków , dokładnie w miejscu gdzie jest pioerwszy %s wstawi pierwszy argument po przecinku czyli
            // mysqli_real_escape_string($polaczenie,$login), natomiast w miejsce drugiego %s wstawi mysqli_real_escape_string($polaczenie,$password))))
            // składnia tej funkcji sprawia, że są one czytelne dla oka
            //natomiast mysqli wykrywa próby wpływania na zapytania SQL za pomocą apostrofów itp. KONIECZNA przy systemach logowania
            if ($rezultat = @$polaczenie->query(
		sprintf("SELECT * FROM uzytkownicy WHERE user='%s' AND password='%s'",
		mysqli_real_escape_string($polaczenie,$login),
		mysqli_real_escape_string($polaczenie,$password))))
		{
                  $ilu_userow = $rezultat->num_rows;

                  //jezeli ilosc userow jest wieksza od 0 tzn że jest taki użytkownik i udało się zalogować
                  if($ilu_userow > 0)
                  {
                        //ustawienie zmiennej aby po zalogowaniu i odświeżeniu strony przeglądarka mogła to zapamiętać, żeby
                        //nie trzeba było logować się od nowa
                        $_SESSION['zalogowany'] = true;

                        $wiersz = $rezultat->fetch_assoc();

                        //niejawne przesłanie zmiennej globalnie do innego pliku php można skorzystać z SESSION
                        //$_SESSION['id'] = $wiersz['id'];
                        $_SESSION['user'] = $wiersz['user'];
				$_SESSION['userid'] = $wiersz['userid'];

                        //jeśli udało nam się zalogować to usuńmy całkowicie zmienną błąd z sesji
                        unset($_SESSION['blad']);
                        //czyszczenie pobranych danych z wiersza !! czyszczenie pamięci
                        $rezultat->close();
                        header('Location:index.php');
                  }

                  //natomiast jeżeli nie ma takiego usera to przejdziemy do else
                  else
                  {
                        //wyświetlamy komunikat w kolorze czerwonym o błędnym haśle lub loginie
				$_SESSION['blad'] = '<span style="color:red">Nieprawidłowy login lub hasło!</span>';

                        // i przekierowujemy go spowrotem do index.php
                        header('Location:wypozyczalnia.php');
			}
            }

            //zamknięcie połączenia z bazą jest obowiązkowe !!
            $polaczenie->close();
      }

 ?>
