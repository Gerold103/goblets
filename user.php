<?php
	require_once('admin_authentification.php');
	require_once('db_utils.php');

	$telephone = $_GET['telephone'];
	$id = $_GET['id'];
	$action = $_GET['action'];
	$user = NULL;
	$header = 'Страница клиента';
	if ($action == 'delete') {
		delete_user_by_telephone($telephone);
	} elseif (isset($telephone)) {
		$user = get_user_by_telephone($telephone);
	} else {
		$user = get_user_by_id($id);
		$telephone = $user['telephone'];
	}
?>
<!DOCTYPE html>
<html>
<head>
	<title><?php echo($header); ?></title>
</head>
<body>
	<?php require_once('admin_header.php'); ?>

	<?php if (isset($user)) { ?>

		<b>Имя: </b><?php echo($user['name']); ?><br>
		<b>Телефон: </b><?php echo($telephone); ?><br>
		<b>Адрес: </b><?php
			if (isset($user['address']))
				echo($user['address']);
		?><br>
		<b>Почта: </b><?php
			if (isset($user['email']))
				echo($user['email']);
		?><br>
		<b>Ссылка на ВК: </b><?php
			if (isset($user['vk_link']))
				echo($user['vk_link']);
		?><br>
		<b>Первый заказ: </b><?php
			echo($user['first_order_ts']);
		?><br><br>

		<a onclick="return confirm('Вы уверены?')"
		   href="user.php?action=delete&telephone=<?php echo($telephone); ?>">
			Удалить
		</a>
	<?php } else if ($action != 'delete') { ?>
		Пользователь не найден
	<?php } else { ?>
		Пользователь удален
	<?php } ?>
</body>
</html>
