<?php
require ("db.php"); 
if ($_SERVER["REQUEST_METHOD"]=='POST')
{
	$trip = $_GET["trip"];
	$trip_people = $trip."_people";
	$trip_transactions = $trip."_transactions";
	$person = $_POST["person"];
	$individual = "SELECT name FROM `$trip_people`";
	$pay = "SELECT * FROM `$trip_transactions` WHERE payer = '$person' OR payee = '$person' ";
	$people = mysqli_query($dbc, $individual);
	
	echo "<link rel='stylesheet' type='text/css' href='main.css'>
	<h2>Ledger for ".$person.": </h2>";
	
	while ($row1 = mysqli_fetch_array($people, MYSQLI_NUM))
	{
		$owe = 0;
		$receive = 0;
		if ($row1[0] != $person)
		{
			echo "<p><h3>".$row1[0].": </h3>
			<table>
			<tr>
			<th>Payer:</th>
			<th>Payee:</th>
			<th>Amount: (SGD)</th>
			<th>Description:</th>
			</tr>";
			
			$view_pay = mysqli_query($dbc, $pay);
			while ($row = mysqli_fetch_array($view_pay, MYSQLI_NUM))
			{
				if (($row[1] == $row1[0]) || ($row[2] == $row1[0]))
				{
					echo "<tr>";
					echo "<td>".$row[1]."</td>"."<td>".$row[2]."</td>"."<td>".$row[3]."</td>"."<td>".$row[4]."</td>";
					echo "</tr>";
					if ($row[1] == $person)
					{
						$owe = $owe + $row[3];
					}
					if ($row[2] == $person)
					{
						$receive = $receive + $row[3];
					}
				}
			}
			
			$total = $receive - $owe;
			echo "<tr><th></th><th></th><th>Total:</th><th>SGD ".$total."</th></tr>";
			$payment = abs($total);
			if ($total < 0)
			{
				echo"<tr><th>To PAY ".$row1[0].": SGD ".$payment."</th></tr>";
			}
			elseif ($total > 0)
			{
				echo"<tr><th>To RECEIVE from ".$row1[0].": SGD ".$payment."</th></tr>";
			}
			else
			{
				echo"<tr><th>To PAY/RECEIVE: $0</th></tr>";
			}
			echo "</table><br /><br /><br /><br /><br />";
		}
	}	
	
	die();
}

else 
{

$trip_name = $_GET["trip"];
$trip_people = $trip_name."_people";
$query = "SELECT name FROM `$trip_people`";
$p = mysqli_query($dbc, $query);
	
echo "<html>
<head><title>Accountant</title></head>

<body>
<form action='".$_SERVER['REQUEST_URI']."' method='post'>
<select name='person'>";
while ($row = mysqli_fetch_array($p, MYSQLI_NUM))
	{
		echo "<option value='".$row[0]."'>".$row[0]."</option>";
	}
echo "</select>
<input type='submit' name='submit' value='submit'>
</form>
</body>
</html>
";
}

?>