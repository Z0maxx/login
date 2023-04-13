CREATE DATABASE IF NOT EXISTS users;
use users;

CREATE TABLE registered_users (
    id INT(4) UNSIGNED AUTO_INCREMENT,
    username VARCHAR(30) NOT NULL,
    password VARCHAR(100) NOT NULL,
    PRIMARY KEY (id)
);