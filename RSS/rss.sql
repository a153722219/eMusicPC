SET NAMES UTF8;

DROP DATABASE IF EXISTS rss;

CREATE DATABASE rss CHARSET=UTF8;

USE rss;

CREATE TABLE r_user(
	id INT PRIMARY KEY AUTO_INCREMENT,
	username varchar(32) NOT NULL UNIQUE,
	password varchar(32) NOT NULL,
	avatar varchar(256) NOT NULL DEFAULT '/static/avatar.jpg'
);

CREATE TABLE r_sub(
	mid INT PRIMARY KEY AUTO_INCREMENT,
	uid INT NOT NULL,
	title varchar(32) NOT NULL,
	image varchar(256) NOT NULL,
	hash varchar(64) NOT NULL,
	pnum int NOT NULL default 0,
	isSub boolean NOT NULL default true
);

insert into r_user values(null,'小白大人','e10adc3949ba59abbe56e057f20f883e',default);


