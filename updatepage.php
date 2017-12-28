<?php 
require("functions.php");
require("../../config.php");

if(isset($_GET["logout"])){
	session_destroy();
	header("Location: login.php");
	exit();
}

//MUUTUJAD

$updateDay = "";
$updateMonth = "";
$updateYear = "";

$row = "";
$regplate = "";
$Date = "";
$mileage = "";
$comment = ""; 

$car_numberError = "";
$updateDayError = "";
$mileageError = "";
$commentError = "";
$RegplateError = "";
$DateError = "";

if(isset($_POST["EnterButton"])){
//KAS KASUTAJA ON SISESTANUD REGNUMBRI
    if (isset($_POST["row"])){
		$regplate = $_POST["row"];
	} else {
		$RegplateError .= "Registreerimisnumber pole sisestatud!";
	}	
//KAS KASUTAJA ON SISESTANUD KUUPÄEVA

    if (isset($_POST["updateDate"])){
		$Date = $_POST["updateDate"];
	} else {
		$DateError .= "Kuupäev pole sisestatud!";
	}	
	
//KAS KASUTAJA ON SISESTANUD LÄBISÕIDU
	if (isset($_POST["mileage"])){
		$mileage = $_POST["mileage"];
	} else {
		$mileageError .= "Läbisõit pole sisestatud!";
	}

//KAS KASUTAJA ON SISESTANUD KOMMENTAARI
	if (isset($_POST["comment"])){
		$comment = $_POST["comment"];
	} else {
		$commentError .= "Kommentaar pole sisestatud!";
	}
	
	//KASUTAJA SISESTATUD KUUPÄEVA KONTROLL 
	if(isset($_POST["updateMonth"]) and isset($_POST["updateDay"]) and isset($_POST["updateYear"])){
		if(checkdate(intval($_POST["updateMonth"]), intval($_POST["updateDay"]), intval($_POST["updateYear"]))){
			//INTVAL KÜSIB INTEGER VÄÄRTUSE!
			//CHECKDATE VAATAB, KAS KUUPÄEV EKSISTEERIB
			$birthDate = date_create($_POST["updateMonth"] ."/" .$_POST["updateDay"] ."/" .$_POST["updateYear"]);
			$Date = date_format($birthDate, "Y-m-d");
			
			//DATE_CREATE loob uue kuupäeva andmestruktuuri.
			//DATE_FORMAT teeb sellest stringi.
			
		} else {
			$signupBirthDayError = "Ei ole korrektne kuupäev";
		}
	}
	
	//KUI KÕIK OK, KIRJUTAN BAASI
	if (empty($mileageError) and empty($commentError)){
			AddDiary($row, $Date, $mileage, $comment);
	}
}
	//KUUPÄEV
    $updateDaySelectHTML = ""; //alustuseks tühi muutuja
	$updateDaySelectHTML .= '<select name="updateDay">' ."\n"; //select - rippmenüü, "updateDay" - nimi mis läheb POST-i, \n on reavahetus
	$updateDaySelectHTML .= '<option value="" selected disabled>päev</option>' ."\n"; //option on 1 valik; selected ütleb, et alguses on valitud option kuni option lõppeb; disabled ütleb, et alguses see pole valitud
	for ($i = 1; $i < 32; $i ++){ //for tingimus ütleb, et ajutise muutuja i väärtus on 1-st kuni 31-ni
		if($i == $updateDay){ //tingimus tähendab, et kui see on valitud siis see jääb valituks
			$updateDaySelectHTML .= '<option value="' .$i .'">' .$i .'</option>' ."\n";
			//$updateDaySelectHTML .= '<option value="' .$i .'" selected>' .$i .'</option>' ."\n"; 
		} else {
			$updateDaySelectHTML .= '<option value="' .$i .'">' .$i .'</option>' ." \n";
		}
	}
	$updateDaySelectHTML.= "</select> \n";
	
	//KUU 
    $updateMonthSelectHTML = "";
	$monthNamesEt = ["jaanuar", "veebruar", "märts", "aprill", "mai", "juuni", "juuli", "august", "september", "oktoober", "november", "detsember"];
	$updateMonthSelectHTML .= '<select name="updateMonth">' ."\n";
	$updateMonthSelectHTML .= '<option value="" selected disabled>kuu</option>' ."\n";
	foreach ($monthNamesEt as $key=>$month){
		if ($key + 1 === $updateMonth){
			$updateMonthSelectHTML .= '<option value="' .($key + 1) .'">' .$month .'</option>' ."\n";
			//$updateMonthSelectHTML .= '<option value="' .($key + 1) .'" selected'>' .$month .'</option>' ."\n";
		} else {
		$updateMonthSelectHTML .= '<option value="' .($key + 1) .'">' .$month .'</option>' ."\n";
		}
	}
	$updateMonthSelectHTML .= "</select> \n";
	
	//AASTA
    $updateYearSelectHTML = "";
	$updateYearSelectHTML .= '<select name="updateYear">' ."\n";
	$updateYearSelectHTML .= '<option value="" selected disabled>aasta</option>' ."\n";
	$yearNow = date("Y");
	for ($i = $yearNow; $i > 1900; $i --){
		if($i == $updateYear){
			$updateYearSelectHTML .= '<option value="' .$i .'" selected>' .$i .'</option>' ."\n";
		} else {
			$updateYearSelectHTML .= '<option value="' .$i .'">' .$i .'</option>' ."\n";
		}	
	}
	$updateYearSelectHTML.= "</select> \n";
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
<a href="cars.php">Autode Lisamine</a>

<h1>Sõidupäevikute portaal</h1>
<h4>Sa oled sisselogitud kui: <?php echo $_SESSION["firstname"] ." " .$_SESSION["lastname"]; ?></h4>
<p>See on eksamiks koostatud lehekülg, mis sisaldab tõsiseltvõetavat sisu.</p>
<p>Siin leheküljel saab süsteemi lisada uue sõidupäevikukirje. Palun täida kõik lahtrid</p> 

<form method="POST" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>">

<!-- ETTEVAATUST! -->

<?php 
$conn = new mysqli('localhost', 'if17', 'if17', 'if17_lutsmeel') 
or die ('Cannot connect to db');

echo "<select name='select'>";
    $result = $conn->query("SELECT regplate FROM cars");
    while ($row = $result->fetch_assoc()) {
                  //unset($regplate);
				  $id = $row['id'];
                  $regplate = $row['regplate'];
                  echo '<option value="'.$id.'">'.$regplate.'</option>';
  				   
}
    echo "</select>";
	?>
	
<!-- ETTEVAATUST! -->

<br><br>
<label>Kuupäev</label>
<?php
			echo $updateDaySelectHTML .$updateMonthSelectHTML .$updateYearSelectHTML;
?>
<br><br>
<label>Läbisõit</label>
<input name="mileage" type="integer" value="">
<br><br>
<label>Kommentaar</label>
<input name="comment" type="text" value="">
<input name="EnterButton" type="submit" value="Lisa sõidupäevik">
</form>

</body>
</html>