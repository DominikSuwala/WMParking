<?php
	
	class wmc {
		
		function __construct() {
			$this->kafka_host = '';
			$this->kafka_port = '';
			$this->kafka_client = null;
		}
		function htmlpage($data) {
			return <<<html
	<html>
	<title>White Marigold Capital Technical - Car Bays</title>
	<head>
		<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script>
		<!-- Styles -->
		<style>
		body { background-color: #30303d; color: #fff; }
		#chartdiv {
		  width: 100%;
		  height: 500px;
		}

		.amcharts-export-menu-top-right {
		  top: 10px;
		  right: 0;
		}
		</style>
		
		<!-- Resources -->
		<script src="https://www.amcharts.com/lib/3/amcharts.js"></script>
		<script src="https://www.amcharts.com/lib/3/serial.js"></script>
		<script src="https://www.amcharts.com/lib/3/plugins/export/export.min.js"></script>
		<link rel="stylesheet" href="https://www.amcharts.com/lib/3/plugins/export/export.css" type="text/css" media="all" />
		<script src="https://www.amcharts.com/lib/3/themes/dark.js"></script>
		
	</head>
		
	<body>
		<!-- Chart code -->
		<script>
			$(document).ready(function () {
				setInterval(function() {
					$.get("chart_refresh.php", function (result) {
						$('#chartdiv').html(result);
						
					});
				}, 1200);
			});
		</script>

	<!-- HTML -->
	<div id="refreshchart" align="center" >
		<h1>Bay Occupancy</h1>
			<div style="width:50%;" id="chartdiv">
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
		</div>
	</div>
	</body>
	
	</html>
	
html;
		}
		function chooseColor($value, $thresholds) {
			$best = 0;
			for($i = 0; $i < sizeof($thresholds); $i++) {
				if($value > $thresholds[$i]) {
					$best = $i;
				}
				else {
					return $best;
				}
			}
			return $best;
		}
		function chooseColor2($n, $thresholds) {
			$i = 0;
			foreach($thresholds as $threshold) {
				if($n > $threshold) {
					return $i;
				}
				$i++;
			}
			return $i;
		}
		function getCounts() {
			return <<<sql
			SELECT `bay_id`, COUNT(*) AS COUNT
			FROM `tbl_bay_entry`
			WHERE `bay_id` > 0
			GROUP BY `bay_id` ORDER BY `count` DESC;
sql;
		}
		
	}