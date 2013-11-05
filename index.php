<?php
	session_start();
	$_SESSION['location'] = 'http://' . $_SERVER['SERVER_NAME'] . "/game-dashboard/index.php";

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

	error_reporting(0); // turn off error reporting


	if (isset($_SESSION['username']) == false) { // No $_SESSION['username'] variable, send to login script

		header('Location: http://labs.library.gvsu.edu/login');

	} else { // user has logged in

		$session_user = $_SESSION['username'];

		$session_user = 'felkerk'; // ONLY FOR TESTING ADMIN RIGHTS


		// Checking for admin users. 
		// Admin users can hide players on the user table, 
		$admin_user = 0;
		if ($session_user == 'felkerk' OR $session_user == 'earleyj' OR $session_user == 'reidsmam') {
			$admin_user = 1;

			$hide_userid = $db->real_escape_string($_GET['userid']);

			// hide user
			if ($_GET['hide'] == 1) {	

				$result = $db->query("SELECT * FROM user_affil Where user_affil.id = '$hide_userid'");

				$row_cnt = $result->num_rows;

				if ($row_cnt == 0) {
					$db->query("INSERT INTO user_affil VALUES ('$hide_userid', '1')");
				} else {
					$db->query("UPDATE user_affil SET user_affil.affil = 1 WHERE user_affil.id = '$hide_userid'");
				}

			} else {
				$db->query("UPDATE user_affil SET affil = 0 WHERE user_affil.id = '$hide_userid'");
			}
			
		}

?>
<!DOCTYPE HTML>
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
      padding-bottom: 6em;
    }
    .timeline li { 
      position: relative;
      float: left;
      width: 6%;
      margin: 0 .848%;
      height: 12em; 
    }
    .timeline li a { 
      display: block;
      height: 100%;  
    }
    .timeline li .label { 
      display: block; 
      position: absolute; 
      bottom: -.2em; 
      left: 0; 
      width: 100%; 
      height: .2em; 
      line-height: 2em; 
      text-align: center;
    }
      .timeline li .label-data { 
      display: block; 
      position: absolute; 
      bottom: -1.4em; 
      left: 0; 
      width: 100%; 
      height: .2em; 
      line-height: 2em; 
      text-align: center;
      color: #333;
      font-size: 1.2em;
    }
    .timeline li a .count { 
      display: block; 
      position: absolute; 
      bottom: 0; 
      left: 0; 
      height: 0; 
      width: 100%; 
      background: #88B3DA; 
      text-indent: -9999px; 
      overflow: hidden; 
    }
    .timeline li:hover { 
      background: #EFEFEF; 
    }
    .timeline li a:hover .count { 
      background: #fb694b; 
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


		<div >

			<div class="left unit span2">

				<div>
					<label class="title">Quests Started</label>
					<br>
					<span class="data big"><?php echo $num_of_quests_started ?></span>
					<span class="data small">(<?php echo $num_of_quests_completed ?> completed)</span>

				</div>

				<div>
					<ul class="chartlist">
						<li>
							<span class="count"><?php echo $quest_percent ?>%</span>
							<span class="index" <?php echo 'style="width: ' . $quest_percent . '%"' ?>></span>
						</li>
					</ul>
				</div>

			</div>

			<div class="left unit span2">

				<div>
					<label class="title">Total Players</label>
					<br>
					<span class="data big"><?php echo $num_of_players ?></span>
					<span class="data small">(<?php echo $active_players ?> with points)</span>
				</div>

				<div>
					<ul class="chartlist">
						<li>
							<span class="count"><?php echo $player_percent ?>%</span>
							<span class="index" <?php echo 'style="width: ' . $player_percent . '%"' ?>></span>
						</li>
					</ul>
				</div>

			</div>

		</div>

		<div class="left unit span1" style="padding-top: 1em">
			<?php include 'new_players_graph.php'; ?>
		</div>

		<div class="left unit span1" style="padding-top: 1em">
			<?php include 'quest_completion_graph.php'; ?>
		</div>

		<div class="left unit span1" style="padding-top: 1em">
			<?php include 'quest_table.php'; ?>
		</div>
	
		<div class="left unit span1" style="padding-top: 1em" id="players">
			<?php include 'user_table.php'; ?>
		</div>

	<div class="line break footer">
		<div class="span1 unit break">
			<p>Written by <a href="http://jonearley.net/">Jon Earley</a> for <a href="http://gvsu.edu/library">Grand Valley State University Libraries</a>. Code is <a href="https://github.com/gvsulib/game-dashboard">available on Github</a>.</p>
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

<?php

} // close cas login check

?>

</body>
</html>
