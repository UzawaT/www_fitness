drop database if exists gymstore;
create database if not exists gymstore;
use gymstore;

#create tables related to the user
create table user_role
(
	role_id int unsigned auto_increment,
    role_name nvarchar(30) not null,
    
    primary key(role_id)
);

insert into user_role(role_name)
value ('administrator');

insert into user_role(role_name)
value ('gym member');

create table gym_user
(
	user_id int unsigned auto_increment,
    first_name nvarchar(30) not null,
    middle_initial nchar(1),
    last_name nvarchar(30) not null,
    email nvarchar(100) not null,
    phone nchar(12),
    user_name nvarchar(30) not null,
    user_pass nchar(60) not null,
    role_id int unsigned default '2',
    
    primary key(user_id),
    foreign key(role_id) references user_role(role_id)
);

create table user_image
(
	image_id int unsigned auto_increment primary key,
    filename nvarchar(100),
    image_type nvarchar(50),
    image_data longblob,
    user_id int unsigned not null,
    
    foreign key(user_id) references gym_user(user_id)
);

#create tables related to products
create table prod_category
(
	cat_id int unsigned auto_increment,
    cat_name varchar(50) not null,

    primary key(cat_id)
);

insert into prod_category(cat_name)
values('Apparel');

insert into prod_category(cat_name)
values('Exercise tool');

create table prod_price
(
	price_id int unsigned auto_increment,
    price decimal not null,
	start_date date not null,
    end_date date,

    primary key(price_id)
);

insert into prod_price(price, start_date)
values('10', '2018-07-25');

insert into prod_price(price, start_date)
values('25', '2018-07-25');

insert into prod_price(price, start_date)
values('20', '2018-07-25');

create table product
(
	prod_id int unsigned auto_increment,
    prod_name nvarchar(50) not null,
    description text,
    image_file text,
	cat_id int unsigned not null,
    price_id int unsigned not null,
    
    primary key(prod_id),
    foreign key(cat_id) references prod_category(cat_id),
    foreign key(price_id) references prod_price(price_id)
);

insert into product(prod_name, description, image_file, cat_id, price_id)
values('T-shirt', 'T-shirt made of breathable material', 't-shirt.jpg','1', '1');

insert into product(prod_name, description, image_file, cat_id, price_id)
values('Sweatshirt', 'Sweatshirt for cold weather', 'sweat-shirt.jpg', '1', '2');

insert into product(prod_name, description, image_file, cat_id, price_id)
values('Foam Roller', 'Foam roller for stretching', 'foam-roller.jpg', '2', '3');

#create tables related to shopping cart
create table cart
(
	cart_id int unsigned auto_increment,
    user_id int unsigned,
    date_created datetime default current_timestamp,
    checked_out boolean default false,
    date_checked_out datetime,
    total_sales decimal default 0,
    
    primary key(cart_id),
    foreign key(user_id) references gym_user(user_id)
);

create table cart_item
(
	item_id int unsigned auto_increment,
    prod_id int unsigned,
    cart_id int unsigned,
    quantity int unsigned not null,
    
    primary key(item_id),
    foreign key(prod_id) references product(prod_id),
    foreign key(cart_id) references cart(cart_id)
);