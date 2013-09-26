<!DOCTYPE HTML>

<?php
	include 'resources/secret/config.php';
	$db = new mysqli($db_host, $db_user, $db_pass, $db_database);
	if ($db->connect_errno) {
    	printf("Connect failed: %s\n", $db->connect_error);
    	exit();
	}

	if ($db->connect_errno) {
    	printf("Connect failed: %s\n", $db->connect_error);
    	exit();
	}

	date_default_timezone_set('America/Detroit');

	ini_set("log_errors", 1);
ini_set("error_log", "php-error.log");
?>

<html>
	<head>
	<title>Library Quest Dashboard</title>

	<link rel="stylesheet" type="text/css" href="resources/css/styles.css"/>
	<link rel="stylesheet" type="text/css" href="http://gvsu.edu/cms3/assets/741ECAAE-BD54-A816-71DAF591D1D7955C/libui.css" />
	<meta name="viewport" content="width=device-width, initial-scale=1">

	</head>
<body>

<style>

/* CHART LISTS */
.chartlist { 
float: left; 
border: 1px solid #666; 
width: 100%;
height: 1.8em;
}
.chartlist li { 
position: relative;
display: block;  
_zoom: 1;
}
.chartlist li { 
display: block; 
padding: 0.4em 4.5em 0.4em 0.5em;
position: relative; 
z-index: 2; 
}
.chartlist .count { 
display: block; 
position: absolute; 
top: 0; 
right: 0; 
margin: 0 0.3em; 
text-align: left; 
color: #333; 
font-weight: bold; 
font-size: 0.875em; 
line-height: 2em; 
}
.chartlist .index { 
display: block; 
position: absolute; 
top: 0; 
left: 0; 
height: 100%; 
background: #fb694b; 
text-indent: -9999px; 
overflow: hidden; 
line-height: 2em;
height: 1.8em;
}

label {
font-size: .8em;
margin-bottom: .5em;
color: #333;
font-weight: bold;
text-transform: uppercase;
}

.lib-row-headings th {
font-size: .8em !important;
margin-bottom: .5em;
color: #333;
font-weight: bold;
text-transform: uppercase;
}

.data {
font-size: 2.5em;
color: #333;
text-align: center;
}

.small {
font-size: 100%;
}

.big {
font-size: 5.5em;
}

.quest-percent {
	font-size: 3em;
	color: #333;
	padding-right: .2em;
}

.quest-title {
	line-height: 3em;
	color: #333;
}

body {
font-family: Arial, Helvetica, sans-serif;
}



/* TIMELINE CHARTS */
    .timeline { 
      font-size: 0.75em; 
      height: 10em; 
      width: 53em;
    }
    .timeline li { 
      position: relative;
      float: left;
      width: 14%; 
      margin: 0 0.1em;
      height: 8em; 
    }
    .timeline li a { 
      display: block;
      height: 100%;  
    }
    .timeline li .label { 
      display: block; 
      position: absolute; 
      bottom: -2em; 
      left: 0; 
      background: #fff; 
      width: 100%; 
      height: 2em; 
      line-height: 2em; 
      text-align: center;
    }
    .timeline li a .count { 
      display: block; 
      position: absolute; 
      bottom: 0; 
      left: 0; 
      height: 0; 
      width: 100%; 
      background: #ccc; 
      text-indent: -9999px; 
      overflow: hidden; 
    }
    .timeline li:hover { 
      background: #EFEFEF; 
    }
    .timeline li a:hover .count { 
      background: #2D7BB2; 
    }



</style>

<body>

	<div id="gvsu-header-wrapper">
		<div id="gvsu-header">
			<div id="gvsu-logo">
				<a href="http://www.gvsu.edu/">
					<img style="margin: .5em;"class = "logo-img"src="http://www.gvsu.edu/homepage/files/img/gvsu_logo.png" alt="Grand Valley State University" border="0">
				</a>
			</div>
		</div>
	</div>

	<div id="wrapper">

		<div class="line break">
			<div class="span2of3 unit left">
				<h2><a href="index.php">Library Quest Dashboard</a></h2>
			</div> <!-- end span -->
		</div> <!-- end line -->

		<?php 

			include 'data.php';
		?>


		<div>

			<div class="left unit span2">

				<div class="left unit span1">
					<label class="title">Quests Started</label>
					<br>
					<span class="data big"><?php echo $num_of_quests_started ?></span>
					<span class="data small">(<?php echo $num_of_quests_completed ?> completed)</span>

				</div>

				<div class="left unit span1">
					<ul class="chartlist">
						<li>
							<span class="count"><?php echo $quest_percent ?>%</span>
							<span class="index" <?php echo 'style="width: ' . $quest_percent . '%"' ?>></span>
						</li>
					</ul>
				</div>

			</div>

			<div class="left unit span2">

				<div class="left unit span1">
					<label class="title">Total Players</label>
					<br>
					<span class="data big"><?php echo $num_of_players ?></span>
					<span class="data small">(<?php echo $active_players ?> with points)</span>
				</div>

				<div class="left unit span1">
					<ul class="chartlist">
						<li>
							<span class="count"><?php echo $player_percent ?>%</span>
							<span class="index" <?php echo 'style="width: ' . $player_percent . '%"' ?>></span>
						</li>
					</ul>
				</div>

			</div>

		</div>


		<div class="left unit span1">

		<ul class="timeline">

		<?php


		$cntLargest = 0;

		$result = $db->query("SELECT u.id, u.user_name, u.created_at FROM users u ORDER BY u.created_at");

		while($row = $result->fetch_assoc()) {

			$thisDate = date('WY',strtotime($row['created_at']));

			if ($thisDate != $previousDate) {
				if ($cntDate > $cntLargest) {
					$cntLargest = $cntDate;
				}
				$cntDate = 0;
			} else {
				$cntDate++;
			}
			$previousDate = $thisDate;
		}

		$cntDate = 0;

		$result = $db->query("SELECT u.id, u.user_name, u.created_at FROM users u ORDER BY u.created_at");

	    while($row = $result->fetch_assoc()) {

	    	$thisDate = date('WY',strtotime($row['created_at']));

	    	if ($thisDate != $previousDate) {

	    		echo '
	    		 <li>
			        <a href="#">
			        	<span class="label">' . $cntDate . '</span>
			        	<span class="count" style="height: ' . $cntDate / $cntLargest * 100 . '%">(' . $cntDate . ')</span>
			        </a>
			    </li>
	    		';
	    		$cntDate = 0;
	    	} else {
	    		

	    		$cntDate++;
	    	}

	    	$previousDate = $thisDate;

	    }

	    ?>

	    <ul>

		</div>


	<div class="line break footer">
		<div class="span1 unit break">
			<p><a href="http://gvsu.edu/library">Grand Valley State University Libraries</a>.</p>
		</div> <!-- end span -->
	</div> <!-- end line -->

	</div> <!-- end wrapper -->
<script src="//ajax.googleapis.com/ajax/libs/jquery/2.0.0/jquery.min.js"></script>
<script>

	$(document).ready(function() {

		// Hide the items you don't want to show if JS is available
		$(".show-wrapper").hide();

		// Make the div toggle visible/invisible on click
		$(".show-toggle").click(function() {

			$(".show-wrapper").slideToggle(400);

		});

	});

</script>

</body>
</html>
