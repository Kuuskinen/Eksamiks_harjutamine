<?php
require("functions.php");

//Kui pole sisselogitud, suunab sisselogimisse 
if((!isset($_SESSION["userId"])) or ($_SESSION["userId"]) != "1"){
	header("Location: login.php");
	exit();
}

$kasutaja_valik = "";     

?>

<!DOCTYPE html>
<html>
<head>
<title>CLASSIFIED</title>
</head>  

<body>
<h1>Siin saad mängida arvuti vastu kivi-paber-kääre.</h1>

<form method="POST" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>">
<input type="radio" name="kasutaja_valik" value="1" <?php if ($kasutaja_valik == "1") {echo 'checked';} ?>><label>Kivi</label>
<input type="radio" name="kasutaja_valik" value="2" <?php if ($kasutaja_valik == "2") {echo 'checked';} ?>><label>Paber</label>
<input type="radio" name="kasutaja_valik" value="3" <?php if ($kasutaja_valik == "3") {echo 'checked';} ?>><label>Käärid</label>
<br><br>

</form>
</body>
</html> 