<?php

	session_start();
	// niszczenie sesji, wylogowanie
	session_unset();
	//przekierowanie do pliku index.php
	header('Location: index.php');

?>
