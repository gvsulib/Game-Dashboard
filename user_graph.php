

<label class="title">New Players</label>

<ul class="timeline">

<?php

	// SELECT users.id, users.user_name, users.created_at FROM users GROUP BY DATE_FORMAT(users.created_at, "%u")


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
			        	<span class="count" style="height: ' . $cntDate / $cntLargest * 100 . '%">(' . $cntDate . ')</span>
			        	<span class="label">' . ($cntDate > 0 ? $cntDate : '') . '</span>
			        	<span class="label-data">' . date('n/j',strtotime($row['created_at'])) . '</span>
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