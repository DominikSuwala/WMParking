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
	$total = 0;
	foreach($allRows as $k => $v) {
		$mydata[] = array(
			"bay" => $v[0], 
			"visits" => $v[1],
			"color" => $colors[$mywmc->chooseColor2($row, $thresholds)]
			#"color" => $colors[$mywmc->chooseColor($v[1], $thresholds)]
		);
		$total += $v[1];
		$row++;
	}
	$data = json_encode($mydata);
	$html = <<<html
	<script>
		$(function () {
			$("#occupancy").text("Bay Occupancy: {$total}");
		});
	</script>
	<script>
	var chart = AmCharts.makeChart("chartdiv", {
		  "type": "serial",
		  "theme": "dark",
		  "marginRight": 70,
		  "dataProvider": {$data},
		  "valueAxes": [{
			"axisAlpha": 0,
			"position": "left",
			"title": "Occupancy"
		  }],
		  "startDuration": 0,
		  "graphs": [{
			"balloonText": "<b>[[category]]: [[value]]</b>",
			"fillColorsField": "color",
			"fillAlphas": 0.75,
			"lineAlpha": 0.2,
			"type": "column",
			"valueField": "visits"
		  }],
		  "chartCursor": {
			"categoryBalloonEnabled": false,
			"cursorAlpha": 0,
			"zoomable": false
		  },
		  "categoryField": "bay",
		  "categoryAxis": {
			"gridPosition": "start",
			"labelRotation": 45,
			"title": "Bay #"
		  },
		  "export": {
			"enabled": true
		  }

		});
	</script>
html;
	echo $html;