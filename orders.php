<?php
	require_once('admin_authentification.php');
	require_once('db_utils.php');

	$orders_per_page = 2;
	$header = 'Управление заказами';
	$status = $_GET['status'];
	if (isset($status) && strlen($status) == 0)
		$status = NULL;
	$page = 1;
	if (isset($_GET['page']))
		$page = (int)$_GET['page'];
	$offset = ($page - 1) * $orders_per_page;
	$orders = get_orders($status, $offset, $orders_per_page + 1);
	$is_last_page = count($orders) < $orders_per_page + 1;
	if (!$is_last_page)
		array_pop($orders);
?>
<!DOCTYPE html>
<html>
<head>
	<title><?php echo($header); ?></title>
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
	<?php require_once('admin_header.php'); ?>

	<h2>Фильтр статуса</h2>
	<form method="GET">
		<label>Статус заказа</label>
		<select name="status">
			<?php if (isset($status)) { ?>
			<option value="">-</option>
			<?php } else { ?>
			<option selected value="">-</option>
			<?php }
			foreach ($order_status_to_string as
				 $value => $text) {
				if ($value == $status) {
			?>
			<option selected value="<?php echo($value); ?>">
			<?php } else { ?>
			<option value="<?php echo($value); ?>">
			<?php } echo($text); ?>
			</option>
			<?php } ?>
		</select><br>
		<input type="submit" value="Применить">
	</form><br>
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
				<a href="user.php?id=<?php
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
		<?php if ($page != 1) { ?>
			<a href="orders.php?page=1&status=<?php
			   echo($status); ?>">В начало</a><br>
			<a href="orders.php?page=<?php
			   echo($page - 1); ?>&status=<?php echo($status); ?>">
			   назад <--
			</a>
		<?php } ?>
		<?php if (!$is_last_page) { ?>
			<a href="orders.php?page=<?php
			   echo($page + 1); ?>&status=<?php echo($status); ?>">
			   --> вперед
			</a>
		<?php } ?>
	</div>
</body>
</html>