<!DOCTYPE html>
<html>

<head>
    <meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.1.3/css/bootstrap.min.css" integrity="sha384-MCw98/SFnGE8fJT3GXwEOngsV7Zt27NXFoaoApmYm81iuXoPkFOJwJ8ERdknLPMO" crossorigin="anonymous">
    <title>Snow Service - Set Paid Status</title>
</head>
<body>
<main role="main" class="container-fluid">
	<h1>Snow Service - Set Paid Status</h1>
	<?php
		@$db = new mysqli("localhost", "snowservPHPuser", "cpsc33000" ,"snowservice");
	
		// select all removals aka services
		// modified version of RemovalsPrices VIEW
		$query = "SELECT CONCAT(firstName, ' ', lastName) AS Name,
					   SUM(IF(wasSnowRemoved <> 0, SnowRemovalPrice, 0) + IF(wasWalkwaySalted <> 0, SaltingPrice, 0)) AS TotalPrice,
					   E.TimeDate AS Date_and_Time,
					   wasSnowRemoved, wasWalkwaySalted, wasPaid
					   FROM Customer C
					   LEFT JOIN Address A
					   ON C.CustomerID = A.CustomerID
					   RIGHT JOIN SnowRemoval R
					   ON A.AddressID = R.AddressID
					   LEFT JOIN SnowfallEvent E
					   ON E.EventID = R.EventID
					   GROUP BY E.TimeDate, R.RemovalNumber, C.CustomerID;";
		
		$result = $db->query($query);
		
		$num_results = $result->num_rows;
		echo "<a href=\"snowservice.html\"><< Back to Snow Service Database</a><br>";
		echo "<p><em>Note: Paid status may not be removed, only added</em></p>";
		
		?>
		<form method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>">
		<input type="submit" name="submit" value="Mark Paid" class="btn btn-primary">
		
		<?php
		echo "<table class='table table-responsive'>";
	
		// display all Removals
		echo "<th>Name</th>";
		echo "<th>Total Price</th>";
		echo "<th><div align=center>Date</div></th>";
		echo "<th>Plowed?</th>";
		echo "<th>Salted?</th>";
		echo "<th>Paid?</th>";
		
		echo "<tr>";
		for ($i = 1; $i <= $num_results; $i++) {
			$row = $result->fetch_assoc();
			$plow = $row["wasSnowRemoved"];
			$salt = $row["wasWalkwaySalted"];
			$paid = $row["wasPaid"];
			if ($plow == 1) {
				$plow = "Yes";
			}
			else {
				$plow = "No";
			}
			if ($salt == 1) {
				$salt = "Yes";
			}
			else {
				$salt = "No";
			}
			if ($paid == 1) {
				$paid = "checked";
			}
			else {
				$paid = "";
			}
			$box = 'box'."00".$i;
			
			echo "<td>".($row["Name"])."</td>";
			echo "<td><div align=center>$".$row["TotalPrice"]."</div></td>";
			echo "<td>".substr($row["Date_and_Time"], 0, -9)."</td>";
			echo "<td><div align=center>".($plow)."</div></td>";
			echo "<td><div align=center>".($salt)."</div></td>";
			echo "<td><div align=center>
					<input type=\"checkbox\" name=\"$box\" id=\"$box'\" value=\"1\" ".($paid)."></div></td>";
					
			echo "</tr>";
		}
		?>
		</form>
		<?php
		
		if ($_SERVER["REQUEST_METHOD"] == "POST") {
			echo "posted<br>";
			$setquery = "UPDATE SnowRemoval SET wasPaid = 1 WHERE RemovalNumber = ";
			
			$notfirst = 0;
			for ($i = 1; $i <= $num_results; $i++) {
				if (isset($_POST["box"."00".$i])) {
					// echo "<br>box"."00".$i." is checked";
					if ($notfirst == 1) {
						// append another RemovalNumber to the WHERE clause
						$setquery .= "OR RemovalNumber = $i ";
					}
					else {
						// otherwise, this is the first change
						// so append the RemovalNumber, set notfirst = 1,
						// and only do the above if from then on
						$setquery .= $i." ";
						$notfirst = 1;
					}
				}
				else {
					// the value of the variable name stored in $loopbox
					// is an unchecked box, so don't append another RemovalNumber
					$setquery .= "";
				}
			}
			// echo "<br><br>Set Query is: ".$setquery."<br>";
			$stmt = $db->prepare($setquery);
			$stmt->execute();
			echo "<br>".$stmt->affected_rows." removals had their paid status changed.<br>";
			echo "<a href=".htmlspecialchars($_SERVER["PHP_SELF"]).">Reload to see updated data</a>";
			for ($j = 1; $j <=50; $j++) {
				echo "<br>";
			}
			exit;
		} 

		$result->free();
		$db->close();
				
	?>	
</body>
</html>