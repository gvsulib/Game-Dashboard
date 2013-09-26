<style>

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

<label class="title">Quests Completed</label>

<ul class="timeline">

<?php


	$result = $db->query("SELECT MAX(count), MIN(weekNumber) FROM (
      SELECT WEEK(quest_progresses.updated_at) as weekNumber, COUNT(quest_progresses.completed) as count
         FROM quest_progresses
              WHERE quest_progresses.completed = 1
              GROUP BY weekNumber
              ORDER BY weekNUmber
      ) as maxCount");

	while($row = $result->fetch_assoc()) {
		$maxCompleted = $row['MAX(count)'];
      $minDate = $row['MIN(weekNumber)'];
	}

	$result = $db->query("SELECT WEEK(quest_progresses.updated_at) as weekNumber,
           COUNT(quest_progresses.completed) as count
       FROM quest_progresses
       WHERE quest_progresses.completed = 1
       GROUP BY weekNumber
       ORDER BY weekNumber");

   while($row = $result->fetch_assoc()) {

 		echo '
 		 <li>
	        <a href="#">
	        	<span class="count" style="height: ' . $row['count'] / $maxCompleted * 100 . '%">(' . $row['count'] . ')</span>
	        	<span class="label">' . ($row['count'] > 0 ? $row['count'] : '') . '</span>
	        	<span class="label-data">' . date("n/j", strtotime('2013W' . $row['weekNumber'])) . '</span>
	        </a>
	    </li>
 		';

   }

   ?>

<ul>