<?php

	$dbc = mysqli_connect('127.0.0.1','USERNAME','PASSWORD','DATABASE_NAME')
	OR die (mysqli_connect_error());
	mysqli_set_charset($dbc,'utf8');
	
?>