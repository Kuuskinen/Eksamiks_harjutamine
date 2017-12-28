<?php
//OLULINE ON AUTO NUMBER!
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

//MUUTUJAD
$car_number = "";
$car_numberError = "";

if(isset($_POST["AddButton"])){
	echo"1";
	if (isset($_POST["car_number"])){
		    $car_number = $_POST["car_number"];
			$car_number = CarAdding($car_number);
			echo"2";
		} else {
			$car_numberError .= "Autonumbrit pole sisestatud!";
		}
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
<a href="updatepage.php">Sõidupäeviku täitmine</a>
<h1>Autode lehekülg</h1>
<p>Siin leheküljel saab süsteemi lisada uue auto. Auto lisamiseks sisesta ILMA TÜHIKUTETA AUTO NUMBRIMÄRK ja vajuta "Lisa auto"</p> 

<form method="POST" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>">
<input name="car_number" type="text" value=""><span><?php echo $car_numberError; ?></span>
<input name="AddButton" type="submit" value="Lisa auto">
</form>

</body>
</html>