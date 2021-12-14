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

-- User TABLE
CREATE TABLE user(
    id INT NOT NULL PRIMARY KEY AUTO_INCREMENT,
    fName VARCHAR(45) NOT NULL,
    lName VARCHAR(45) NOT NULL,
    companyName VARCHAR(45) NOT NULL,
    email VARCHAR(100),
    joined TIMESTAMP NOT NULL DEFAULT NOW(),
    pwd VARCHAR(100) NOT NULL,
    phoneNumber VARCHAR(20)
);

-- Product TABLE
CREATE TABLE product(
    id INT NOT NULL PRIMARY KEY AUTO_INCREMENT,
    model VARCHAR(50) NOT NULL,
    pName VARCHAR(45) NOT NULL,
    releaseDate year(4) NOT NULL,
);

-- Invoice TABLE
CREATE TABLE invoice(
    id INT NOT NULL PRIMARY KEY AUTO_INCREMENT,
    userID INT NOT NULL,
    employeeID INT NOT NULL,
    invoiceDate TIMESTAMP NOT NULL DEFAULT NOW(),
    FOREIGN KEY(userID)
    REFERENCES user(id),
    FOREIGN KEY(employeeID)
    REFERENCES employee(id)
);

-- Invoice Details TABLE
CREATE TABLE invoiceDetails(
    equipmentID INT NOT NULL AUTO_INCREMENT PRIMARY KEY,
    invoiceID INT NOT NULL,
    productID INT NOT NULL,
    FOREIGN KEY(invoiceID)
    REFERENCES invoice(id),
    FOREIGN KEY(productID)
    REFERENCES product(id)
);