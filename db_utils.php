<?php
	require_once('credentials.php');

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
	$db_connection->set_charset("utf8");
	$db_connection->select_db($database_name);

	$order_status_to_string = [
		'new' => 'Новый',
		'processing' => 'В обработке',
		'ready' => 'Готов к отправке',
		'shipped' => 'Отправлен',
		'delivered' => 'Доставлен',
		'canceled' => 'Отменен',
	];

	$delivery_type_to_string = [
		'courier' => 'Курьер',
		'customer' => 'Самовывоз',
	];

	$product_type_to_string = [
		'goblet' => 'Кубок',
		'box' => 'Коробка',
	];

	function db_execute($sql, $param_format, ...$param_values) {
		global $db_connection;
		$stmt = $db_connection->prepare($sql);
		if ($stmt === false)
			db_error($db_connection->error);
		if ($stmt->bind_param($param_format,
				      ...$param_values) === false)
			db_error($stmt->error);
		if ($stmt->execute() === false)
			db_error($stmt->error);
		return $stmt;
	}

	function stmt_fetch_assoc($stmt) {
		$retval = [];
		$stmt_result = $stmt->get_result();
		while ($row = $stmt_result->fetch_assoc())
			array_push($retval, $row);
		return $retval;
	}

	function db_execute_and_fetch(...$params) {
		$stmt = db_execute(...$params);
		$retval = stmt_fetch_assoc($stmt);
		$stmt->close();
		return $retval;
	}


	function create_order($telephone, $vk_url, $name, $address, $email,
			      $delivery_type, $comment, $products) {
		global $db_connection;
		if ($db_connection->begin_transaction() === false)
			db_error($db_connection->error);
		$user_id =
			db_execute_and_fetch('select get_or_create_user(?, ?, '.
					     '?, ?, ?) as id', 'sssss', $vk_url,
					     $name, $address, $email,
					     $telephone)[0]['id'];

		$stmt = db_execute('insert into bis_order values (null, null, '.
				   'null, ?, ?, ?, null, "new")', 'sis',
				   $delivery_type, $user_id, $comment);
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
		if ($db_connection->commit() === false)
			db_error($db_connection->error);
		return $order_id;
	}

	function get_orders($status, $offset, $limit) {
		$query = 'select * from bis_order ';
		if (isset($status))
			$query = $query.'where status = ? ';
		$query = $query.'order by open_ts desc, id desc limit ? offset ?';
		if (isset($status)) {
			return db_execute_and_fetch($query, 'sii', $status,
						    $limit, $offset);
		} else {
			return db_execute_and_fetch($query, 'ii', $limit,
						    $offset);
		}
	}

	function get_user_by_telephone($telephone) {
		$rows = db_execute_and_fetch('select * from user where '.
					     'telephone = ?', 's', $telephone);
		if (count($rows) > 0)
			return $rows[0];
		else
			return NULL;
	}

	function get_user_by_id($id) {
		$rows = db_execute_and_fetch('select * from user where id = ?',
					     'i', $id);
		if (count($rows) > 0)
			return $rows[0];
		else
			return NULL;
	}

	function delete_user_by_telephone($telephone) {
		$stmt = db_execute('delete from user where telephone = ?', 's',
				   $telephone);
		$stmt->close();
	}

	function get_order_by_id($id) {
		$orders = db_execute_and_fetch('select * from bis_order where '.
					       'id = ?', 'i', $id);
		if (count($orders) == 0)
			return NULL;
		$order = $orders[0];
		$order['product_orders'] =
			db_execute_and_fetch('select product_id as id, count, '.
					     'name from product_order join '.
					     'product on product_id = '.
					     'product.id where order_id = ?',
					     'i', $id);
		return $order;
	}

	function delete_order_by_id($id) {
		$stmt = db_execute('delete from bis_order where id = ?', 'i',
				   $id);
		$stmt->close();
	}

	function update_order_by_id($id, $new_status, $new_admin_comment) {
		$stmt = db_execute('update bis_order set status = ?, '.
				   'admin_comment = ? where id = ?', 'ssi',
				   $new_status, $new_admin_comment, $id);
		$stmt->close();
	}

	function get_product_by_id($id) {
		return db_execute_and_fetch('select * from product where '.
					    'id = ?', 'i', $id)[0];
	}

	function update_product_by_id($id, $new_count, $new_price) {
		$stmt = db_execute('update product set available_count = ?, '.
				   'price = ? where id = ?', 'iii', $new_count,
				   $new_price, $id);
		$stmt->close();
	}

	function get_products() {
		return db_execute_and_fetch('select * from product', '');
	}
?>