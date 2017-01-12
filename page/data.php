<?php 
	// et saada ligi sessioonile
	require("../functions.php");
	
    require("../class/Helper.class.php");
	$Helper = new Helper();
	
	require("../class/Note.class.php");
	$Note = new Note($mysqli);
	
	//ei ole sisseloginud, suunan login lehele
	if(!isset ($_SESSION["userId"])) {
		header("Location: login.php");
		exit();
	}
	
	//kas kasutaja tahab välja logida
	// kas aadressireal on logout olemas
	if (isset($_GET["logout"])) {
		
		session_destroy();
		
		header("Location: login.php");
		exit();
	}
	
	if (	isset($_POST["automark"]) && 
			isset($_POST["rendikestvus"]) && 
			isset($_POST["värv"]) &&
			!empty($_POST["automark"]) && 
			!empty($_POST["rendikestvus"]) &&
			!empty($_POST["värv"])
	) {		
	
		$automark = $Helper->cleanInput($_POST["automark"]);
		$rendikestvus = $Helper->cleanInput($_POST["rendikestvus"]);
		$svärv = $Helper->cleanInput($_POST["värv"]);
		$Note->saveNote($automark, $rendikestvus, $värv);
		
	}
	
	$q = "";
	//otsisõna aadressirealt
	if(isset($_GET["q"])){
		$q = $Helper->cleanInput($_GET["q"]);
	}
	
	$sort ="id";
	$order = "ASC";
	
	if(isset($_GET["sort"]) && isset($_GET["order"])){
		$sort = $_GET["sort"];
		$order = $_GET["order"];
	}
	$notes = $Note->getAllNotes($q, $sort, $order);
	
	//echo "<pre>";
	//var_dump($notes);
	//echo "</pre>";

?>
<?php require("../header.php"); ?>

<h1>Data</h1>
<p>
	Tere tulemast <a href="user.php"><?=$_SESSION["userEmail"];?></a>!
	<a href="?logout=1">Logi välja</a>
</p>
<h2><i>Märkmed</i></h2>
<form method="POST">
			
	<label>automark</label><br>
	<input name="automark" type="text">
	
	<br><br>
	
	<label>rendikestvus</label><br>
	<input name="rendikestvus" type="text">
				
	<br><br>
	
	<label>värv</label><br>
	<input name="värv" type="text">
	
	<br><br>
	
	<input type="submit">

</form>

<h2>arhiiv</h2>
<form>
	<input type="search" name ="q" value="<?=$q;?>">
	<input type="submit" value="otsi">
</form>


<h2 style="clear:both;">Tabel</h2>
<?php 

	$html = "<table class='table'>";
		
		$html .= "<tr>";
		
			$orderId = "ASC";
			if(isset($_GET["order"])&&
				$_GET["order"] == "ASC" &&
				$_GET["sort"] == "id" ){
					
				$orderId = "DESC";
				}
				
		$html .= "<th>
				<a href='?q=".$q."&sort=id&order=".$orderId."'>
					id
					</a>
				</th>";
				
				$orderId = "ASC";
			if(isset($_GET["order"])&&
				$_GET["order"] == "ASC" &&
				$_GET["sort"] == "automark" ){
					
				$orderId = "DESC";
				}
				
		$html .= "<th>
				<a href='?q=".$q."&sort=automark&order=".$orderId."'>
					automark
					</a>
				</th>";
			
				$orderId = "ASC";
			if(isset($_GET["order"])&&
				$_GET["order"] == "ASC" &&
				$_GET["sort"] == "rendikestvus" ){
					
				$orderId = "DESC";
				}
				
		$html .= "<th>
				<a href='?q=".$q."&sort=rendikestvus&order=".$orderId."'>
					rendikestvus
					</a>
				</th>";
			
		$orderId = "ASC";
			if(isset($_GET["order"])&&
				$_GET["order"] == "ASC" &&
				$_GET["sort"] == "värv" ){
					
				$orderId = "DESC";
				}
				
		$html .= "<th>
				<a href='?q=".$q."&sort=värv&order=".$orderId."'>
					värv
					</a>
				</th>";	
		
			$orderId = "ASC";
			if(isset($_GET["order"])&&
				$_GET["order"] == "ASC" &&
				$_GET["sort"] == "värv" ){
					
				$orderId = "DESC";
				}
				
		$html .= "<th>
				<a href='?q=".$q."&sort=värv&order=".$orderId."'>
					värv
					</a>
				</th>";
			
			

		$html .= "</tr>";

	foreach ($notes as $note) {
		$html .= "<tr>";
			$html .= "<td>".$note->id."</td>";
			$html .= "<td>".$note->automark."</td>";
			$html .= "<td>".$note->rendikestvus."</td>";
			$html .= "<td>".$note->värv."</td>";
			$html .= "<td><a href='edit.php?id=".$note->id."'>edit.php</a></td>";
		$html .= "</tr>";
	}
	
	$html .= "</table>";
	
	echo $html;

?>


<?php require("../footer.php"); ?>



