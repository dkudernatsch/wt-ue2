DROP DATABASE IF EXISTS webshop;
CREATE DATABASE IF NOT EXISTS webshop;

use webshop;

DROP TABLE IF EXISTS user;

CREATE TABLE user (
  username  VARCHAR(32) PRIMARY KEY,
  password  CHAR(255) NOT NULL,
  firstname VARCHAR(32) NOT NULL,
  lastname  VARCHAR(32) NOT NULL,
  email     VARCHAR(64) NOT NULL,
  is_admin  BOOLEAN DEFAULT FALSE,
  is_ldap   BOOLEAN DEFAULT FALSE
);