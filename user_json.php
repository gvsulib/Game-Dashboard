<?php

	include 'resources/secret/config.php';
	$db = new mysqli($db_host, $db_user, $db_pass, $db_database);

	if ($db->connect_errno) {
    	printf("Connect failed: %s\n", $db->connect_error);
    	exit();
	}

	$sth = $db->query("SELECT created_at FROM users ORDER BY created_at");
	$rows = array();
	$rows['name'] = 'created_at';

	$rows1 = array();
	$rows1['name'] = 'num_of_users';
	$num_of_users = 0;

	while($r = $sth->fetch_assoc()) {
		$num_of_users++;
	    $rows['data'][] = $r['created_at'];
	    $rows1['data'][] = $num_of_users;
	}

	$result = array();
	array_push($result,$rows1);
	array_push($result,$rows);
	print json_encode($result, JSON_NUMERIC_CHECK);


?>