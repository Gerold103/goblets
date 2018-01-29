<?php
	require_once('admin_authentification.php');
	require_once('db_utils.php');

	$id = $_GET['id'];
	$header = 'Страница продукта';
	if (isset($_GET['update'])) {
		update_product_by_id($id, $_GET['new_count'],
				     $_GET['new_price']);
	}
	$product = get_product_by_id($id);
?>
<!DOCTYPE html>
<html>
<head>
	<title>Страница продукта</title>
</head>
<body>
	<?php require_once('admin_header.php'); ?>
	<b>Название: </b><?php echo($product['name']); ?><br>
	<b>Тип: </b>
	<?php echo($product_type_to_string[$product['type']]); ?><br>
	<b>Цена: </b><?php echo($product['price']); ?>₽<br>
	<b>Доступно штук: </b><?php echo($product['available_count']); ?><br>
	<b>Размеры: </b><?php echo($product['size']); ?><br>
	<b>Емкость: </b><?php echo($product['capacity']); ?><br><br>
	<form method='GET'>
		<label>Цена: </label>
		<input type="text" name="new_price"
		 value="<?php echo($product['price']); ?>"><br>

		<label>Количество: </label>
		<input type="text" name="new_count"
		 value="<?php echo($product['available_count']); ?>"><br><br>

		<input type="hidden" name="id" value="<?php echo($id); ?>">
		<input type="submit" name="update" value="Применить">
	</form>
</body>
</html>
