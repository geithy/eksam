<?php 
class toode {
	
	private $connection;
	
	function __construct($mysqli){
		
		$this->connection = $mysqli;
	}
	//funktsioon kustuta
	function delete($id){
		$stmt = $this->connection->prepare("UPDATE pandimaja SET deleted=NOW() WHERE id=? AND deleted IS NULL");
		$stmt->bind_param("i",$id);
		
		// kas õnnestus salvestada
		if($stmt->execute()){
			// õnnestus
			echo "Kustutamine õnnestus! Uuendame lehte 1 sekundi pärast!";
		}
		
		$stmt->close();

		header("Refresh:1; url=data.php");
		
		
	}
		
	function get($q, $content, $order, $sort) {
		
		$allowedSort = ["id", "toode", "ostuhind", "myygihind"];
		
		if(!in_array($sort, $allowedSort)){
			// ei ole lubatud tulp
			$sort = "id";
		} else {
			// kuion lubatud tulp
			$sort = $sort;
		}
		
		$orderBy = "ASC";
		
		if ($order == "DESC") {
			$orderBy = "DESC";
		}
		//echo "Sorteerin: ".$sort." ".$orderBy." ";
		//echo $q;
		
		
		//kas otsib
		if ($q == "true") {
			
			echo "Otsib: ". $q;

			$stmt = $this->connection->prepare("
				SELECT id, toode, ostuhind, myygihind
				FROM pandimaja
				WHERE deleted IS NULL 
				AND ((toode LIKE ?) OR (ostuhind LIKE ?) OR (myygihind LIKE ?) )
				ORDER BY $sort $order
			");
			$content = "%".$content."%";
			$stmt->bind_param("sss", $content, $content, $content);
			
		} else {

			$stmt = $this->connection->prepare("
				SELECT id, toode, ostuhind, myygihind
				FROM pandimaja
				WHERE deleted IS NULL
				ORDER BY $sort $order
			");
			
		}
		
		echo $this->connection->error;
		
		$stmt->bind_result($id, $toode, $ostuhind, $myygihind);
		$stmt->execute();
		
		
		//tekitan massiivi
		$result = array();
		
		// tee seda seni, kuni on rida andmeid
		// mis vastab select lausele
		while ($stmt->fetch()) {
			
			//tekitan objekti
			$t = new StdClass();
			
			$t->id = $id;
			$t->toode = $toode;
			$t->ostuhind = $ostuhind;
			$t->myygihind = $myygihind;
			
			
			//echo $toode."<br>";
			// iga kord massiivi lisan juurde raamatu pealkirja
			array_push($result, $t);
		}
		
		$stmt->close();

		return $result;
		}
		function getSingle($edit_id){
		$stmt = $this->connection->prepare("SELECT toode, ostuhind, myygihind FROM pandimaja WHERE id=? AND deleted IS NULL");
		$stmt->bind_param("i", $edit_id);
		$stmt->bind_result($toode, $ostuhind, $myygihind);
		$stmt->execute();
		
		//tekitan objekti
		$t = new Stdclass();
		
		//saime ühe rea andmeid
		if($stmt->fetch()){
			// saan siin alles kasutada bind_result muutujaid
			$t->toode = $toode;
			$t->ostuhind = $ostuhind;
			$t->myygihind = $myygihind;
			
			
		}else{
			// ei saanud rida andmeid kätte
			// sellist id'd ei ole olemas
			// see rida võib olla kustutatud
			header("Location: data.php");
			exit();
		}
		
		$stmt->close();
		
		
		return $t;
		
}
	function update($id, $toode, $ostuhind, $myygihind){

		$stmt = $this->connection->prepare("UPDATE pandimaja SET toode=?, ostuhind=?, myygihind=? WHERE id=? AND deleted IS NULL");
		$stmt->bind_param("siii",$toode, $ostuhind, $myygihind, $id);

		// kas õnnestus salvestada
		if($stmt->execute()){
			// õnnestus
			echo "salvestus õnnestus!";
		}

		$stmt->close();


	}

	function insert($toode, $ostuhind, $myygihind){
    	
		$stmt = $this->connection->prepare("INSERT INTO pandimaja (toode, ostuhind, myygihind) VALUES (?, ?, ?)");
		$stmt->bind_param("sii",$toode, $ostuhind, $myygihind);
		
		// kas õnnestus salvestada
		if($stmt->execute()){
			// õnnestus
			echo "lsaimine õnnestus!";
		}
		
		$stmt->close();
		
		
	}
	function cleanInput($input) { 
		$input = trim($input);
		$input = stripslashes($input);
		$input = htmlspecialchars($input);
		return $input;
	}
}
?>