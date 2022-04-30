<!DOCTYPE html>
<html>

<head>
    <meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.1.3/css/bootstrap.min.css" integrity="sha384-MCw98/SFnGE8fJT3GXwEOngsV7Zt27NXFoaoApmYm81iuXoPkFOJwJ8ERdknLPMO" crossorigin="anonymous">
    <title>Snow Service - Search Results</title>
</head>
<body>
<main role="main" class="container-fluid">
	<h1>Snow Service Search Results</h1>
	<?php
		$searchname = trim($_POST["searchname"]);
		$searchremname = trim($_POST["searchremname"]);
		$searchdate = $_POST["searchdate"];
		$searchzip = $_POST["searchzip"];
		
		if (!$searchname && !$searchzip && !$searchremname && !$searchdate) {
			//  if all of the fields are empty
			echo "<a href=\"snowservice.html\"><< Back to Snow Service Database</a><br>";
			echo "<h2>Error</h2>You have not entered search details. Please try again.";
			exit;
		}
		
		if (($searchname || $searchzip) && ($searchremname || $searchdate)) {
			// if attempting to search both types of search, no go
			echo "<a href=\"snowservice.html\"><< Back to Snow Service Database</a><br>";
			echo "<h2>Error</h2>Customers and Removals cannot be searched together.<br>Please perform only 1 type of search at a time.";
			exit;
		}
		
		@$db = new mysqli("localhost", "snowservPHPuser", "cpsc33000" ,"snowservice");
		
		if ($db->connect_error) {
			die("Connect error".$db->connect_errorno.": ".$db->connect_error);
		}
		
		if ($searchname || $searchzip) {
			// search CustomerSearchView via one or both of Name or ZipCode
			echo "<a href=\"snowservice.html\"><< Back to Snow Service Database</a><br>";
			echo "<h2>Results of Customer Search</h2>";
			$query = "SELECT * FROM CustomerSearchView WHERE ";
			if ($searchname) {
				echo "Criteria: Name: $searchname";
				$query .= "Name LIKE '%$searchname%' ";
			}
			if ($searchname && $searchzip) { 
				echo "<br>";
				$query .= "AND ";
			}
			if ($searchzip) {
				echo "Criteria: Zip Code: $searchzip";
				$query .= "ZipCode LIKE '%$searchzip%'";
			}
			// echo "<br>".$query; // For Debugging
			
		} elseif ($searchremname || $searchdate) {
			// search RemovalsPrices via one or both of Name or Date
			echo "<a href=\"snowservice.html\"><< Back to Snow Service Database</a><br>";
			echo "<h2>Results of Removals Search</h2>";
			$query = "SELECT * FROM RemovalsPrices WHERE ";
			if ($searchremname) {
				echo "Criteria: Name: $searchremname";
				$query .= "Name LIKE '%$searchremname%' ";
			}
			if ($searchremname && $searchdate) { 
				echo "<br>";
				$query .= "AND ";
			}
			if ($searchdate) {
				echo "Criteria: Date: $searchdate";
				$query .= "'$searchdate' >= DATE(Date_and_Time) AND '$searchdate' <= DATE(Date_and_Time)";
			}
			// echo "<br>".$query; // For Debugging
		}
		
		$result = $db->query($query);
		
		$num_results = $result->num_rows;
		echo "<p>Number of results found: $num_results</p>";
		
        echo "<table class='table table-responsive'>";
		
		if ($searchdate || $searchremname) {
			// display Removals search results
			echo "<th>Name</th>";
			echo "<th>Total Price</th>";
			echo "<th><div align=center>Date</div></th>";
			
			echo "<tr>";
			for ($i = 1; $i <= $num_results; $i++) {
				$row = $result->fetch_assoc();
				echo "<td>".stripslashes($row["Name"])."</td>";
				echo "<td><div align=center>$".$row["TotalPrice"]."</div></td>";
				echo "<td>".substr($row["Date_and_Time"], 0, -9)."</td>";
				echo "</tr>";
			}
		} elseif ($searchzip || $searchname) {
			// display Customer search results
			echo "<th>Name</th>";
			echo "<th>Address</th>";
			echo "<th>Zip Code</th>";
			echo "<tr>";
			for ($i = 1; $i <= $num_results; $i++) {
				$row = $result->fetch_assoc();
				echo "<td>".stripslashes($row["Name"])."</td>";
				echo "<td>".stripslashes($row["StreetNumber"].' '.$row["Street"].'<br>'.$row["City"])."</td>";
				echo "<td><br>".stripslashes($row["ZipCode"])."</td>";
				echo "</tr>";
			}
		}	
		$result->free();
		$db->close();
				
	?>	
</body>
</html>