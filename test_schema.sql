use goblets;

/** Insert users and orders for testing. */
begin;
insert into user values (NULL, NULL, 'vk.com/user1', 'name1', 'address1',
			 'e1@mail.ru', '89031234567', NULL);
insert into user values (NULL, NULL, 'vk.com/user2', 'name2', 'address2',
			 'e2@mail.ru', '89031234568', NULL);
insert into user values (NULL, NULL, 'vk.com/user3', 'name3', 'address3',
			 'e3@mail.ru', '89031234569', NULL);
insert into user values (NULL, NULL, 'vk.com/user4', 'name4', 'address4',
			 'e4@mail.ru', '89031234570', NULL);
commit;

begin;
insert into bis_order values (NULL, NULL, NULL, 'courier', 1, 'user comment 1',
			      'admin comment 1', 'new', 'address5',
			      'e5@mail.ru', 'vk.com/user5');
set @order_id = last_insert_id();
insert into product_order values (NULL, 0, @order_id, 1);
insert into product_order values (NULL, 1, @order_id, 2);
insert into product_order values (NULL, 2, @order_id, 3);
commit;

begin;
insert into bis_order values (NULL, NULL, NULL, 'customer', 2, 'user comment 2',
			      '', 'processing', 'address6',
			      'e6@mail.ru', 'vk.com/user6');
set @order_id = last_insert_id();
insert into product_order values (NULL, 0, @order_id, 1);
insert into product_order values (NULL, 1, @order_id, 2);
commit;

begin;
insert into bis_order values (NULL, NULL, NULL, 'courier', 3, 'user comment 3',
			      'admin comment 3', 'ready', 'address7',
			      'e7@mail.ru', 'vk.com/user7');
set @order_id = last_insert_id();
insert into product_order values (NULL, 1, @order_id, 2);
insert into product_order values (NULL, 2, @order_id, 3);
commit;

begin;
insert into bis_order values (NULL, NULL, NULL, 'courier', 4, NULL, NULL,
			      'shipped', 'address8',
			      'e8@mail.ru', 'vk.com/user8');
set @order_id = last_insert_id();
insert into product_order values (NULL, 0, @order_id, 1);
commit;

begin;
insert into bis_order values (NULL, NULL, NULL, 'courier', 1, NULL, NULL,
			      'delivered', 'address9',
			      'e9@mail.ru', 'vk.com/user9');
set @order_id = last_insert_id();
insert into product_order values (NULL, 0, @order_id, 1);
commit;

begin;
insert into bis_order values (NULL, NULL, NULL, 'courier', 2, NULL, NULL,
			      'canceled', 'address10',
			      'e10@mail.ru', 'vk.com/user10');
set @order_id = last_insert_id();
insert into product_order values (NULL, 0, @order_id, 1);
commit;

begin;
insert into bis_order values (NULL, NULL, NULL, 'courier', 3, NULL, NULL,
			      'canceled', 'address11',
			      'e11@mail.ru', 'vk.com/user11');
set @order_id = last_insert_id();
insert into product_order values (NULL, 0, @order_id, 1);
commit;

begin;
insert into bis_order values (NULL, NULL, NULL, 'courier', 4, NULL, NULL,
			      'new', 'address12',
			      'e12@mail.ru', 'vk.com/user12');
set @order_id = last_insert_id();
insert into product_order values (NULL, 0, @order_id, 1);
commit;

begin;
insert into bis_order values (NULL, NULL, NULL, 'courier', 1, NULL, NULL,
			      'processing', 'address13',
			      'e13@mail.ru', 'vk.com/user13');
set @order_id = last_insert_id();
insert into product_order values (NULL, 0, @order_id, 1);
commit;

begin;
insert into bis_order values (NULL, NULL, NULL, 'courier', 2, NULL, NULL,
			      'ready', 'address14',
			      'e14@mail.ru', 'vk.com/user14');
set @order_id = last_insert_id();
insert into product_order values (NULL, 0, @order_id, 1);
commit;

begin;
insert into bis_order values (NULL, NULL, NULL, 'courier', 3, NULL, NULL,
			      'shipped', 'address15',
			      'e15@mail.ru', 'vk.com/user15');
set @order_id = last_insert_id();
insert into product_order values (NULL, 0, @order_id, 1);
commit;
