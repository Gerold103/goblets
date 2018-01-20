create table product (id integer autoincrement primary key,
                      type enum ('goblet', 'box') not NULL,
                      name text not NULL,
                      price unsigned not NULL,
                      available_count unsigned not NULL,
                      size text not NULL,
                      capacity unsigned NULL,

                      index(type));

create table user (id integer autoincrement primary key,
                   first_order_ts timestamp default current_timestamp not NULL,
                   vk_link text NULL,
                   name text NULL,
                   address text NULL,
                   email text NULL,
                   telephone text not NULL,

                   unique index(telephone),
                   index(vk_link),
                   index(email));

create table order (id integer autoincrement primary key,
                    open_ts timestamp default current_timestamp not NULL,
                    update_ts timestamp default current_timestamp
                              on update current_timestamp not NULL,
                    delivery_type enum ('courier', 'customer') not NULL,
                    user_id integer not NULL,
                    user_comment text NULL,
                    admin_comment text NULL,
                    status enum ('new', 'processing', 'ready', 'shipped',
                                 'delivered', 'canceled') not NULL,

                    index(status, open_ts),
                    index(open_ts, status),
                    index(user_id),

                    foreign key (user_id) references user(id));

create table product_order (id integer autoincrement primary key,
                            product_id integer not NULL,
                            order_id integer not NULL,
                            count unsigned not NULL,

                            index(product_id),
                            unique index(order_id, product_id),

                            foreign key (product_id) references product(id),
                            foreign key (order_id) references order(id));
/**
 * Add order:
 *
 *
 * begin;
 * -- Find user.
 * user_id = select id from user where telephone = @telephone;
 * if not user_id then
 *     insert into user values(NULL, NULL, @vk_link, @name, @address, @email,
 *                             @telephone);
 *     user_id = last_insert_id
 * end
 * insert into order values(NULL, NULL, NULL, @delivery_type, @user_id,
 *                          @comment, NULL, 'new');
 * order_id = last_insert_id;
 * foreach product in order do
 *     insert into product_order values(NULL, @product_id, @order_id, @count);
 * end
 * commit;
 *
 *
 * Change status:
 *
 * begin;
 * old_status = select status from order where id = @order_id;
 * if status == 'processing' then
 *      product_ids, counts = select product_id, count from product_order where
 *                            order_id = order_id;
 *      foreach product_id, count do
 *              update product set available_count = available_count - @count
 *              where product_id = @product_id;
 *      end;
 * else if old_status != 'new' and status == 'canceled' then
 *      product_ids, counts = select product_id, count from product_order where
 *                            order_id = order_id;
 *      foreach product_id, count do
 *              update product set available_count = available_count + @count
 *              where product_id = @product_id;
 *      end;
 * end
 * update order set status = @status where id = order_id;
 * commit;
 */