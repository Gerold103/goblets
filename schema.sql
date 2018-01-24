create database goblets default character set utf8;
use goblets;
create table product (id integer primary key,
                      type enum ('goblet', 'box') not NULL,
                      name text not NULL,
                      price integer unsigned not NULL,
                      available_count integer unsigned not NULL,
                      size text not NULL,
                      capacity integer unsigned NULL,

                      index(type));
insert into product values(0, 'goblet', 'Дракон', 1200, 10, '8x8x18', 200);
insert into product values(1, 'goblet', 'Рука скелета', 1200, 10,
                           '8x8x18', 200);
insert into product values(2, 'goblet', 'Череп', 1200, 10, '8x8x18', 200);

create table user (id integer auto_increment primary key,
                   first_order_ts timestamp default current_timestamp not NULL,
                   vk_link text NULL,
                   name text NULL,
                   address text NULL,
                   email text NULL,
                   telephone text not NULL,
                   is_subscribed_on_news boolean default false,

                   unique index(telephone(20)),
                   index(vk_link(300)),
                   index(email(300)));

create table bis_order (id integer auto_increment primary key,
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

create table product_order (id integer auto_increment primary key,
                            product_id integer not NULL,
                            order_id integer not NULL,
                            count integer unsigned not NULL,

                            index(product_id),
                            unique index(order_id, product_id),

                            foreign key (product_id) references product(id),
                            foreign key (order_id) references bis_order(id));

delimiter $$
create function get_or_create_user(vk_link_ text, name_ text, address_ text,
                                   email_ text, telephone_ text)
                returns integer NOT DETERMINISTIC
begin
  declare user_id integer;
  declare user_count integer;
  select id, count(*) into user_id, user_count from user where telephone = telephone_;
  if user_count = 0 then
    insert into user values(null, null, vk_link_, name_, address_, email_,
                            telephone_, null);
    set user_id = last_insert_id();
  end if;
  return user_id;
end$$
delimiter ;
/**
 * Add order:
 *
 *
 * begin;
 * -- Find user.
 * user_id = select id from user where telephone = @telephone;
 * if not user_id then
 *     insert into user values(NULL, NULL, @vk_link, @name, @address, @email,
 *                             @telephone, NULL);
 *     user_id = last_insert_id
 * end
 * insert into bis_order values(NULL, NULL, NULL, @delivery_type, @user_id,
 *                          @comment, NULL, 'new');
 * order_id = last_insert_id;
 * foreach product in bis_order do
 *     insert into product_order values(NULL, @product_id, @order_id, @count);
 * end
 * commit;
 *
 *
 * Change status:
 *
 * begin;
 * old_status = select status from bis_order where id = @order_id;
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
 * update bis_order set status = @status where id = order_id;
 * commit;
 */