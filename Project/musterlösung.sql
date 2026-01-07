DROP DATABASE IF EXISTS portableindoorfeedback;
CREATE DATABASE IF NOT EXISTS portableindoorfeedback;
USE portableindoorfeedback;

CREATE TABLE user (
  pk_username VARCHAR(50) NOT NULL,
  firstName VARCHAR(50) NOT NULL,
  lastName VARCHAR(50) NOT NULL,
  password VARCHAR(255) NOT NULL,
  email VARCHAR(50) NOT NULL,
  role ENUM('User','Admin') NOT NULL DEFAULT 'User',
  PRIMARY KEY (pk_username),
  UNIQUE (email)
);

CREATE TABLE station (
  pk_serialNumber VARCHAR(50) NOT NULL,
  name VARCHAR(100),
  description TEXT,
  fk_user_owns VARCHAR(50),
  PRIMARY KEY (pk_serialNumber),
  FOREIGN KEY (fk_user_owns)
    REFERENCES user(pk_username)
    ON DELETE SET NULL
    ON UPDATE CASCADE
);

CREATE TABLE measurement (
  pk_measurement INT NOT NULL AUTO_INCREMENT,
  temperature DECIMAL(5,2) NOT NULL,
  humidity DECIMAL(5,2) NOT NULL,
  pressure DECIMAL(6,2) NOT NULL,
  light DECIMAL(6,2) NOT NULL,
  gas DECIMAL(6,2) NOT NULL,
  timestamp DATETIME NOT NULL,
  fk_station_records VARCHAR(50) NOT NULL,
  PRIMARY KEY (pk_measurement),
  FOREIGN KEY (fk_station_records)
    REFERENCES station(pk_serialNumber)
    ON DELETE CASCADE
    ON UPDATE CASCADE
);

CREATE TABLE collection (
  pk_collection INT NOT NULL AUTO_INCREMENT,
  name VARCHAR(50),
  description TEXT,
  fk_user_creates VARCHAR(50) NOT NULL,
  PRIMARY KEY (pk_collection),
  FOREIGN KEY (fk_user_creates)
    REFERENCES user(pk_username)
    ON DELETE CASCADE
    ON UPDATE CASCADE
);

CREATE TABLE contains (
  pkfk_collection INT NOT NULL,
  pkfk_measurement INT NOT NULL,
  PRIMARY KEY (pkfk_collection, pkfk_measurement),
  FOREIGN KEY (pkfk_collection)
    REFERENCES collection(pk_collection)
    ON DELETE CASCADE
    ON UPDATE CASCADE,
  FOREIGN KEY (pkfk_measurement)
    REFERENCES measurement(pk_measurement)
    ON DELETE CASCADE
    ON UPDATE CASCADE
);

CREATE TABLE hasaccess (
  pkfk_user VARCHAR(50) NOT NULL,
  pkfk_collection INT NOT NULL,
  PRIMARY KEY (pkfk_user, pkfk_collection),
  FOREIGN KEY (pkfk_user)
    REFERENCES user(pk_username)
    ON DELETE CASCADE
    ON UPDATE CASCADE,
  FOREIGN KEY (pkfk_collection)
    REFERENCES collection(pk_collection)
    ON DELETE CASCADE
    ON UPDATE CASCADE
);

CREATE TABLE isfriend (
  pkfk_user_user VARCHAR(50) NOT NULL,
  pkfk_user_friend VARCHAR(50) NOT NULL,
  PRIMARY KEY (pkfk_user_user, pkfk_user_friend),
  FOREIGN KEY (pkfk_user_user)
    REFERENCES user(pk_username)
    ON DELETE CASCADE
    ON UPDATE CASCADE,
  FOREIGN KEY (pkfk_user_friend)
    REFERENCES user(pk_username)
    ON DELETE CASCADE
    ON UPDATE CASCADE
);
