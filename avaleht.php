<?php
require("functions.php");

//Kui pole sisselogitud, suunab sisselogimisse 
if((!isset($_SESSION["userId"])) or ($_SESSION["userId"]) != "1"){
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
<title>Harjutamine</title>
</head>
<body>
<a href="?logout=1">Logi välja</a> <?php //<---- a on link! ?>
<a href="game.php">Kivi-Paber-Käärid</a>
<a href="test.html">test</a>
<h1>Pealeht</h1>
<p>See on harjutamiseks tehtud lehekülg<p>
<p>Autoriseerimata ja ebakorrektse ID-ga kasutajad kõrvaldatakse.<p>

</body>
</html>