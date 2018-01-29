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
			      'admin comment 1', 'new');
set @order_id = last_insert_id();
insert into product_order values (NULL, 0, @order_id, 1);
insert into product_order values (NULL, 1, @order_id, 2);
insert into product_order values (NULL, 2, @order_id, 3);
commit;

begin;
insert into bis_order values (NULL, NULL, NULL, 'customer', 2, 'user comment 2',
			      '', 'processing');
set @order_id = last_insert_id();
insert into product_order values (NULL, 0, @order_id, 1);
insert into product_order values (NULL, 1, @order_id, 2);
commit;

begin;
insert into bis_order values (NULL, NULL, NULL, 'courier', 3, 'user comment 3',
			      'admin comment 3', 'ready');
set @order_id = last_insert_id();
insert into product_order values (NULL, 1, @order_id, 2);
insert into product_order values (NULL, 2, @order_id, 3);
commit;

begin;
insert into bis_order values (NULL, NULL, NULL, 'courier', 4, NULL, NULL,
			      'shipped');
set @order_id = last_insert_id();
insert into product_order values (NULL, 0, @order_id, 1);
commit;

begin;
insert into bis_order values (NULL, NULL, NULL, 'courier', 1, NULL, NULL,
			      'delivered');
set @order_id = last_insert_id();
insert into product_order values (NULL, 0, @order_id, 1);
commit;

begin;
insert into bis_order values (NULL, NULL, NULL, 'courier', 2, NULL, NULL,
			      'canceled');
set @order_id = last_insert_id();
insert into product_order values (NULL, 0, @order_id, 1);
commit;

begin;
insert into bis_order values (NULL, NULL, NULL, 'courier', 3, NULL, NULL,
			      'canceled');
set @order_id = last_insert_id();
insert into product_order values (NULL, 0, @order_id, 1);
commit;

begin;
insert into bis_order values (NULL, NULL, NULL, 'courier', 4, NULL, NULL,
			      'new');
set @order_id = last_insert_id();
insert into product_order values (NULL, 0, @order_id, 1);
commit;

begin;
insert into bis_order values (NULL, NULL, NULL, 'courier', 1, NULL, NULL,
			      'processing');
set @order_id = last_insert_id();
insert into product_order values (NULL, 0, @order_id, 1);
commit;

begin;
insert into bis_order values (NULL, NULL, NULL, 'courier', 2, NULL, NULL,
			      'ready');
set @order_id = last_insert_id();
insert into product_order values (NULL, 0, @order_id, 1);
commit;

begin;
insert into bis_order values (NULL, NULL, NULL, 'courier', 3, NULL, NULL,
			      'shipped');
set @order_id = last_insert_id();
insert into product_order values (NULL, 0, @order_id, 1);
commit;
