<?php
require("../config.php");
require("functions.php");
require("helper.class.php");
//require("user.class.php");
require("toode.class.php");

	$toode_class = new toode($mysqli);
	$Helper = new Helper();
	
	// kas on sisseloginud, kui ei ole siis
	// suunata login lehele
	if (!isset ($_SESSION["userID"])) {
		
		header("Location: login.php");
		exit();
		
	}
	
	//kas ?logout on aadressireal
	if (isset($_GET["logout"])) {
		
		session_destroy();
		
		header("Location: login.php");
		exit();
		
	}
	
	// ei ole tühjad väljad mida salvestada
	if ( isset($_POST["toode"]) &&
		 isset($_POST["ostuhind"]) &&
		 isset ($_POST ["myygihind"]) &&
		 !empty($_POST["toode"]) &&
		 !empty($_POST["ostuhind"]) &&
		 !empty ($_POST["myygihind"])
	
	  ) {
		$toode = $Helper->cleanInput($_POST["toode"]);
		$toode_class->insert($Helper->cleanInput($_POST["toode"]), $_POST["ostuhind"], $_POST["myygihind"]);
		
		header("Location: data.php");
		exit();
	}

	// aadress
	if( isset($_POST["search"])){

		$content = $Helper->cleanInput($_POST["content"]);
		$order = $Helper->cleanInput($_POST["order"]);

		if( isset($_POST["content"]) &&
			isset($_POST["order"]) &&
			!empty($_POST["content"]) &&
			!empty($_POST["order"])
		){

			$people = $toode_class->get("true", $content, $order, $sort);

		}

		if(empty($_POST["content"])){

			echo "<br />Viga otsingus! Sisesta otsingusõna või number!<br />";

			$people = $toode_class->get("false", "", "ASC", "id"); }


	} else {

		$people = $toode_class->get("false", "", "ASC", "id");

	}
	
?>
<h1>PANDIMAJA ANDMETABEL</h1>
<p>
	Tere tulemast <?=$_SESSION["email"];?>!
	<a href="?logout=1">Logi välja</a>
</p> 

<h1>Lisa toode</h1>
<form method="POST">
			
	<label>Lisa toode</label><br>
	<input type="text" name="toode" placeholder="<?php if(!empty($_POST["toode"])) {
			echo $_POST["toode"];
		} else {
			echo "toode";
		}?>" > 
	<input type="number_format" name="ostuhind" placeholder="<?php if(!empty($_POST["ostuhind"])) {
			echo $_POST["ostuhind"];
		} else {
			echo "ostuhind";
		}?>" > 

	<input type="number_format" name="myygihind" placeholder="<?php if(!empty($_POST["myygihind"])) {
			echo $_POST["myygihind"];
		} else {
			echo "müügihind";
		}?>">
		
<input type="submit" value="Salvesta">
	
</form>
<h2>Otsing</h2>
<form method="POST">

	<input type="text" name="content" placeholder="Otsisõna" <?php if(!empty($_POST["content"])){ echo "value='".$_POST["content"]."'"; } ?>/>
	<label>Järjesta
		<select name="cond">
			<option value="id" <?php if(!empty($_POST["cond"])){ if($_POST["cond"] == "id"){ echo "selected='selected'"; } } ?>>ID</option>
			<option value="toode" <?php if(!empty($_POST["cond"])){ if($_POST["cond"] == "toode"){ echo "selected='selected'"; } } ?>>TOODE</option>
			<option value="ostuhind" <?php if(!empty($_POST["cond"])){ if($_POST["cond"] == "ostuhind"){ echo "selected='selected'"; } } ?>>OSTUHIND</option>
			<option value="myygihind" <?php if(!empty($_POST["cond"])){ if($_POST["cond"] == "myygihind"){ echo "selected='selected'"; } } ?>>MÜÜGIHIND</option>
		</select>
		järgi
	</label>
	<label>
		<select name="order">
			<option value="ASC" <?php if(!empty($_POST["order"])){ if($_POST["order"] == "ASC"){ echo "selected='selected'"; } } ?>>Kasvavas</option>
			<option value="DESC" <?php if(!empty($_POST["order"])){ if($_POST["order"] == "DESC"){ echo "selected='selected'"; } } ?>>Kahanevas</option>
		</select>
		järjekorras
	</label>
	<button type="submit" name="search" value="search">Otsi</button>

</form>
<h2>Pandimaja toodete nimekiri</h2>
<form method="POST">
<?php
	
	$html = "<table>";
		$html .= "<tr>";
			$html .= "<th>id</th>";
			$html .= "<th>toode</th>";
			$html .= "<th>ostuhind</th>";
			$html .= "<th>müügihind</th>";
			$html .= "<th>Toimingud</th>";
		$html .= "</tr>";

		foreach($people as $p){
			$html .= "<tr>";
				$html .= "<td>".$p->id."</td>";
				$html .= "<td>".$p->toode."</td>";
				$html .= "<td>".$p->ostuhind."</td>";
				$html .= "<td>".$p->myygihind."</td>";
				$html .= "<td><a href='edit.php?id=".$p->id."'>MUUDA VÕI KUSTUTA</a></td>";
				
			$html .= "</tr>";	
		}

	$html .= "</table>";
	echo $html;

?>
</form>