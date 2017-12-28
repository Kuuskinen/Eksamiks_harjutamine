<?php
require("functions.php");

//Kui pole sisselogitud, suunab sisselogimisse 
if(!isset($_SESSION["userId"])){
	header("Location: login.php");
	exit();
}

//Väljalogimine
if(isset($_GET["logout"])){
	session_destroy();
	header("Location: login.php");
	exit();
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
<a href="cars.php">Autode lisamine</a>
<a href="updatepage.php">Sõidupäeviku täitmine</a>
<a href="logs.php">Sõidupäevikud</a>
<center>
<h1>Sõidupäevikute portaal</h1>
<h4>Sa oled sisselogitud kui: <?php echo $_SESSION["firstname"] ." " .$_SESSION["lastname"]; ?></h4>
<p>Sain variandi "24 Sõidupäevik"</p>
<p>See on eksamiks koostatud lehekülg, mis sisaldab tõsiseltvõetavat sisu.</p>
<p>NB! Autosid saab lisada lehel "Autode lisamine", sõidupäevikutesse saab kirjet lisada lehel "Sõidupäeviku täitmine" ja kasutaja näeb oma sisestusi lehel "Andmestik".</p> 
</center>

</body>
</html>