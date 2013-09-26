<?php

$num_of_players = 0;
$active_players = 0;
$num_of_quests_completed = 0;
$num_of_quests_started = 0;

$result = $db->query("SELECT * FROM users ORDER BY created_at");
while($row = $result->fetch_assoc()) {
    $num_of_players++;
}

$result = $db->query("SELECT u.id FROM users u, quests q, quest_progresses qp WHERE u.id = qp.user_id AND q.id = qp.quest_id AND qp.completed = 1 GROUP BY u.id ORDER BY SUM(q.points) DESC");
while($row = $result->fetch_assoc()) {
    $active_players++;
}

$result = $db->query("SELECT * FROM quest_progresses qp");
while($row = $result->fetch_assoc()) {
    $num_of_quests_started++;
}

$result = $db->query("SELECT * FROM quest_progresses qp WHERE qp.completed = 1");
while($row = $result->fetch_assoc()) {
    $num_of_quests_completed++;
}

$quest_percent = floor($num_of_quests_completed/$num_of_quests_started * 100);
$player_percent = floor($active_players/$num_of_players * 100);