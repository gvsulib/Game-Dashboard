<div class="lib-table">
	<table>	
	<thead>
		<tr>
			<th colspan="7">Leaderboard</th>
		</tr>
		<tr class="lib-row-headings">
			<th></th>
			<?php
			if ($admin_user) {
				echo '<th>ID</th>';
			}
			?>
			<th>Player</th>
			<th>Points</th>
			<th>Prize Entries</th>
			<th>Account Created</th>
			<th></th>
		</tr>
	</thead>

	<?php

		$player_cnt = 0;

		if (!$admin_user) {

			$result = $db->query("SELECT u.id, u.user_name, ifnull(u.user_name, 'Anonymous'), SUM(q.points), u.created_at FROM users u, quests q, quest_progresses qp WHERE u.id = qp.user_id AND q.id = qp.quest_id AND qp.completed = 1 AND u.id Not in (select id from user_affil) GROUP BY u.id ORDER BY SUM(q.points) DESC");
		} else {

			$result = $db->query("SELECT u.id, u.user_name, ifnull(u.user_name, 'Anonymous'), SUM(q.points), u.created_at FROM users u, quests q, quest_progresses qp WHERE u.id = qp.user_id AND q.id = qp.quest_id AND qp.completed = 1 GROUP BY u.id ORDER BY SUM(q.points) DESC");
		}

		
	    
	    while($row = $result->fetch_assoc()) {

	    	$user_name = $row['user_name'];

	    	if (!isset($user_name)) 
	    		$user_name = 'Anonymous*';

	    	$player_cnt++;


	    	if ($player_cnt == 8) {
	    		echo '<tr>
		    			<td colspan="7" class="show-toggle" style="text-align: center; color: #00549d; text-decoration: underline; cursor: pointer">All Players With Points</td>
					</tr>';
	    		echo '</tbody>';
	    		echo '<tbody class="show-wrapper">';
	    	}

			echo '
	    	<tr>
	    		<td>' . $player_cnt . '</td>';
	    		if ($admin_user) {
	    		echo ' <td>' . $row['id'] . '</td>';
	    		}
	    		echo '
				<td>' . $user_name . '</td>
				<td>' . $row['SUM(q.points)'] . '</td>';

				$points = $row['SUM(q.points)'];
				$prize_entries = floor($points / 30);
			
				if ($prize_entries == 0) {
					$prize_entries = '';
				}

			echo '
				<td>' . $prize_entries . '</td>
				<td>' . date('m/d/y', strtotime($row['created_at'])) . '</td>';

				if ($admin_user) {

					$hide_id = $row['id'];

					$hide_result = $db->query("SELECT user_affil.affil FROM user_affil WHERE user_affil.id = '$hide_id' AND user_affil.affil = 1");
					$message = '<td><a href="index.php?userid=' . $row['id'] . '&hide=1#players">Hide</a></td>';

					while($hide_row = $hide_result->fetch_assoc()) {
						$message = '<td><a href="index.php?userid=' . $row['id'] . '&hide=0#players">Unhide</a></td>';
					}

					echo $message;


				} else {
					echo '<td></td>';
				}

			echo '</tr>';
	    }
	?>

</table>
</div>