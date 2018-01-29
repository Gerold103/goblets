<?php
	require_once('admin_authentification.php');

	$orders_per_page = 10;
?>
<!DOCTYPE html>
<html>
<head>
	<title>Управление заказами</title>
	<style type="text/css">
		.pages {
			font-size: 20px;
		}

		.pages a {
			margin-left: 8px;
		}
	</style>
</head>
<body>
	<h2>Фильтр статуса</h2>
	<form method="GET" action="admin.php">
		<label>Статус заказа</label>
		<select name="status">
			<option value="" selected>-</option>
			<option value="new">Новый</option>
			<option value="processing">В обработке</option>
			<option value="ready">Готов к отправке</option>
			<option value="shipped">Отправлен</option>
			<option value="delivered">Доставлен</option>
			<option value="canceled">Отменен</option>
		</select><br>
		<input type="submit" value="Применить">
	</form>
	<table border="1">
		<tr>
			<th>Номер заказа</th>
			<th>Статус</th>
			<th>Время создания</th>
			<th>Время обновления</th>
			<th>Ссылка на клиента</th>
			<th>Ссылка на заказ</th>
			<th>Комментарий администратора</th>
		</tr>
<?php
		require_once('db_utils.php');
		$status = $_GET['status'];
		if (isset($status) && strlen($status) == 0)
			$status = NULL;
		$page = 1;
		if (isset($_GET['page']))
			$page = (int)$_GET['page'];
		$offset = ($page - 1) * $orders_per_page;
		$orders = select_orders($status, $offset, $orders_per_page);
		foreach ($orders as $i => $order) {
?>
		<tr>
			<td><?php echo($order['id']); ?></td>
			<td><?php
				echo($order_status_to_string[$order['status']]);
			?></td>
			<td><?php
				echo(date('d/m/y H:i', $order['open_ts']));
			?></td>
			<td><?php
				echo(date('d/m/y H:i', $order['update_ts']));
			?></td>
			<td>
				<a href="client.php?id=<?php
					echo($order['user_id']);?>">Клиент</a>
			</td>
			<td>
				<a href="order.php?id=<?php
					echo($order['id']);?>">Заказ</a>
			</td>
			<td><?php
				$comment = $order['admin_comment'];
				if (!isset($comment) || strlen($comment) == 0)
					echo('-');
				else
					echo($comment);
			?></td>
		</tr>
<?php
		}
?>
	</table>
	<div class="pages">
		<a href="#">1</a> ... <a href="#">10</a><a href="#">11</a><a href="#">12</a> ...
	</div>
</body>
</html>