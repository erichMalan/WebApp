DROP DATABASE IF EXISTS myDB;

CREATE DATABASE IF NOT EXISTS myDB;

CREATE TABLE IF NOT EXISTS MyGuests (
    		id INT(6) UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    		email VARCHAR(30) NOT NULL,
    		password VARCHAR(255) NOT NULL,
    		reg_date TIMESTAMP
    		);

CREATE TABLE IF NOT EXISTS MySits (
    		id INT(6) UNSIGNED AUTO_INCREMENT PRIMARY KEY,
            	row INT(6),
            	col INT(6),
            	status VARCHAR(20),
            	user VARCHAR(20),
    		reg_date TIMESTAMP
    		);
