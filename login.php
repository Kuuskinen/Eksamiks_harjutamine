<?php
	require("../../config.php");
	require("functions.php");

	//OLULINE
	//Kui kasutaja (ehk mina) on juba sisse loginud, siis suunab pealehele
	
	if((isset($_SESSION["userId"])) and ($_SESSION["userId"]) == "1"){
		header("Location: avaleht.php");
		exit();
	}
	
	//OLULINE

	//KASUTAJA TEGEMISE MUUTUJAD
	$signupFirstName = "";
	$signupLastName = "";
	$gender = "";
	$signupEmail = "";
	$signupBirthDay = null;
	$signupBirthMonth = null;
	$signupBirthYear = null;
	$signupBirthDate = "";

	//LOGIMISE MUUTUJAD
	$loginEmail = "";
	$notice = "";

	//VIGADE MUUTUJAD
	$signupFirstNameError = "";
	$signupLastNameError = "";
	$signupBirthDayError = "";
	$signupGenderError = "";
	$signupEmailError = "";
	$signupPasswordError = "";
	$loginEmailError ="";
	$loginPasswordError = "";


//KONTROLLIB, KAS SISSELOGIMISE NUPPU ON VAJUTATUD JA KÄIVITAB VASTAVAD PROTSEDUURID 

if(isset($_POST["signInButton"])){
	
	//Kas sisestatud on kasutaja email
	if(isset($_POST["loginEmail"])){
		if(empty(["loginEmail"])){
			$loginEmailError = "Sisselogimiseks on tarvis sisestada E-mail";
		} else {
			$loginEmail = ($_POST["loginEmail"]);
		}
	}
	
	if(!empty($loginEmail) and !empty($_POST["loginPassword"])){
		$notice = signIn($loginEmail, $_POST["loginPassword"]);
		echo "LOGIMINE...";
	}
}
	// SISSELOGIMISE LÕPP
	
//UUE KASUTAJA TEGEMINE, KUI ON VAJUTATUD VASTAVAT NUPPU
if(isset($_POST["SignUpButton"])){
	//echo "SALVESTAMINE";
	//KAS KASUTAJA ON SISESTANUD OMA EESNIME
	if(isset($_POST["signupFirstName"])){
		if(empty(["signupFirstName"])){
			$signupFirstNameError = "Palun sisesta oma nimi!";
		} else {
			$signupFirstName = Kontroll($_POST["signupFirstName"]);
		}
	}
	
	//KAS KASUTAJA ON SISESTANUD OMA PEREKONNANIME
	if(isset($_POST["signupLastName"])){
		if(empty(["signupLastName"])){
			$signupLastNameError = "Palun sisesta oma perekonnanimi!";
		} else {
			$signupLastName = Kontroll($_POST["signupLastName"]);
		}
	}
		
	//KAS KASUTAJA ON SISESTANUD OMA SOO
	if(isset($_POST["gender"]) && !empty($_POST["gender"])){
		if(empty(["gender"])){
			$signupGenderError = "Sa pole sootu!";
		} else {
			$gender = Kontroll($_POST["gender"]);
		}
	}
	
	//KAS KASUTAJA ON SISESTANUD OMA SÜNNIKUUPÄEVA
	if (isset($_POST["signupBirthDay"])){
		$signupBirthDay = $_POST["signupBirthDay"];
	} else {
		$signupBirthDayError = "Kuupäeva pole sisestatud!";
	}
	
	//KAS KASUTAJA ON SISESTANUD OMA SÜNNIKUU
	if ( isset($_POST["signupBirthMonth"]) ){
		$signupBirthMonth = intval($_POST["signupBirthMonth"]);
	} else {
		$signupBirthDayError .= " Kuu pole sisestatud!";
	}

	
	//KAS KASUTAJA ON SISESTANUD OMA SÜNNIAASTA
	if (isset($_POST["signupBirthYear"])){
		$signupBirthYear = $_POST["signupBirthYear"];
		//echo $signupBirthYear;
	} else {
		$signupBirthDayError .= "Aasta pole sisestatud!";
	}
	
	//KASUTAJA SISESTATUD KUUPÄEVA KONTROLL 
	if(isset($_POST["signupBirthMonth"]) and isset($_POST["signupBirthDay"]) and isset($_POST["signupBirthYear"])){
		if(checkdate(intval($_POST["signupBirthMonth"]), intval($_POST["signupBirthDay"]), intval($_POST["signupBirthYear"]))){
			//INTVAL KÜSIB INTEGER VÄÄRTUSE!
			//CHECKDATE VAATAB, KAS KUUPÄEV EKSISTEERIB
			$birthDate = date_create($_POST["signupBirthMonth"] ."/" .$_POST["signupBirthDay"] ."/" .$_POST["signupBirthYear"]);
			$signupBirthDate = date_format($birthDate, "Y-m-d");
			
			//DATE_CREATE loob uue kuupäeva andmestruktuuri.
			//DATE_FORMAT teeb sellest stringi.
			
			//echo $birthDate;
		} else {
			$signupBirthDayError = "Ei ole korrektne kuupäev";
		}
	}
	
	//KASUTAJA SISESTATUD EMAIL-I KONTROLL
	if(isset ($_POST["signupEmail"])){
		if(empty($_POST["signupEmail"])){
			$signupEmailError = "Email on sisestamata!";
		} else { 
		    //SIIN TULEB TRIKIKAS KOHT. EMAIL LÄBIB NII TAVALISE KONTROLLI, KUI KA ERIKONTROLLI!
			$signupEmail = Kontroll($_POST["signupEmail"]);
			
			$signupEmail = filter_var($signupEmail, FILTER_SANITIZE_EMAIL);
			$signupEmail = filter_var($signupEmail, FILTER_VALIDATE_EMAIL);
		}
	}
	
	//KASUTAJA SISESTATUD PAROOLI KONTROLL 
	if(isset($_POST["signupPassword"])){
		if(empty($_POST["signupPassword"])){
			$signupPasswordError = "Aga kes parooli sisestab?";
		} else {
			if(strlen($_POST["signupPassword"]) < 8){
				$signupPasswordError = "Sisestatud parool ei sobi! See peab olema kaheksa tähemärki pikk!";
			}
		}
	}


     //KUI EELNEVAD KONTROLLID ON TEHTUD, SIIS KIRJUTAME REGISTREERUVA KASUTAJA ANDMEBAASI
	if (empty($signupFirstNameError) and empty($signupLastNameError) and empty($signupBirthDayError) and empty($genderError) and empty($signupEmailError) and empty($signupPasswordError)){
			echo "SALVESTAMINE...";
			//PAROOLI KRÜPTEERIMINE
			$signupPassword = hash("sha512", $_POST["signupPassword"]);
			//echo "\n Parooli " .$_POST["signupPassword"] ." räsi on: " .$signupPassword;
			signUp($signupFirstName, $signupLastName, $signupBirthDate, $gender, $signupEmail, $signupPassword);
	}
}

	//KOPEERITUD
	//Tekitame kuupäeva valiku
    $signupDaySelectHTML = "";
	$signupDaySelectHTML .= '<select name="signupBirthDay">' ."\n";
	$signupDaySelectHTML .= '<option value="" selected disabled>päev</option>' ."\n";
	for ($i = 1; $i < 32; $i ++){
		if($i == $signupBirthDay){
			$signupDaySelectHTML .= '<option value="' .$i .'" selected>' .$i .'</option>' ."\n";
		} else {
			$signupDaySelectHTML .= '<option value="' .$i .'">' .$i .'</option>' ." \n";
		}
	}
	$signupDaySelectHTML.= "</select> \n";
	
	//Tekitame sünnikuu valiku
    $signupMonthSelectHTML = "";
	$monthNamesEt = ["jaanuar", "veebruar", "märts", "aprill", "mai", "juuni", "juuli", "august", "september", "oktoober", "november", "detsember"];
	$signupMonthSelectHTML .= '<select name="signupBirthMonth">' ."\n";
	$signupMonthSelectHTML .= '<option value="" selected disabled>kuu</option>' ."\n";
	foreach ($monthNamesEt as $key=>$month){
		if ($key + 1 === $signupBirthMonth){
			$signupMonthSelectHTML .= '<option value="' .($key + 1) .'" selected>' .$month .'</option>' ."\n";
		} else {
		$signupMonthSelectHTML .= '<option value="' .($key + 1) .'">' .$month .'</option>' ."\n";
		}
	}
	$signupMonthSelectHTML .= "</select> \n";
	
	//Tekitame aasta valiku
    $signupYearSelectHTML = "";
	$signupYearSelectHTML .= '<select name="signupBirthYear">' ."\n";
	$signupYearSelectHTML .= '<option value="" selected disabled>aasta</option>' ."\n";
	$yearNow = date("Y");
	for ($i = $yearNow; $i > 1900; $i --){
		if($i == $signupBirthYear){
			$signupYearSelectHTML .= '<option value="' .$i .'" selected>' .$i .'</option>' ."\n";
		} else {
			$signupYearSelectHTML .= '<option value="' .$i .'">' .$i .'</option>' ."\n";
		}	
	}
	$signupYearSelectHTML.= "</select> \n";
//<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); 
?>

<!DOCTYPE html>
<html>
<head>
<title>CLASSIFIED</title>
</head>
<body>
	<h1>Salaveeb</h1>
	<h2>Logimine</h2>
	<p>SISSELOGIMINE</p>
	
	<!--htmlspecialchars trükib välja normaalse tekstina ja tagid ei aktiveeru. -->
	
	<form method="POST" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>">
	<!--htmlspecialchars teeb erimärgid HTML tekstiks. 
	echo'b aadressiriba lõppu
	kirjutab lihtsalt aadressiribale leheaadressi ja sellega asi piirdubki!-->
		<label>Kasutajanimi (E-post): </label>
		<input name="loginEmail" type="email" value="<?php echo $loginEmail; ?>"><span><?php echo $loginEmailError; ?></span>
		<br><br>
		<input name="loginPassword" placeholder="Salasõna" type="password"><span><?php echo $loginPasswordError; ?></span>
		<br><br>
		<input name="signInButton" type="submit" value="Logi sisse"><span><?php echo $notice; ?></span>
	</form>
	
	<h2>Loo kasutaja</h2>
	<p>ÄRA TEE KASUTAJAT!</p>
	
	<form method="POST" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>">
		<label>Eesnimi </label>
		<input name="signupFirstName" type="text" value="">
		<span><?php echo $signupFirstNameError; ?></span>
		<br>
		<label>Perekonnanimi </label>
		<input name="signupLastName" type="text" value="">
		<span><?php echo $signupLastNameError; ?></span>
		<br>
		<label>Sisesta oma sünnikuupäev</label>
		<?php
			echo $signupDaySelectHTML .$signupMonthSelectHTML .$signupYearSelectHTML;
		?>
		<span><?php echo $signupBirthDayError; ?></span>
		
		<br><br>
		<label>Sugu</label>
		<br>
		<input type="radio" name="gender" value="1" <?php if ($gender == "1") {echo 'checked';} ?>><label>Mees</label> <!-- Kõik läbi POST'i on string!!! -->
		<input type="radio" name="gender" value="2" <?php if ($gender == "2") {echo 'checked';} ?>><label>Naine</label><span><?php echo $signupGenderError; ?></span>
		<!-- CHECKED ANNAB TEADA, ET SEE ON NÜÜD VALITUD -->
		<br><br>
		
		<label>Kasutajanimi (E-post)</label>
		<input name="signupEmail" type="email" value="">
		<span><?php echo $signupEmailError; ?></span>
		<br><br>
		<input name="signupPassword" placeholder="Salasõna" type="password">
		<span><?php echo $signupPasswordError; ?></span>
		<br><br>

		
		<input name="SignUpButton" type="submit" value="Loo kasutaja">
	</form>
		
</body>
</html>
	