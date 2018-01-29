<?php
	require_once('admin_authentification.php');
	require_once('db_utils.php');

	$header = 'Страница товаров';
	$products = get_products();
?>
<!DOCTYPE html>
<html>
<head>
	<title><?php echo($header); ?></title>
</head>
<body>
	<?php require_once('admin_header.php'); ?>

	<table border="1">
		<tr>
			<th>Название</th>
			<th>Цена</th>
			<th>Доступное количество</th>
		</tr>
		<?php
			foreach ($products as $i => $product) {
		?>
			<tr>
				<td>
					<a href="product.php?id=<?php
					   echo($product['id']);
					?>">
						<?php echo($product['name']); ?>
					</a>
				</td>
				<td><?php echo($product['price']); ?></td>
				<td><?php
					echo($product['available_count']);
				?></td>
			</tr>
		<?php
			}
		?>
	</table>
</body>
</html>
