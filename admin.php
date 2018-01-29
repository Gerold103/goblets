<?php
	require_once('admin_authentification.php');
?>
<!DOCTYPE html>
<html>
<head>
	<title>Админка</title>
</head>
<body>
	<h1>Панель управления магазином</h1>

	<h2>Найти клиента по номеру телефона</h2>
	<form method="GET" action="user.php">
		<label>Телефон клиента</label>
		<input type="text" name="telephone"><br>
		<input type="submit" value="Найти">
	</form>

	<h2>Найти заказ по номеру</h2>
	<form method="GET" action="order.php">
		<label>Номер заказа</label>
		<input type="text" name="id"><br>
		<input type="submit" value="Найти">
	</form>

	<h2><a href="products.php">Управление товарами</a></h2>

	<h2><a href="orders.php">Управление заказами</a></h2>
</body>
</html>
