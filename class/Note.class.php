<?php 
class Note {
	
    private $connection;
	
	function __construct($mysqli){
		$this->connection = $mysqli;
	}
	
	/* KLASSI FUNKTSIOONID */
    
    function saveNote($automark, $rendikestvus, $värv) {
		
		$stmt = $this->connection->prepare("INSERT INTO colorNotes2 (automark, rendikestvus, värv) VALUES (?, ?, ?)");
		echo $this->connection->error;
		
		$stmt->bind_param("sss", $automark, $rendikestvus, $värv );
		if ( $stmt->execute() ) {
			echo "salvestamine õnnestus";	
		} else {	
			echo "ERROR ".$stmt->error;
		}
		
	}
	
	
	function getAllNotes($q, $sort, $order) {
		
		//lubatud tulbad
		$allowedSort = ["id", "automark", "rendikestvus", "värv"];
		
		if(!in_array($sort, $allowedSort)){
			//ei olnud lubatud tulpade sees
			$sort = "id"; //las sorteerib id järgi
		}
		
		$orderBy = "ASC";
		
		if($order == "DESC"){
			$orderBy = "DESC";
		}
		
		echo "sorteerin ".$sort." ".$orderBy." ";
		
		//otsime
		if($q != "") {
			
			echo "Otsin: ".$q;
			
			$stmt = $this->connection->prepare("
				SELECT id, automark, rendikestvus, värv
				FROM colorNotes2
				WHERE deleted IS NULL
				AND (automark LIKE ? OR rendikestvus LIKE ? OR värv LIKE ?)
				ORDER BY $sort $orderBy
			");
			$searchWord = "%".$q."%";
			$stmt->bind_param("sss", $searchWord, $searchWord, $searchWord);
		
		}else{
			//ei otsi
			$stmt = $this->connection->prepare("
				SELECT id, automark, rendikestvus, värv
				FROM colorNotes2
				WHERE deleted IS NULL
				ORDER BY $sort $orderBy
			");
		}
		
		$stmt->bind_result($id, $automark, $rendikestvus, $värv);
		$stmt->execute();
		
		$result = array();
		
		// tsükkel töötab seni, kuni saab uue rea AB'i
		// nii mitu korda palju SELECT lausega tuli
		while ($stmt->fetch()) {
			//echo $note."<br>";
			
			$object = new StdClass();
			$object->id = $id;
			$object->automark = $automark;
			$object->rendikestvus = $rendikestvus;
			$object->värv = $värv;
			
			
			array_push($result, $object);
			
		}
		
		return $result;
		
	}
	
	function getSingleNoteData($edit_id){
    		
		$stmt = $this->connection->prepare("SELECT automark, rendikestvus, värv FROM colorNotes2 WHERE id=? AND deleted IS NULL");
		$stmt->bind_param("i", $edit_id);
		$stmt->bind_result($automark, $rendikestvus, $värv);
		$stmt->execute();
		
		//tekitan objekti
		$n = new Stdclass();
		
		//saime ühe rea andmeid
		if($stmt->fetch()){
			// saan siin alles kasutada bind_result muutujaid
			$n->automark = $automark;
			$n->rendikestvus = $rendikestvus;
			$n->värv = $värv;
			
		}else{
			// ei saanud rida andmeid kätte
			// sellist id'd ei ole olemas
			// see rida võib olla kustutatud
			header("Location: data.php");
			exit();
		}
		
		$stmt->close();		
		return $n;
		
	}
	function updateNote($id, $automark, $rendikestvus, $värv){
				
		$stmt = $this->connection->prepare("UPDATE colorNotes2 SET automark=?, rendikestvus=?, värv=? WHERE id=? AND deleted IS NULL");
		$stmt->bind_param("sssi",$automark, $rendikestvus, $värv, $id);
		
		// kas õnnestus salvestada
		if($stmt->execute()){
			// õnnestus
			echo "salvestus õnnestus!";
		}
		
		$stmt->close();
		
	}
	
	function deleteNote($id){
		
		$stmt = $this->connection->prepare("
			UPDATE colorNotes2 
			SET deleted=NOW() 
			WHERE id=? AND deleted IS NULL
		");
		$stmt->bind_param("i", $id);
		
		// kas õnnestus salvestada
		if($stmt->execute()){
			// õnnestus
			echo "salvestus õnnestus!";
		}
		
		$stmt->close();
		
	}
	
} 
?>