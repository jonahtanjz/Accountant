<?php
require("db.php");

$current_trip = mysqli_real_escape_string($dbc, $_GET["trip"]);
$query1 = "SELECT trip_name FROM trip WHERE trip_name = '$current_trip'";
$tripexist = mysqli_query($dbc, $query1);

if (mysqli_num_rows($tripexist)>0)
{		
	$trip = mysqli_real_escape_string($dbc, $_GET["trip"]);
	$getTable = "SELECT people, transactions FROM trip WHERE trip_name = '$trip'";
    	$PTtable = mysqli_query($dbc, $getTable);
	$PTrow = mysqli_fetch_row($PTtable);
	$trip_people = $PTrow[0];
	$trip_transactions = $PTrow[1];
	$individual = "SELECT name FROM `$trip_people`";
	$pay = "SELECT * FROM `$trip_transactions`";
	$people = mysqli_query($dbc, $individual);
    	$total = array();
	
    	while ($row1 = mysqli_fetch_array($people, MYSQLI_NUM))
    	{
		$owe = 0;
		$receive = 0;
		$view_pay = mysqli_query($dbc, $pay);
		while ($row = mysqli_fetch_array($view_pay, MYSQLI_NUM))
		{
			if ($row[1] == $row1[0])
			{
		    		$owe = $owe + $row[3];
		    	}
		    	if ($row[2] == $row1[0])
		    	{
		    		$receive = $receive + $row[3];
		    	}
		}
				
	    	$total[$row1[0]] = $receive - $owe;
	}
			
	asort($total);
	$excess = 0;
	$numofrows = count($total);
	$counter = 1;
	foreach($total as $person_name => $total_amount)
	{
		$skip = 0;
		if ($total_amount < 0)
		{
			$excess = $excess + abs($total_amount);
		}
		elseif ($total_amount > 0)
		{
			$excess = $excess - $total_amount;
		}
		else
		{
			$skip = 1;
		}
			
		if (($skip == 0) && ($counter < $numofrows))
		{
			echo "<strong>".$person_name." To PAY: SGD ".$excess." ==> </strong>";
		}
		elseif($counter == $numofrows)
		{
			echo "<strong>".$person_name."</strong>";
		}
		    
		$counter++;
	}
	
	die();
}	

else
{
    header ('Location: select.php');
}		
?>
