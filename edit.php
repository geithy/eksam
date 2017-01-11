<?php
	//edit.php
	require("functions.php");
	
	require("toode.class.php");
	$toode = new toode($mysqli);
	
	require("helper.class.php");
	$Helper = new Helper();
	
	//var_dump($_POST);
	
	//kas kasutaja uuendab andmeid
	if(isset($_POST["update"])){
		
		$toode->update($Helper->cleanInput($_POST["id"]), $Helper->cleanInput($_POST["toode"]), $Helper->cleanInput($_POST["ostuhind"]), $Helper->cleanInput($_POST["myygihind"]));
		
		header("Location: edit.php?id=".$_POST["id"]."&success=true");
        exit();	
		
	}
	
	//kustutan
	if(isset($_GET["delete"])){
		
		$toode->delete($_GET["id"]);
		
		header("Location: data.php");
		exit();
	}
	
	
	
	// kui ei ole id'd aadressireal siis suunan
	if(!isset($_GET["id"])){
		header("Location: data.php");
		exit();
	}
	
	//saadan kaasa id
	$c = $toode->getSingle($_GET["id"]);
	//var_dump($c);
	
	if(isset($_GET["success"])){
		echo "salvestamine õnnestus";
	}
	
?>

<a href="data.php"> tagasi </a>

<h2>Muuda toote kirjet</h2>
  <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>" method="post" >
	<input type="hidden" name="id" value="<?=$_GET["id"];?>" > 
  	<label for="toode" >Toote nimetus</label><br>
	<input id="toode" name="toode" type="text" value="<?php echo $c->toode;?>" ><br><br>
  	<label for="ostuhind" >Ostuhind</label><br>
	<input id="ostuhind" name="ostuhind" type="number" value="<?=$c->ostuhind;?>"><br><br>
	<label for="myygihind" >Müügihind</label><br>
	<input id="myygihind" name="myygihind" type="number" value="<?=$c->myygihind;?>"><br><br>
  	<label for="toode" >Toote staatus = Kui toode on müüdud, märgi toote nime taha: MÜÜDUD</label><br>
	<input id="toode" name="toode" type="text" value="<?php echo $c->toode;?>" ><br><br>
	<input type="submit" name="update" value="Salvesta">
  </form>
  
  
 <br>
 <br>
 <br>
 <a href="?id=<?=$_GET["id"];?>&delete=true">kustuta</a>
