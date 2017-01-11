<?php
	
require("../config.php");
require("functions.php");
require("helper.class.php");
require("user.class.php");

	$Helper = new Helper();
	$user_class = new user($mysqli);
//kas kasutaja on sisse logitud
if(isset($_SESSION["userID"])){
	header("Location: data.php");
	exit();
}
	//var_dump(5.5)
	//var_dump($_GET);
	//echo "<br>";
	//var_dump($_POST);
	
	// MUUTUJAD
	$signupEmailError = "";
	$signupPasswordError = "";
	$signupGenderError = "";
	$signupEmail = "";
	$signupGender = "";
	
	// kas e/post oli olemas
	if ( isset ( $_POST["signupEmail"] ) ) {
		
		if ( empty ( $_POST["signupEmail"] ) ) {
			
			// oli email, kuid see oli tühi
			$signupEmailError = "See väli on kohustuslik!";
			
		} else {
			
			// email on õige, salvestan väärtuse muutujasse
			$signupEmail = $_POST["signupEmail"];
			
		}
		
	}
	
	if ( isset ( $_POST["signupPassword"] ) ) {
		
		if ( empty ( $_POST["signupPassword"] ) ) {
			
			// oli password, kuid see oli tühi
			$signupPasswordError = "See väli on kohustuslik!";
			
		} else {
			
			// tean et parool on ja see ei olnud tühi
			// VÄHEMALT 8
			
			if ( strlen($_POST["signupPassword"]) < 8 ) {
				
				$signupPasswordError = "Parool peab olema vähemalt 8 tähemärkki pikk";
				
			}
			
		}
		
	}

	if ( isset ( $_POST["signupPassword"] ) ) {
		if ( empty ( $_POST["signupGender"] ) ) {
			$signupGenderError = "See väli on kohustuslik!";
		} else {
			$signupGender = $_POST["signupGender"];
		}
	}
	
	
	// Kus tean et ühtegi viga ei olnud ja saan kasutaja andmed salvestada
	if ( isset($_POST["signupPassword"]) &&
		 isset($_POST["signupEmail"]) &&
		  isset($_POST["signupGender"]) &&
		empty($signupEmailError) &&
		 empty($signupPasswordError) &&
		empty($signupGenderError)
	   ) {
		
		echo "Salvestan...<br>";
		echo "email ".$signupEmail."<br>";
		echo "sugu ".$signupGender."<br>";
		
		$password = hash("sha512", $_POST["signupPassword"]);
		
		echo "parool ".$_POST["signupPassword"]."<br>";
		echo "räsi ".$password."<br>";
		
		//echo $serverPassword;
		
		$signupEmail = $Helper->cleanInput ($signupEmail);
		$password = $Helper->cleanInput ($password);

		$user_class->signup($signupEmail, $password, $gender);
	   
	   
		
	}
	
	$error = "";
	// kontrollin, et kasutaja täitis välja ja võib sisse logida
	if ( isset($_POST["loginEmail"]) &&
		 isset($_POST["loginPassword"]) &&
		 !empty($_POST["loginEmail"]) &&
		 !empty($_POST["loginPassword"])
	  ) {
		
		//login sisse
		$user_class->login($_POST["loginEmail"], $_POST["loginPassword"]);
		echo "Logi";
		
	}
	

?>
<!DOCTYPE html>
<html>
	<head>
		<title>Sisselogimise lehekülg</title>
	</head>
	<body>

		<h1>Logi sisse</h1>
		
		<form method="POST">
			<p style="color:red;"><?=$error;?></p>
			<label>E-post</label><br>
			<input name="loginEmail" type="email">
			
			<br><br>
			
			<input name="loginPassword" type="password" placeholder="Parool">
			
			<br><br>
			
			<input type="submit" value="Logi sisse">
			
		</form>
		
		<h1>Loo kasutaja</h1>
		
		<form method="POST">
			
			<label>E-post</label><br>
			<input name="signupEmail" type="email" value="<?=$signupEmail;?>"> <?php echo $signupEmailError; ?>
			
			<br><br>
			
			<input name="signupPassword" type="password" placeholder="Parool"> <?php echo $signupPasswordError; ?>
			
			<br><br>

				<input type="radio" name="signupGender" value="male" > Male<br>

				<input type="radio" name="signupGender" value="female" > Female<br>

				<input type="radio" name="signupGender" value="other" > Other<br>

			<?php echo $signupGenderError; ?>
			 
			
			<input type="submit" value="Loo kasutaja">
			
		</form>
		
	</body>
	</html>