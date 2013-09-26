

    <div class="lib-table">
    <table> 
    <thead>
        <tr>
            <th colspan="5">Quest Completion Rate</th>
        </tr>
        <tr class="lib-row-headings">
            <th></th>
            <th>Quest</th>
            <th>Accepted</th>
            <th>Completed</th>
            <th>Completion Rate</th>
        </tr>
    </thead>

    <?php

        $result = $db->query("SELECT quests.id, quests.title, sum(quest_progresses.accepted), sum(quest_progresses.completed), sum(quest_progresses.completed) / sum(quest_progresses.accepted) * 100
            FROM quests, quest_progresses
            WHERE quest_progresses.quest_id = quests.id
            GROUP BY quests.id
            ORDER BY sum(quest_progresses.completed) / sum(quest_progresses.accepted) DESC");
        
        while($row = $result->fetch_assoc()) {

            $quest_cnt++;

            echo '
            <tr>
                <td>' . $quest_cnt . '</td>
                <td><a href=quest.php?id=' . $row['id'] . '>' . $row['title'] . '</a></td>
                <td>' . $row['sum(quest_progresses.accepted)'] . '</td>
                <td>' . $row['sum(quest_progresses.completed)'] . '</td>
                <td>' . floor($row['sum(quest_progresses.completed)'] / $row['sum(quest_progresses.accepted)'] * 100) . '%</td>
            </tr>';

        }
    ?>

</table>
</div>



