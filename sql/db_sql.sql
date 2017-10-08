CREATE DATABASE stock;

CREATE TABLE stock.products (
  id bigint not null primary key auto_increment,
  name varchar(150) not null,
  description varchar(2048),
  price double not null,
  url varchar(256)
);