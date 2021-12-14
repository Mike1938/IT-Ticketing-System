DROP DATABASE IF EXISTS ticketingSystem;
CREATE DATABASE ticketingSystem;
use ticketingSystem;


-- JobPosition TABLE
CREATE TABLE jobPosition(
    jobCode INT NOT NULL PRIMARY KEY AUTO_INCREMENT,
    position VARCHAR(45) NOT NULL,
    pay DECIMAL(8,2) NOT NULL DEFAULT 18
);

-- Employee TABLE
CREATE TABLE employee(
    id INT PRIMARY KEY NOT NULL AUTO_INCREMENT,
    fname VARCHAR(45) NOT NULL,
    lName VARCHAR(45) NOT NULL,
    email VARCHAR(100) NOT NULL,
    pwd VARCHAR(100) NOT NULL,
    joined TIMESTAMP NOT NULL DEFAULT NOW(),
    phoneNumber VARCHAR(20),
    jobPosition INT NOT NULL,
    FOREIGN KEY(jobPosition)
    REFERENCES jobPosition(jobCode)
);
