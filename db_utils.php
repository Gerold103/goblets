<?php
	require_once('credentials.php');

	$delivery_type_to_str = [

	];

	function db_error($error) {
		echo json_encode(['error' => 'Ошибка сервера. '.
					     'Повторите позже',
				  'description' => $error]);
		exit();
	}

	$db_connection = mysqli_connect($database_host, $database_user,
					$database_password);

	if (! $db_connection->ping())
		db_error('ping error');
	$db_connection->select_db($database_name);

	function create_order($telephone, $vk_url, $name, $address, $email,
			      $delivery_type, $comment, $products) {
		global $db_connection;
		$rc = $db_connection->begin_transaction();
		if ($rc === false)
			db_error($db_connection->error);
		$stmt = $db_connection->prepare('select get_or_create_user(?, '.
						'?, ?, ?, ?)');
		if ($stmt === false)
			db_error($db_connection->error);
		$rc = $stmt->bind_param('sssss', $vk_url, $name, $address,
					$email, $telephone);
		if ($rc === false)
			db_error($stmt->error);
		$rc = $stmt->execute();
		if ($rc === false)
			db_error($stmt->error);
		$rc = $stmt->bind_result($user_id);
		if ($rc === false)
			db_error($stmt->error);
		$rc = $stmt->fetch();
		if ($rc === false)
			db_error($stmt->error);
		$stmt->close();

		$stmt = $db_connection->prepare('insert into bis_order values '.
						'(null, null, null, ?, ?, ?, '.
						'null, "new")');
		if ($stmt === false)
			db_error($db_connection->error);
		$rc = $stmt->bind_param('sis', $delivery_type, $user_id,
					$comment);
		if ($rc === false)
			db_error($stmt->error);
		$rc = $stmt->execute();
		if ($rc === false)
			db_error($stmt->error);
		$stmt->close();
		$order_id = $db_connection->insert_id;
		$query_insert_product_orders = '';
		foreach ($products as $i => $p) {
			$query = sprintf('insert into product_order values '.
					 '(NULL, %d, %d, %d); ', $p['id'],
					 $order_id, $p['count']);
			$query_insert_product_orders =
				$query_insert_product_orders.$query;
		}
		$rc = $db_connection->multi_query($query_insert_product_orders);
		if ($rc === false)
			db_error($db_connection->error);
		/* This shit is to clear buffer. PHP sucks. */
		while(mysqli_next_result($db_connection)){;}
		$rc = $db_connection->commit();
		if ($rc === false)
			db_error($db_connection->error);
		return $order_id;
	}
?>