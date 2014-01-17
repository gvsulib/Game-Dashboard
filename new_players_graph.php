
<label class="title">New Players</label>

<ul class="timeline">

<?php

   $result = $db->query("SELECT MAX(count) FROM (
        SELECT WEEK(users.created_at) as weekNumber, YEAR(users.created_at) as yearNumber, COUNT(users.id) as count
            FROM users
            GROUP BY weekNumber
            ORDER BY yearNumber DESC, weekNumber
            ) as maxCount");

   while($row = $result->fetch_assoc()) {
      $maxCount = $row['MAX(count)'];
   }

   $endDateYear = date("Y");
   $endDateWeek = date("W");

   $infoDateYear = date("Y", strtotime("-13 week"));
   $infoDateWeek = date("W", strtotime("-13 week"));

   /*
   echo '<br>$endDateYear: ' . $endDateYear;
   echo '<br>$endDateWeek: ' . $endDateWeek;
   echo '<br>$infoDateYear: ' . $infoDateYear;
   echo '<br>$infoDateWeek: ' . $infoDateWeek;
   echo '<br>';
   */

   $build_graph = true;
   
   while ($build_graph == true) {

      $result = $db->query("SELECT WEEK(users.created_at) as weekNumber, YEAR(users.created_at) as yearNumber, COUNT(users.id) as count
            FROM users
            WHERE WEEK(users.created_at) = '$infoDateWeek'
            ORDER BY yearNumber DESC");

      while($row = $result->fetch_assoc()) {

         $infoDateWeek = sprintf("%02s", $infoDateWeek);

         echo '
         <li>
           <a href="#">
            <span class="count" style="height: ' . $row['count'] / $maxCount * 100 . '%">(' . $row['count'] . ')</span>
            <span class="label">' . ($row['count'] > 0 ? $row['count'] : '') . '</span>
            <span class="label-data">' . date("M d", strtotime($infoDateYear . "W" . $infoDateWeek)) . '</span>
           </a>
         </li>
         ';

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
   } 
   

   ?>

<ul>