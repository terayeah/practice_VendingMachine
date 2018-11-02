drop table users;
drop table vending_machine;
drop table drink;
drop table vending_machine_drink;
drop table user_drink;


create table users(
  id int not null auto_increment primary key,
  name varchar(255) unique,
  cash int,
  suica int default 0,
  salt varchar(255),
  encrypted_password varchar(255)
);

create table vending_machine(
  id int not null auto_increment primary key,
  name varchar(255),
  type enum('cash', 'suica', 'both'),
  cash int default 0,
  suica int default 0,
  charge int default 0
);

create table drink(
  id int not null auto_increment primary key,
  name varchar(255),
  price int default 0
);

create table vending_machine_drink(
  id int not null auto_increment primary key,
  vending_machine_id int,
  drink_id int,
  drink_count int default 0
);

create table user_drink(
  id int not null auto_increment primary key,
  user_id int,
  drink_id int,
  drink_count int default 0
);


 insert into vending_machine(name, type) values ('test_cash', 'cash');
 insert into vending_machine(name, type) values ('test_suica', 'suica');
 insert into drink (name, price) values ('coffee', 100);
 insert into drink (name, price) values ('お茶', 150);
 insert into drink (name, price) values ('水', 80);
 insert into vending_machine_drink (vending_machine_id, drink_id, drink_count) values (1, 1, 20);
 insert into vending_machine_drink (vending_machine_id, drink_id, drink_count) values (2, 3, 10);
