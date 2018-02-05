<?php
	require_once('admin_authentification.php');
	require_once('db_utils.php');

	$id = $_GET['id'];
	$action = $_GET['action'];
	$new_admin_comment = $_GET['admin_comment'];
	$new_status = $_GET['status'];
	$order = NULL;
	$header = 'Страница заказа';
	if ($action == 'delete') {
		delete_order_by_id($id);
	} else {
		if (isset($_GET['update_order'])) {
			update_order_by_id($id, $new_status,
					   $new_admin_comment);
		}
		$order = get_order_by_id($id);
	}
?>
<!DOCTYPE html>
<html>
<head>
	<title><?php echo($header); ?></title>
</head>
<body>
	<?php require_once('admin_header.php'); ?>

	<?php if (isset($order)) { ?>

		<b>Идентификатор: </b><?php echo($id); ?><br>
		<b>Время создания: </b><?php echo($order['open_ts']); ?><br>
		<b>Время обновления: </b><?php echo($order['update_ts']); ?><br>
		<b>Способ доставки: </b><?php
			echo($delivery_type_to_string[$order['delivery_type']]);
		?><br>
		<b>Ссылка на клиента: </b>
		<a href="user.php?id=<?php echo($order['user_id']); ?>">
			Клиент
		</a><br>
		<b>Статус: </b><?php
			echo($order_status_to_string[$order['status']]);
		?><br>
		<b>Адрес: </b><?php
			if (isset($order['address']) &&
			    strlen($order['address']) > 0)
				echo($order['address']);
		?><br>
		<b>Почта: </b><?php
			if (isset($order['email']) &&
			    strlen($order['email']) > 0)
				echo($order['email']);
		?><br>
		<b>Ссылка на ВК: </b><?php
			if (isset($order['vk_link']) &&
			    strlen($order['vk_link']) > 0)
				echo($order['vk_link']);
		?><br>
		<b>Комментарий пользователя: </b><?php
			if (isset($order['user_comment']))
				echo($order['user_comment']);
		?><br>
		<b>Комментарий администратора: </b><?php
			if (isset($order['admin_comment']))
				echo($order['admin_comment']);
		?><br>

		<table>
			<tr>
				<th>Товар</th>
				<th>Количество</th>
			</tr>
			<?php
				$pos = $order['product_orders'];
				foreach ($pos as $i => $po) {
			?>
			<tr>
				<td>
					<a href="product.php?id=
					<?php echo($po['id']); ?>">
						<?php echo($po['name']); ?>
					</a>
				</td>
				<td><?php echo($po['count']); ?></td>
			</tr>
			<?php 	} ?>
		</table><br><br>

		<form method="GET">
			<h2>Изменить заказ</h2><br>
			<label>Статус</label>
			<select name="status">
				<?php
				foreach ($order_status_to_string as
					 $value => $text) {
					if ($value == $order['status']) {
				?>
					<option selected
					 value="<?php echo($value); ?>">
						<?php echo($text); ?>
					</option>
				<?php } else { ?>
					<option value="<?php echo($value); ?>">
						<?php echo($text); ?>
					</option>
				<?php }
				} ?>
			</select><br><br>

			<label>Комментарий администратора</label><br>
			<textarea name="admin_comment" rows="10" cols="50"><?php echo($order['admin_comment']); ?></textarea><br><br>
			<input type="hidden" name="id"
			 value="<?php echo($id); ?>">
			<input type="submit" name="update_order"
			 value="Применить">
		</form><br><br>

		<a onclick="return confirm('Вы уверены?')"
		   href="order.php?action=delete&id=<?php echo($id); ?>">
			Удалить
		</a>
	<?php } else if ($action != 'delete') { ?>
		Заказ не найден
	<?php } else { ?>
		Заказ удален
	<?php } ?>
</body>
</html>
