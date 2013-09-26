<div class="lib-table">
	<table>	
	<thead>
		<tr>
			<th colspan="5">Leaderboard</th>
		</tr>
		<tr class="lib-row-headings">
			<th></th>
			<th>Player</th>
			<th>Points</th>
			<th>Prize Entries</th>
			<th>Account Created</th>
		</tr>
	</thead>

	<?php

		$player_cnt = 0;

		$result = $db->query("SELECT u.id, u.user_name, ifnull(u.user_name, 'Anonymous'), SUM(q.points), u.created_at FROM users u, quests q, quest_progresses qp WHERE u.id = qp.user_id AND q.id = qp.quest_id AND qp.completed = 1 GROUP BY u.id ORDER BY SUM(q.points) DESC");
	    
	    while($row = $result->fetch_assoc()) {

	    	$user_name = $row['user_name'];

	    	if (!isset($user_name)) 
	    		$user_name = 'Anonymous*';

	    	$player_cnt++;

	    	if ($player_cnt == 8) {
	    		echo '<tr>
		    			<td colspan="5" class="show-toggle" style="text-align: center; color: #00549d; text-decoration: underline; cursor: pointer">All Players With Points</td>
					</tr>';
	    		echo '</tbody>';
	    		echo '<tbody class="show-wrapper">';
	    	}

			echo '
	    	<tr>
	    		<td>' . $player_cnt . '</td>
				<td>' . $user_name . '</td>
				<td>' . $row['SUM(q.points)'] . '</td>';

				$points = $row['SUM(q.points)'];
				$prize_entries = floor($points / 30);
			
				if ($prize_entries == 0) {
					$prize_entries = '';
				}

			echo '
				<td>' . $prize_entries . '</td>
				<td>' . date('m/d/y',strtotime($row['created_at'])) . '</td>
			</tr>';
	    }
	?>

</table>
</div>