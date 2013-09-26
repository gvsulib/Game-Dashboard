
<label class="title">Quest Completions</label>

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

   $endDate = date("W");
   $infoDate = $endDate - 12; // 13 dates of data

   while ($infoDate <= $endDate) {

      $result = $db->query("SELECT WEEK(quest_progresses.updated_at) as weekNumber, YEAR(quest_progresses.updated_at) as yearNumber, COUNT(quest_progresses.completed) as count
            FROM quest_progresses
            WHERE quest_progresses.completed = 1 AND WEEK(quest_progresses.updated_at) = '$infoDate' ORDER BY yearNumber DESC");

      while($row = $result->fetch_assoc()) {

         echo '
         <li>
           <a href="#">
            <span class="count" style="height: ' . $row['count'] / $maxCompleted * 100 . '%">(' . $row['count'] . ')</span>
            <span class="label">' . ($row['count'] > 0 ? $row['count'] : '') . '</span>
            <span class="label-data">' . date("n/j", strtotime('2013W' . $infoDate)) . '</span>
           </a>
         </li>
         ';
      }

      $infoDate++;
   } 

   ?>

<ul>