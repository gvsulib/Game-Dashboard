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

	$quest_id = $_GET["id"];
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

		<div class="span1 unit left">
		    <div class="lib-table">
			    <table> 
				    <thead>
				        <tr>
				            <th colspan="2">Quest Details</th>
				        </tr>
				    </thead>
				    <tbody>

				    <?php
						$result = $db->query("SELECT quests.id, quests.title, sum(quest_progresses.accepted), sum(quest_progresses.completed), sum(quest_progresses.completed) / sum(quest_progresses.accepted) * 100
				            FROM quests, quest_progresses
				            WHERE quest_progresses.quest_id = '$quest_id'
				            GROUP BY quests.id
				            ORDER BY sum(quest_progresses.completed) / sum(quest_progresses.accepted) DESC");
				        while($row = $result->fetch_assoc()) {
				            $accepted = $row['sum(quest_progresses.accepted)'];
				            $completed = $row['sum(quest_progresses.completed)'];
				            $quest_rate = floor($row['sum(quest_progresses.completed)'] / $row['sum(quest_progresses.accepted)'] * 100);
				        }

        			?>

					<?php
						$result = $db->query("SELECT * FROM quests WHERE quests.id = '$quest_id'");

						while($row = $result->fetch_assoc()) {
							echo '
					    	<tr>
					    		<td>Title</td>
								<td>' . $row['title'] . '</td>
							</tr>
							<tr>
					    		<td>Location</td>
								<td>' . $row['location'] . '</td>
							</tr>
							<tr>
					    		<td>Short Description</td>
								<td>' . $row['short_description'] . '</td>
							</tr>
							<tr>
					    		<td>Accepeted Text</td>
								<td>' . $row['acceptance_text'] . '</td>
							</tr>
							<tr>
					    		<td>Completion Text</td>
								<td>' . $row['completion_text'] . '</td>
							</tr>
							<tr>
					    		<td>Points</td>
								<td>' . $row['points'] . '</td>
							</tr>
							<tr>
					    		<td>Published</td>
								<td>' . $row['published'] . '</td>
							</tr>
							<tr>
					    		<td>Created</td>
								<td>' . $row['created_at'] . '</td>
							</tr>
							<tr>
					    		<td>End Date</td>
								<td>' . $row['end_date'] . '</td>
							</tr>
							<tr>
					    		<td>Accepted</td>
								<td>' . $accepted . '</td>
							</tr>
														<tr>
					    		<td>Completed</td>
								<td>' . $completed . '</td>
							</tr>
							<tr>
					    		<td>Completion Rate</td>
								<td>' . $quest_rate . '%</td>
							</tr>
							';

						}
					?>

					</tbody>
				</table>
			</div>	
		</div>

		<div class="span1 unit left">
		    <div class="lib-table">
			    <table> 
				    <thead>
				        <tr>
				            <th colspan="5">Challenges</th>
				        </tr>
				        <tr class="lib-row-headings">
				            <th></th>
				            <th>Challenge</th>
				            <th>Completed</th>
				            <th>Completion Rate</th>
				        </tr>
				    </thead>
				    <tbody>

					<?php

						$result = $db->query("SELECT challenges.title, cp.challenge_id, SUM(cp.completed) FROM challenge_progresses cp, challenges WHERE cp.challenge_id = challenges.id AND challenges.quest_id = '$quest_id' GROUP BY cp.challenge_id");

						while($row = $result->fetch_assoc()) {

							$ChallengeCnt++;

							echo '
                            <tr>
                                <td>' . $ChallengeCnt . '</td>
                                <td>' . $row['title'] . '</td>
                                <td>' . $row['SUM(cp.completed)'] . '</td>
                                <td>' . floor($row['SUM(cp.completed)'] / $accepted * 100) . '%</td>
                            </tr>
                            ';	

						}

					?>



					</tbody>
				</table>
			</div>	
		</div>



		<div class="line break footer">
			<div class="span1 unit break">
				<p><a href="http://gvsu.edu/library">Grand Valley State University Libraries</a>.</p>
			</div> <!-- end span -->
		</div> <!-- end line -->

	</div> <!-- end wrapper -->

</body>
</html>
