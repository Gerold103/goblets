var products = [
	{
		id: 0,
		price: 1200,
		image: 'img/goblet_icon_1.png',
		icon: 'img/goblet_1.png',
		name: 'Дракон',
		count: 0,
		capacity: 200,
		size: '8х8х18см',
		description: 'Quisque sed consectetur ex, a rutrum erat.' +
			     'Pellentesque ipsum turpis, posuere eget' +
			     'consequat nec.',
	},
	{
		id: 1,
		price: 1300,
		image: 'img/goblet_icon_2.png',
		icon: 'img/goblet_2.png',
		name: 'Рука скелета',
		count: 0,
		capacity: 200,
		size: '8х8х18см',
		description: 'Quisque sed consectetur ex, a rutrum erat.' +
			     'Pellentesque ipsum turpis, posuere eget' +
			     'consequat nec.',
	},
	{
		id: 2,
		price: 1100,
		image: 'img/goblet_icon_3.png',
		icon: 'img/goblet_3.png',
		name: 'Череп',
		count: 0,
		capacity: 200,
		size: '8х8х18см',
		description: 'Quisque sed consectetur ex, a rutrum erat.' +
			     'Pellentesque ipsum turpis, posuere eget' +
			     'consequat nec.',
	}
];

var total_count = 0;
var total_price = 0;
var discount = 0;
var delivery_type = null;

var busket_icon = new Image(25, 25);
busket_icon.src = 'img/basket.png';
busket_icon.setAttribute('alt', 'Корзина');

function slow_move_to(id) {
	$('html, body').animate({scrollTop: $(id).offset().top}, 800);
	return false;
}

function on_anchor_click(e) {
	return slow_move_to($(e).attr('href'));
}

function update_order_details() {
	total_price = 0;
	total_count = 0;
	for (var i = 0; i < products.length; ++i) {
		var p = products[i]
		total_price += p.count * p.price;
		total_count += p.count;
	}
	discount = 0;
	var vk_flag = document.getElementById("vk_flag");
	if (vk_flag.checked) {
		$("#vk_link_input").fadeIn(500);
		discount = 5;
	} else {
		$("#vk_link_input").fadeOut(500);
	}
	var selected_delivery = $("input[name=delivery_type]:checked");
	if (selected_delivery.length == 0) {
		delivery_type = null;
	} else {
		delivery_type = selected_delivery.val();
		if (delivery_type == 'courier') {
			total_price += 250;
			$("#address_input").fadeIn(500);
		} else {
			$("#address_input").fadeOut(500);
		}
	}
}

function add_product_to_busket(product_id) {
	++products[product_id].count;
	update_busket();
}

function delete_product_from_busket(product_id) {
	--products[product_id].count;
	update_busket();
}

function product_dom_for_order(product) {
	var tr = document.createElement('tr');
	tr.className = 'product_set';

	var td_icon = document.createElement('td');
	var icon = document.createElement('img');
	icon.setAttribute('src', product.icon);
	td_icon.appendChild(icon);

	var td_name = document.createElement('td');
	td_name.className = 'product_title';
	td_name.textContent = product.name;

	var td_price = document.createElement('td');
	td_price.className = 'price';
	td_price.textContent = product.price + '₽ х ' + product.count +
			       ' = ' + product.count * product.price + '₽';

	var td_add = document.createElement('td');
	var add_button = document.createElement('button');
	add_button.className = 'add_button';
	add_button.textContent = '+';
	add_button.onclick = function() {
		add_product_to_busket(product.id);
	};
	td_add.appendChild(add_button);

	var td_delete = document.createElement('td');
	var delete_btn = document.createElement('button');
	delete_btn.className = 'delete_button';
	delete_btn.textContent = '-';
	delete_btn.onclick = function() {
		delete_product_from_busket(product.id);
	};
	td_delete.appendChild(delete_btn);

	tr.appendChild(td_icon);
	tr.appendChild(td_name);
	tr.appendChild(td_price);
	tr.appendChild(td_add);
	tr.appendChild(td_delete);
	return tr;
}

function product_dom_for_catalogue(product) {
	var div = document.createElement('div');
	div.setAttribute('align', 'center');
	div.className = 'product_item';

	var img = new Image(250, 250);
	img.src = product.image;
	img.style.display = 'block';
	img.setAttribute('alt', product.name);

	var title = document.createElement('div');
	title.className = 'product_title';
	title.textContent = product.name;

	var hr = document.createElement('hr');

	var description = document.createElement('p');
	description.textContent = product.description;

	var price = document.createElement('div');
	price.className = 'price';
	price.textContent = product.price + '₽';

	var size_div = document.createElement('div');
	var u = document.createElement('u');
	u.textContent = 'Размеры:';
	var size = document.createTextNode(' ' + product.size);
	size_div.appendChild(u);
	size_div.appendChild(size);

	var capacity_div;
	if (product.capacity != null) {
		capacity_div = document.createElement('div');
		u = document.createElement('u');
		u.textContent = 'Объем:';
		var capacity =
			document.createTextNode(' ' + product.capacity + 'мл');
		capacity_div.appendChild(u);
		capacity_div.appendChild(capacity);
	} else {
		capacity_div = null;
	}

	var ok_msg = document.createElement('div');
	ok_msg.className = 'ok_msg';
	ok_msg.textContent = 'Добавлено!';

	var button = document.createElement('button');
	button.className = 'wood_button';
	button.onclick = function() { put_in_busket(product.id, ok_msg); };
	var text = document.createTextNode('В корзину');

	button.appendChild(text);
	button.appendChild(busket_icon);
	div.appendChild(img);
	div.appendChild(title);
	div.appendChild(hr);
	div.appendChild(description);
	div.appendChild(price);
	div.appendChild(size_div);
	if (capacity_div != null)
		div.appendChild(capacity_div)
	div.appendChild(button);
	div.appendChild(ok_msg);
	return div;
}

function update_busket() {
	var delivery_was_not_selected = delivery_type == null;
	update_order_details();

	var b = $("#busket");
	var product_word;
	var price_with_discount =
		Math.ceil(total_price * (100 - discount) / 100);
	if (total_count == 0) {
		b.fadeOut(500);
	} else {
		/* Update busket popup window. */
		if (total_count >= 5 && total_count <= 20)
			product_word = "товаров";
		else if (total_count % 10 == 1)
			product_word = "товар";
		else if (total_count % 10 >= 2 && total_count % 10 <= 4)
			product_word = "товара";
		else
			product_word = "товаров";
		var html = "<u>Ваш заказ:</u><br><b>" + total_count +
			   " " + product_word + "</b>, " + price_with_discount +
			   "₽<br><a id=\"go_to_order_section\" " +
			   "href=\"#order_section\" " +
			   "onclick=\"return on_anchor_click(this);\">" +
			   "Корзина</a>";
		b.html(html);
		b.fadeIn(500);
	}

	/* Update ordering area. */
	var items = document.getElementById('order_items');
	/* Clear old items. */
	while (items.firstChild)
		items.removeChild(items.firstChild);
	for (var i = 0; i < products.length; ++i) {
		var product = products[i];
		if (product.count > 0) {
			var dom = product_dom_for_order(product)
			items.appendChild(dom);
		}
	}

	var result = document.getElementById("order_result");
	if (total_count == 0)
		product_word = 'товаров';
	var html = '<b>' + total_count + '</b> ' + product_word + '<br>';
	if (total_count > 0) {
		html += '<b>' + total_price + '₽';
		if (discount > 0) {
			html += ' - <span class="red_text">' + discount +
				'%</span> = ' + price_with_discount + '₽';
		}
		html += '</b><br>'
	}
	result.innerHTML = html;

	/* Update form. */
	var order_form = document.getElementById("order_form");
	if (total_count == 0) {
		$(order_form).slideUp(700);
		return;
	}
	var customer = document.getElementById("customer");
	if (delivery_type == null)
		$(customer).fadeOut(500);
	else
		$(customer).fadeIn(500);
	$(order_form).slideDown(700);
	if (delivery_was_not_selected && delivery_type != null)
		slow_move_to('#customer');
}

function put_in_busket(product_id, ok_msg) {
	ok_msg = $(ok_msg);
	ok_msg.fadeIn(1000, function() { ok_msg.fadeOut(1000); });
	add_product_to_busket(product_id);
}

function show_form_error(message) {
	var error = document.getElementById('error_message');
	error.innerText = message;
	$(error).fadeIn(500);
}

function hide_error() {
	var error = document.getElementById('error_message');
	$(error).fadeOut(500);
}

function validate_email(email) {
	var re = /^(([^<>()\[\]\\.,;:\s@"]+(\.[^<>()\[\]\\.,;:\s@"]+)*)|(".+"))@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\])|(([a-zA-Z\-0-9]+\.)+[a-zA-Z]{2,}))$/;
	return re.test(email.toLowerCase());
}

function validate_phone(phone) {
	var re = /^\d{11}$/;
	return re.test(phone);
}

function sumbit_async() {
	/* Validate and send via AJAX. */
	if (delivery_type == null) {
		show_form_error('Укажите способ доставки');
		return;
	}
	var vk_flag = document.getElementById("vk_flag").checked;
	if (vk_flag) {
		var vk_url = $("#vk_link_input").find('input[name="vk"]');
		vk_url = vk_url.val();
		if (vk_url.indexOf('vk.com') == -1 &&
		    vk_url.indexOf('vk.ru') == -1) {
			show_form_error('Неверная ссылка на страницу ВК');
			return;
		}
	}
	var email = $("#email_input").find('input[name="email"]').val().trim();
	if (email.length > 0 && !validate_email(email)) {
		show_form_error('Неверная почта');
		return;
	}
	var phone = $("#phone_input").find('input[name="phone"]').val().trim();
	if (!validate_phone(phone)) {
		show_form_error('Неверный телефон - используйте формат ' +
				'"89031234567"');
		return;
	}
	if (delivery_type == 'courier') {
		var address = $("#address_input").find('input[name="address"]');
		address = address.val().trim();
		if (address.length == 0) {
			show_form_error('Для курьреской доставки нужно ' +
					'указать адрес')
			return;
		}
	}
	hide_error();
}

function initial_push_products_to_dom() {
	var catalogue = document.getElementById('catalogue');
	while (catalogue.firstChild)
		catalogue.removeChild(catalogue.firstChild);
	for (var i = 0; i < products.length; ++i)
		catalogue.appendChild(product_dom_for_catalogue(products[i]));
}

$(document).ready(initial_push_products_to_dom);
