<?php
	require_once('wmc.php');
	
	$host         = 'localhost';
	$username     = 'root';
	$password     = '';
	$dbname       = 'bays';
	$result       = 0;

	/*Create connection */
	$conn = new mysqli($host, $username, $password, $dbname);
	
	/*Check connection */
	if ($conn->connect_error) {
		 die("Connection to database failed: " . $conn->connect_error);
	} 
	
	$mywmc = new \wmc();
	$queryResult = mysqli_query($conn,$mywmc->getCounts());
	$allRows = mysqli_fetch_all($queryResult);
	$max = $allRows[0][1];
	$numRows = sizeof($allRows);
	$thresholds = array();
	$thresholds[] = 0.8 * $numRows;
	$thresholds[] = 0.7 * $numRows;
	$thresholds[] = 0.25 * $numRows;
	
	$colors = array(
		'#00FF00',
		'#FFE303',
		'#FFA500',
		'#BB0000'
	);
	$mydata = array();
	$row = 0;
	foreach($allRows as $k => $v) {
		$mydata[] = array(
			"bay" => $v[0], 
			"visits" => $v[1],
			"color" => $colors[$mywmc->chooseColor2($row, $thresholds)]
			#"color" => $colors[$mywmc->chooseColor($v[1], $thresholds)]
		);
		$row++;
	}
	
	echo $mywmc->htmlpage(json_encode($mydata));