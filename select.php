<?php
require("db.php");
if ($_SERVER["REQUEST_METHOD"] == "POST")
{
	if (isset($_POST["create"]))
	{
    	if ((!empty($_POST["tripname"])) && (!empty($_POST["people"])) && (!empty($_POST["currency"]))&& (floatval($_POST["currency"]) != 0))
    	{
    	    $trip_name = $_POST["tripname"];
	    $currency = floatval($_POST["currency"]);
	    $currency = mysqli_real_escape_string($dbc, $currency);
    	    $tripname = mysqli_real_escape_string($dbc, $trip_name);
    	    $people_data = $tripname."_people";
    	    $transactions_data = $tripname."_transactions";
	        $people = explode (",", $_POST["people"]);
	        $query = "SELECT trip_name FROM trip WHERE trip_name = '$trip_name'";
	        $tripexist = mysqli_query($dbc, $query);
	        if (!(mysqli_fetch_row($tripexist)>0))
	        {
	    	    $create_people = "CREATE TABLE  `$people_data` ( 
	    	    `id` INT(10) NOT NULL AUTO_INCREMENT , 
	    	    `name` VARCHAR(64) NOT NULL , 
	    	    PRIMARY KEY (`id`)
	    	    )";
		
    	        $create_transactions = "CREATE TABLE `$transactions_data` ( 
	    	    `id` INT(10) NOT NULL AUTO_INCREMENT , 
	    	    `payer` VARCHAR(64) NOT NULL , 
	    	    `payee` VARCHAR(64) NOT NULL , 
	    	    `amount` FLOAT NOT NULL , 
	    	    `description` VARCHAR(64) NOT NULL ,
	    	    PRIMARY KEY (`id`)
	    	    )";
		
		    $people_table = mysqli_real_escape_string($dbc, $people_data);
		    $transactions_table = mysqli_real_escape_string($dbc, $transactions_data);
	    	    $r1 = mysqli_query($dbc, $create_people);
	    	    $r2 = mysqli_query($dbc, $create_transactions);
	    	    $add_trip = "INSERT INTO trip (trip_name, people, transactions, currency) VALUES ('$trip_name', '$people_table', '$transactions_table', '$currency')";
	    	    $r3 = mysqli_query($dbc, $add_trip);
		
	    	    foreach ($people as $person)
	    	    {
	    		    $person = mysqli_real_escape_string($dbc, $person);
	    		    $add_people = "INSERT INTO `$people_data` (name) VALUES    ('$person')";
			     $r4 = mysqli_query($dbc, $add_people);
		        }
		
	    	    echo "Success";
	        }
	        
	        else
	        {
	            echo "Trip already exists";
	        }
		
	    }
	
	    else
	    {
	    	echo "Empty field(s) or 0 in currency. Now re-type.";
	    }
	}
	
	elseif (isset($_POST["selector"]))
    {
	    $data = $_POST["selector"];
	    echo "<meta http-equiv='refresh' content='0;url=index.php?trip=".rawurlencode($data)."'>";
    }
    
    die();
}

else
{
	$trips = "SELECT trip_name FROM trip";
	$trip = mysqli_query($dbc, $trips);
	echo "<form action='select.php' method='POST'><select name='selector'>";
	while($row = mysqli_fetch_array($trip, MYSQLI_NUM))
	{
		echo "<option value='".$row[0]."'>".$row[0]."</option>";
	}
	echo "</select><input type='submit' name='select_trip' value='select'></form>";
}


?>

<html>
<head><title>Accountant</title></head>
<body>
<form action="select.php" method="POST">
<br />Trip Name: <input type="text" name="tripname"><br /><br />
People : <input type="text" name="people"><br /><br />
1 SGD = <input type="text" name="currency"><br /><br />
<input type="submit" name="create" value="submit">
</form>
</body>
</html>
