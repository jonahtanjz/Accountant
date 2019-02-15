<?php
require ("db.php"); 
if ($_GET["trip"])
{
if ($_SERVER["REQUEST_METHOD"]=='POST')
{
	$trip = $_GET["trip"];
	$trip_transactions = $trip."_transactions";
	$get_currency = "SELECT currency FROM trip WHERE trip_name = '$trip'";
	$currencydata = mysqli_query($dbc, $get_currency);
	while ($c = mysqli_fetch_array($currencydata, MYSQLI_NUM))
	{
	    $currency = $c[0];
	}
	$payers = $_POST["payer"];
	$amount = floatval($_POST["amt"]);
	if (!isset($_POST["sgd"]))
	{
	    $amount = $amount/$currency;
	}
	$description = $_POST["description"]; 
	$payees = $_POST["payee"];
	$amt_each = ($amount/sizeof($payers))/(sizeof($payees));
	
	foreach($payees as $payee)
	{
	    foreach($payers as $payer)
	    {
		    if($payee != $payer)
			{
				$q = "INSERT INTO `$trip_transactions` (payer, payee, amount, description) VALUES ('$payer', '$payee', '$amt_each', '$description')";
				$r = mysqli_query($dbc, $q);
			}
	    }
	}
	
	echo "Done";
	
	die();
	
}


else
{
	$trip_name = $_GET["trip"];
	$trip_people = $trip_name."_people";
	$query = "SELECT name FROM `$trip_people`";
	$people = mysqli_query($dbc, $query);
	
	echo "<html>
	<head><title>Accountant</title></head>
	<body>
	<h3><a href='view.php?trip=".$trip_name."'>View Ledger</a>-----<a href='select.php'>New Trip</a></h3>
	<form action='".$_SERVER['REQUEST_URI']."' method='post'>
	<h2>Payee:</h2>";
	while ($row = mysqli_fetch_array($people, MYSQLI_NUM))
	{
		echo "<input type='checkbox' name='payee[]' value='".$row[0]."'> ".$row[0]." <br />";
	}
	echo" <br />
	<br />
	Amount: <input type='text' name='amt'>
	Desription: <input type='text' name='description'>
	<br /><br />
	Unsplit: <input type='checkbox' name='unsplit' value='unsplit'>
	SGD: <input type='checkbox' name='sgd' value='sgd'>
	<br />
	<br />
	<h2>Payer:</h2>";
	$people = mysqli_query($dbc, $query);
	while ($row = mysqli_fetch_array($people, MYSQLI_NUM))
	{
		echo "<input type='checkbox' name='payer[]' value='".$row[0]."'> ".$row[0]."<br />";
	}
	
	echo "<br /><input type='checkbox' onClick='checkall(this)'> Check All <br />
	<br />
	<input type='submit' name='submit' value='submit'>
	</form>
	<script type='text/javascript'>
	function checkall(source) {
	checkboxes = document.getElementsByName('payer[]');
	for(var i=0, n=checkboxes.length;i<n;i++) {
		checkboxes[i].checked = source.checked;
	}
	}
	</script>
	</body>
	</html>
	";
}
}

else
{
    header ('Location: select.php');
}
?>