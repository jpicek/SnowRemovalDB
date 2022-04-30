<!DOCTYPE html>
<html>

<head>
    <meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.1.3/css/bootstrap.min.css" integrity="sha384-MCw98/SFnGE8fJT3GXwEOngsV7Zt27NXFoaoApmYm81iuXoPkFOJwJ8ERdknLPMO" crossorigin="anonymous">
    <title>Snow Service Add Data</title>
</head>
<body>
<main role="main" class="container-fluid">
	<h1>Snow Service Add Data</h1>
	<?php
		$addtype = $_POST["addtype"];
		
		@$db = new mysqli("localhost", "snowservPHPuser", "cpsc33000" ,"snowservice");
		
		if ($db->connect_error) {
			die("Could not connect: ". $db->connect_errno . ": " . $db->connect_error);
		}
		echo "<a href=\"snowservice.html\"><< Back to Snow Service Database</a><br>";
		
		if ($addtype == 'event') { 
			// adding a SnowFallEvent
			echo "Add Snowfall Event<br>";
			$date = $_POST["date"];
			$time = $_POST["time"];
			$inches = $_POST["inches"];
			
			$def = 'DEFAULT';
			$date = "$date";
			$time = "$time";
			$datetime = $date."T".$time;
			
			$query = "INSERT INTO SnowFallEvent VALUES (?, ?, ?)";
			
			$stmt = $db->prepare($query);
			echo "VALUES(".$def.", ".$datetime.", ".$inches.")<br>";
			$stmt->bind_param("ssi", $def, $datetime, $inches);
			$stmt->execute();
			echo $stmt->affected_rows." Snowfall event successfully inserted into database";	
		}
		
		if ($addtype == 'removal') { 
			// adding a SnowRemoval
			echo "Add Snow Service (Removal and/or Salting)<br>";
			$num = $_POST["num"];
			$st = $_POST["st"];
			$city = $_POST["city"];
			$snow = $_POST["snow"];
			if (!isset($_POST["snow"])) {
				$snow = 0;
			}
			else {
				$snow = 1;
			}
			if (!isset($_POST["salt"])) {
				$salt = 0;
			}
			else {
				$salt = 1;
			}
			$query = "SELECT AddressID from Address WHERE
			           StreetNumber LIKE '$num' AND
					   Street LIKE '$st' AND
					   City LIKE '$city'";
					   
			$result = $db->query($query);
			$num_results = $result->num_rows;
			if ($num_results > 1) {
				echo "Error: more than 1 address selected.";
				exit;
			}
			else if ($num_results < 1) {
				echo "Address not found.";
				exit;
			}
			else {
				// 1 row found, so address matches
				$row = $result->fetch_assoc();
				// echo $num." ".$st."<br>".$city."<br>"."address ID #".$row["AddressID"]."<br>";
				$aid = $row["AddressID"];
				$query = "SELECT MAX(EventID) AS eidMax FROM SnowfallEvent";
				$eresult = $db->query($query);
				$num_eresults = $eresult->num_rows;
				if ($num_eresults > 1) {
					// shouldn't happen, but
					echo "Error: more than 1 Maximum EventID ???";
				}
				else if ($num_eresults < 1) {
					echo "Error: no maximum event found ???";
					exit;
				}
				else {
					$erow = $eresult->fetch_assoc();
					$eid = $erow["eidMax"];
				}
				
				$query = "INSERT INTO SnowRemoval (AddressID, EventID, wasSnowRemoved, wasWalkwaySalted) VALUES(?, ?, ?, ?)";
				
				$stmt = $db->prepare($query);
				// echo "add VALUES(".$aid.", ".$eid.", ".$snow.", ".$salt.")<br>";
				$stmt->bind_param("iiii", $aid, $eid, $snow, $salt);
				$stmt->execute();
				echo $stmt->affected_rows." SnowRemoval event successfully inserted into database";
			}					   
		}
	
		if ($addtype == 'paid') { 
			// update Paid status
			echo "Change Paid Status";
			$query = "";
		}
		
		$db->close();
				
	?>	
</body>
</html>