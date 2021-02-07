<?php
      session_start();
      if(isset($_SESSION['zalogowany']) && ($_SESSION['zalogowany']==true))
      {
            header('Location: wypozyczalnia.php');
            exit();
      }
      require_once 'connect.php';
      $polaczenie = @new mysqli($host, $db_user, $db_password, $db_name);
      mysqli_query($polaczenie, "SET CHARSET utf8");
      mysqli_query($polaczenie, "SET NAMES 'utf8' COLLATE 'utf8_polish_ci'");

      $zapytanie = "SELECT title, author, description, available FROM ksiazki";

      $rezultat = mysqli_query($polaczenie, $zapytanie);
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
    <form action="zaloguj.php" method="post">
      Login: <br /> <input type="text" name="login"/><br/>
      Hasło: <br /> <input type="password" name="password"/><br/><br />
                    <input type="submit" value="Zaloguj"/>
    </form>
    <br/><br />
    <table width="70%" align="center" border="1">
          <tr>
                <th width="30%">Tytuł</th>
                <th width="30%">Autor</th>
                <th width="35%">Opis</th>
                <th width="5%">Wypożycz</th>
          </tr>
    <?php
      if(isset($_SESSION['blad']))	echo $_SESSION['blad'];
      if ($ile>=1)
      {
      	for ($i = 1; $i <= $ile; $i++)
      	{

      		$row = mysqli_fetch_assoc($rezultat);
      		$title = $row['title'];
      		$author = $row['author'];
      		$description = $row['description'];
      		$available = $row['available'];

                  echo<<<END
                  <tr>
                        <th> $title</th>
                        <th> $author</th>
                        <th> $description</th>
                        <th>
                              <form action="wypozyczalnia.php" method="post">
                              	<select name="borrow">
                              		<option>NIE</option>
                              		<option>TAK</option>
                              	</select>
                              </form>
                        </th>
                  </tr>
                  END;
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
