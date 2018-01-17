create table goblet (id integer autoincrement primary key,
		     name text not NULL,
		     price unsigned not NULL,
		     count unsigned not NULL,
		     size text not NULL);

create table order (id integer autoincrement primary key,
		    open_ts timestamp default current_timestamp not NULL,
		    update_ts timestamp default current_timestamp
			      on update current_timestamp not NULL,
		    vk_link text NULL,
		    delivery_type enum ('courier', 'ownshipment') not NULL,
		    name text NULL,
		    email text NULL,
		    telephone text not NULL,
		    address text NULL,
		    comment text NULL,
		    status enum ('new', 'processing', 'ready', 'shipped',
		    		 'delivered', 'canceled') not NULL,

		    index(vk_link),
		    index(open_ts, status),
		    index(email),
		    index(telephone));

create table goblet_order (id integer autoincrement primary key,
			   goblet_id integer not NULL,
			   order_id integer not NULL,
			   count unsigned not NULL,

			   index(goblet_id),
			   unique index(order_id, goblet_id),

			   foreign key (goblet_id) references goblet(id),
			   foreign key (order_id) references order(id)
			   on delete cascade);
/*
 * Add order:
 *
 * begin;
 * insert into order values(NULL, NULL, NULL, @vk_link, @delivery_type,
 *                          @name, @email, @telephone, @address,
 *                          @comment, 'new');
 * order_id = last_insert_id;
 * foreach goblet in order do
 *     insert into goblet_order values(NULL, @goblet_id, @order_id, @count);
 *     update goblet set count = count - @count where id = @goblet_id;
 * end
 * commit;
 *
 * Change status:
 * begin;
 * if status == 'canceled' then
 *	goblets, counts = select goblet_id, count from goblet_order where order_id = order_id;
 *	foreach goblet, count do
 *		update goblet set count = count + @count where goblet = @goblet;
 *	end;
 * end
 * update order set status = @status where id = order_id;
 * commit;
 */