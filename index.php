<?php
require ("db.php"); 
if ($_SERVER["REQUEST_METHOD"]=='POST')
{
	$payers = $_POST["payer"];
	$amount = floatval($_POST["amt"]);
	if (!isset($_POST["sgd"]))
	{
	    $amount = $amount/3;
	}
	$description = $_POST["description"]; 
	$payees = $_POST["payee"];
	
	if (!isset($_POST["unsplit"]))
	{
	    $amt_each = ($amount/sizeof($payers))/(sizeof($payees));
	}
	else
	{
	    $amt_each = $amount/(sizeof($payees));
	}
	
	foreach($payees as $payee)
	{
	    foreach($payers as $payer)
	    {
		    if($payee != $payer)
			{
				$q = "INSERT INTO transactions (payer, payee, amount, description) VALUES ('$payer', '$payee', '$amt_each', '$description')";
				$r = mysqli_query($dbc, $q);
			}
	    }
	}
	
	echo "Done";
	
	die();
	
}


?>




<html>
<head><title>Accountant</title></head>

<body>
<h3><a href="view.php">View Ledger</a></h3>
<form action="index.php" method="post">
<h2>Payee:</h2>
<input type="checkbox" name="payee[]" value="tube"> Tube <br />
<input type="checkbox" name="payee[]" value="cheow"> Cheow <br />
<input type="checkbox" name="payee[]" value="p"> P <br />
<input type="checkbox" name="payee[]" value="hu"> Hu <br />
<input type="checkbox" name="payee[]" value="n1"> N1 <br />
<input type="checkbox" name="payee[]" value="xy"> XY <br />
<br />
<br />
Amount: <input type="text" name="amt">
Desription: <input type="text" name="description">
<br /><br />
Unsplit: <input type="checkbox" name="unsplit" value="unsplit">
SGD: <input type="checkbox" name="sgd" value="sgd">
<br />
<br />
<h2>Payer:</h2>
<input type="checkbox" name="payer[]" value="tube"> Tube <br />
<input type="checkbox" name="payer[]" value="cheow"> Cheow <br />
<input type="checkbox" name="payer[]" value="p"> P <br />
<input type="checkbox" name="payer[]" value="hu"> Hu <br />
<input type="checkbox" name="payer[]" value="n1"> N1 <br />
<input type="checkbox" name="payer[]" value="xy"> XY <br />
<br /><input type="checkbox" onClick="checkall(this)"> Check All <br />
<br />
<input type="submit" name="submit" value="submit">
</form>
<script type="text/javascript">
function checkall(source) {
  checkboxes = document.getElementsByName('payer[]');
  for(var i=0, n=checkboxes.length;i<n;i++) {
    checkboxes[i].checked = source.checked;
  }
}
</script>
</body>
</html>
