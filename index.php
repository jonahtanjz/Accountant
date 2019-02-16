<?php
require ("db.php"); 

$current_trip = mysqli_real_escape_string($dbc, $_GET["trip"]);
$query1 = "SELECT trip_name FROM trip WHERE trip_name = '$current_trip'";
$tripexist = mysqli_query($dbc, $query1);

if (mysqli_num_rows($tripexist)>0)
{
    if ($_SERVER["REQUEST_METHOD"]=='POST')
    {
	    if ((!empty($_POST["payer"])) && (!empty($_POST["payee"])) && (!empty($_POST["amt"])) && (floatval($_POST["amt"]) != 0))
	    {
	    $trip = mysqli_real_escape_string($dbc, $_GET["trip"]);
	
	    $getTransactionsTable = "SELECT transactions FROM trip WHERE trip_name = '$trip'";
	    $TransactionsTable = mysqli_query($dbc, $getTransactionsTable);
	    $Transactions_row = mysqli_fetch_row($TransactionsTable);
	    $trip_transactions = $Transactions_row[0];
	
	    $get_currency = "SELECT currency FROM trip WHERE trip_name = '$trip'";
	    $currencydata = mysqli_query($dbc, $get_currency);
	    $c = mysqli_fetch_row($currencydata);
	    $currency = $c[0];
	
	    $payers = $_POST["payer"];
	    $amount = floatval($_POST["amt"]);
	    $amount = mysqli_real_escape_string($dbc, $amount);
	
	    if (!isset($_POST["sgd"]))
	    {
	        $amount = $amount/$currency;
	    }
	
	    $description = mysqli_real_escape_string($dbc, $_POST["description"]); 
	    $payees = $_POST["payee"];
	    $amt_each = $amount/(sizeof($payees));
	    if (!isset($_POST["unsplit"]))
    	{
	        $amt_each = ($amount/sizeof($payers))/(sizeof($payees));
	    }
	
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
	    }
	    
	    else
	    {
	        echo "Empty field(s) or 0 in amount. Now re-type.";
	    }
	    
	    die();
	
    }


    else
    {
	    $trip_name = mysqli_real_escape_string($dbc, $_GET["trip"]);
	
    	$getPeopleTable = "SELECT people FROM trip WHERE trip_name = '$trip_name'";
	    $PeopleTable = mysqli_query($dbc, $getPeopleTable);
	    $People_row = mysqli_fetch_row($PeopleTable);
	    $trip_people = $People_row[0];
	
	    $query = "SELECT name FROM `$trip_people`";
	    $people = mysqli_query($dbc, $query);
	
	    echo "<html>
	    <head><title>Accountant</title></head>
	    <body>
	    <h3><a href='view.php?trip=".rawurlencode($_GET["trip"])."'>View Ledger</a>-----<a href='select.php'>New Trip</a></h3>
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
	
	    echo "<br /><input type='checkbox' onClick='checkall(this)'> Check All<br />
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
