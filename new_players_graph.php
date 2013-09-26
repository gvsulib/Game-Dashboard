
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

   $endDate = date("W");
   $infoDate = $endDate - 12; // 13 dates of data


   while ($infoDate <= $endDate) {

      $result = $db->query("SELECT WEEK(users.created_at) as weekNumber, YEAR(users.created_at) as yearNumber, COUNT(users.id) as count
            FROM users
            WHERE WEEK(users.created_at) = '$infoDate'
            ORDER BY yearNumber DESC");

      while($row = $result->fetch_assoc()) {



         echo '
         <li>
           <a href="#">
            <span class="count" style="height: ' . $row['count'] / $maxCount * 100 . '%">(' . $row['count'] . ')</span>
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