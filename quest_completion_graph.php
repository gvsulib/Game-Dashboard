
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

   $endDateYear = date("Y");
   $endDateWeek = date("W");

   $infoDateYear = date("Y", strtotime("-13 week"));
   $infoDateWeek = date("W", strtotime("-13 week"));


   $build_graph = true;
   
   while ($build_graph == true) {

      $result = $db->query("SELECT WEEK(quest_progresses.updated_at) as weekNumber, YEAR(quest_progresses.updated_at) as yearNumber, COUNT(quest_progresses.completed) as count
            FROM quest_progresses
            WHERE quest_progresses.completed = 1 AND WEEK(quest_progresses.updated_at) = '$infoDateWeek' ORDER BY yearNumber DESC");

      while($row = $result->fetch_assoc()) {

        $infoDateWeek = sprintf("%02s", $infoDateWeek);

         echo '
         <li>
           <a href="#">
            <span class="count" style="height: ' . $row['count'] / $maxCompleted * 100 . '%">(' . $row['count'] . ')</span>
            <span class="label">' . ($row['count'] > 0 ? $row['count'] : '') . '</span>
            <span class="label-data">' . date("M d", strtotime($infoDateYear . "W" . $infoDateWeek)) . '</span>
           </a>
         </li>
         ';
      }

        if ($infoDateWeek >= 52) {
            $infoDateWeek = 1;
            $infoDateYear++;
         } else {
            $infoDateWeek++;
         }

         if ($infoDateYear >= $endDateYear && $infoDateWeek >= $endDateWeek) {
            $build_graph = false;
         }
   } 

   ?>

<ul>