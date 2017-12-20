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
<a href="game.php">Kivi-Paber-Käärid</a>
<a href="test.html">test</a>
<a href="weekdays.php">Nädalapäevad</a>
<a href="?logout=1">Logi välja</a> <?php //<---- a on link! ?>
<h1>Pealeht</h1>
<p>See on harjutamiseks tehtud lehekülg<p>
<p>Autoriseerimata ja ebakorrektse ID-ga kasutajad kõrvaldatakse.<p>

<center>
<h1>Vajuta <a href="https://www.nasa.gov/">SIIA</a> et minna NASA kodulehele.</h1>
<h4>Sa oled sisselogitud kui: <?php echo $_SESSION["firstname"] ." " .$_SESSION["lastname"]; ?></h4> 
</center>

</body>
</html>