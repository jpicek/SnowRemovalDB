<!DOCTYPE html>
<html>

<head>
    <meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.1.3/css/bootstrap.min.css" integrity="sha384-MCw98/SFnGE8fJT3GXwEOngsV7Zt27NXFoaoApmYm81iuXoPkFOJwJ8ERdknLPMO" crossorigin="anonymous">
    <title>Snow Service Display Data</title>
</head>
<body>
<main role="main" class="container-fluid">
	<h1>Snow Service Display Data</h1>
	<?php
		$searchtype = $_POST["searchtype"];
		
		@$db = new mysqli("localhost", "snowservPHPuser", "cpsc33000" ,"snowservice");
		
		if ($searchtype == 'customer') { 
			// select all customers and addresses
			$query = "SELECT CONCAT(FirstName, ' ', LastName) AS Name, PhoneNumber,
						StreetNumber, Street, City, ZipCode
						FROM Address A
						RIGHT JOIN Customer C
						ON A.CustomerID = C.CustomerID;";
		}
		
		if ($searchtype == 'removal') { 
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
			}
		
		if ($searchtype == 'event') { 
			// select all snowfall events
			$query = "SELECT DATE(TimeDate) AS d, TIME(TimeDate) AS t, InchesOfSnow FROM SnowFallEvent";
			}
		
		echo "Displaying info on $searchtype"."s";
				
		$result = $db->query($query);
		
		$num_results = $result->num_rows;
		echo "<p>Number of results found: $num_results</p>";
		
        echo "<table class='table table-responsive'>";
		
		if ($searchtype == 'customer') {
			// display all Customers and addresses
			echo "<th>Name</th>";
			echo "<th>Address</th>";
			echo "<th>Zip Code</th>";
			echo "<th>Phone Number</th>";
			echo "<tr>";
			for ($i = 1; $i <= $num_results; $i++) {
				$row = $result->fetch_assoc();
				echo "<td>".($row["Name"])."</td>";
				echo "<td>".($row["StreetNumber"].' '.$row["Street"].'<br>'.$row["City"])."</td>";
				echo "<td><br>".($row["ZipCode"])."</td>";
				echo "<td>".sprintf("%s-%s-%s",substr(($row["PhoneNumber"]), 0, 3),substr(($row["PhoneNumber"]), 3, 3),substr(($row["PhoneNumber"]), 6))."</td>";
				echo "</tr>";
			}
		}
		if ($searchtype == 'removal') {
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
					$paid = "Yes";
				}
				else {
					$paid = "No";
				}
				echo "<td>".($row["Name"])."</td>";
				echo "<td><div align=center>$".$row["TotalPrice"]."</div></td>";
				echo "<td>".substr($row["Date_and_Time"], 0, -9)."</td>";
				echo "<td><div align=center>".($plow)."</div></td>";
				echo "<td><div align=center>".($salt)."</div></td>";
				echo "<td><div align=center>".($paid)."</div></td>";
				echo "</tr>";
			}
		}
		
		if ($searchtype == 'event') {
			// display all snowfall events
			echo "<th>Date</th>";
			echo "<th>Time</th>";
			echo "<th>Snowfall</th>";
			echo "<tr>";
			for ($i = 1; $i <= $num_results; $i++) {
				$row = $result->fetch_assoc();
				echo "<td>".($row["d"])."</td>";
				echo "<td>".($row["t"])."</td>";
				echo "<td><div align=center>".($row["InchesOfSnow"])." in.</div></td>";
				echo "</tr>";
			}
		}		
		$result->free();
		$db->close();
				
	?>	
</body>
</html>