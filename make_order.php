<?php
	/** Validate basics. */
	$delivery_type = $_POST['delivery_type'];
	if (!isset($delivery_type) ||
	    ($delivery_type != 'courier' && $delivery_type != 'customer')) {
		echo json_encode(['error' => 'Неверный тип доставки']);
		exit();
	}
	$vk_url = $_POST['vk_url'];
	if (isset($vk_url)) {
		if (strlen($vk_url) > 300 ||
		    (strpos($vk_url, 'vk.com') === false &&
		     strpos($vk_url, 'vk.ru') === false)) {
			echo json_encode(['error' => 'Некорректная ссылка на '.
						      'VK']);
			exit();
		}
	}
	$name = $_POST['name'];
	if (isset($name) && strlen($name) > 300) {
		echo json_encode(['error' => 'Имя слишком длинное']);
		exit();
	}
	$email = $_POST['email'];
	if (!isset($email) || strlen($email) > 300) {
		echo json_encode(['error' => 'Электронная почта слишком '.
					     'длинная']);
		exit();
	}
	$phone = $_POST['phone'];
	if (!isset($phone) || !preg_match("/^\d{11}$/", $phone)) {
		echo json_encode(['error' => 'Телефон должен иметь формат '.
					     '89031234567']);
		exit();
	}
	$address = $_POST['address'];
	if (isset($address) && strlen($address) > 1000) {
		echo json_encode(['error' => 'Адрес слишком длинный']);
		exit();
	}
	$comment = $_POST['comment'];
	if (isset($comment) && strlen($comment) > 2000) {
		echo json_encode(['error' => 'Комментарий слишком длинный']);
		exit();
	}

	$products = $_POST['products'];
	if (! isset($products)) {
		echo json_encode(['error' => 'Заказ не может быть пустым']);
		exit();
	}
	$products = json_decode($products, true);
	if (! isset($products)) {
		echo json_encode(['error' => 'Неверная строка товаров']);
		exit();
	}
	$counter = 0;
	foreach ($products as $i => $product) {
		if ($i != $counter) {
			echo json_encode(['error' => 'Список товаров должен '.
						     'быть массивом']);
			exit();
		}
		++$counter;
		$id = $product['id'];
		$count = $product['count'];
		if (!isset($id) || !is_int($id) || !isset($count) ||
		    !is_int($count) || $id < 0 || $count <= 0) {
			echo json_encode(['error' =>
					  'Каждый товар должен иметь '.
					  'идентификатор и положительное '.
					  'число единиц']);
			exit();
		}
	}
	if ($counter == 0) {
		echo json_encode(['error' => 'Заказ не может быть пустым']);
		exit();
	}

	/** Validate dependencies. */
	if ($delivery_type == 'courier' && !isset($address)) {
		echo json_encode(['error' => 'Для доставки курьером '.
					     'необходимо указать адрес']);
		exit();
	}

	require_once('db_utils.php');
	$order_id = create_order($phone, $vk_url, $name, $address, $email,
				 $delivery_type, $comment, $products);
	echo json_encode(['success' => true, 'order_id' => $order_id]);
?>