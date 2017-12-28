<?php
require("functions.php");

//Kui pole sisselogitud, saadab login lehele
if(!isset($_SESSION["userId"])){
	header("Location: login.php");
	exit();
}

//Väljalogimine
if(isset($_GET["logout"])){
	session_destroy(); //lõpetab sessioni
	header("Location: login.php");
}

?>
<!DOCTYPE html>
<html>
<head>
<meta charset="utf-8" />
<title>Sõidupäevikud</title>
</head>
<body>
<a href="?logout=1">Logi välja</a> <?php //<---- a on link! ?>
<a href="main.php">Pealeht</a>
<a href="cars.php">Autode lisamine</a>
<a href="updatepage.php">Sõidupäeviku täitmine</a>
<p>Ma ei jõudnud seda kirjutada<p>

</body>
</html>