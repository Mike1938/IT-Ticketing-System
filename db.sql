DROP DATABASE IF EXISTS ticketingSystem;
CREATE DATABASE ticketingSystem;
use ticketingSystem;



CREATE TABLE jobPosition(
    jobCode INT PRIMARY KEY AUTO_INCREMENT,
    position VARCHAR(45) NOT NULL,
    pay DECIMAL(8,2) NOT NULL
);

-- Employee TABLE
CREATE TABLE employee(
    id INT PRIMARY KEY NOT NULL AUTO_INCREMENT,
    fName VARCHAR(45) NOT NULL,
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
    releaseDate year(4) NOT NULL
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

-- Ticket TABLE
CREATE TABLE ticket(
    id INT NOT NULL PRIMARY KEY AUTO_INCREMENT,
    userID INT NOT NULL,
    equipmentID INT NOT NULL,
    problem VARCHAR(250) NOT NULL,
    ticketDate TIMESTAMP NOT NULL DEFAULT NOW(),
    tStatus VARCHAR(15) NOT NULL DEFAULT "Pending",
    FOREIGN KEY(userID)
    REFERENCES user(id),
    FOREIGN KEY(equipmentID)
    REFERENCES invoiceDetails(equipmentID)
);

-- Ticket Status TABLE
CREATE TABLE ticketStatus(
    ticketID INT NOT NULL,
    employeeID INT NOT NULL,
    tStart TIMESTAMP NOT NULL DEFAULT NOW(),
    tEnd TIMESTAMP,
    solution VARCHAR(250),
    FOREIGN KEY(ticketID)
    REFERENCES ticket(id),
    FOREIGN KEY(employeeID)
    REFERENCES employee(id)
);

-- comments TABLE
CREATE TABLE comments(
    id INT NOT NULL AUTO_INCREMENT PRIMARY KEY,
    ticketID INT NOT NULL,
    comment varchar(250) NOT NULL,
    userID INT,
    employeeID INT,
    commentDate TIMESTAMP NOT NULL DEFAULT NOW(),
    FOREIGN KEY(ticketID)
    REFERENCES ticket(id),
    FOREIGN KEY(userID)
    REFERENCES user(id),
    FOREIGN KEY(employeeID)
    REFERENCES employee(id)
);

--  users audit table
CREATE TABLE userAudit(
    logId INT AUTO_INCREMENT PRIMARY KEY NOT NULL,
    userID INT NOT NULL,
    fName VARCHAR(45) NOT NULL,
    lName VARCHAR(45) NOT NULL,
    companyName VARCHAR(45) NOT NULL,
    email VARCHAR(100),
    pwd VARCHAR(100) NOT NULL,
    phoneNumber VARCHAR(20),
    employeeID INT NOT NULL,
    changeType VARCHAR(50) NOT NULL,
    modifiedDate TIMESTAMP DEFAULT NOW() NOT NULL,
    FOREIGN KEY(userID)
    REFERENCES user(id),
    FOREIGN KEY(employeeID)
    REFERENCES employee(id)
);

-- employee audit log on
CREATE TABLE empLogInOut(
    id INT AUTO_INCREMENT PRIMARY KEY NOT NULL,
    empId INT NOT NULL,
    logEvent VARCHAR(20) NOT NULL,
    logOnTime TIMESTAMP NOT NULL DEFAULT NOW(),
    FOREIGN KEY(empID)
    REFERENCES employee(id)
);

-- Insert into jobPosition table
INSERT INTO jobPosition(position, pay)
    VALUES
        ('IT Manager', 40), ('Senior IT', 32), ('Junior IT', 25), ('IT', 19);

-- Creating view for clients computers
CREATE VIEW clientProducts
    AS
        SELECT
            invoice.userID as "user",
            invoiceDetails.equipmentID as "equipId",
            product.pName as "ProductName"
        FROM invoiceDetails
        INNER JOIN invoice
        ON invoiceDetails.invoiceID = invoice.id
        INNER JOIN product
        ON invoiceDetails.productID = product.id;

CREATE VIEW tickets
    AS
        SELECT
            ticket.id as "ticketId",
            ticket.userID as "userId",
            ticket.equipmentID as "equipId",
            ticket.problem as "problem",
            DATE_FORMAT(ticket.ticketDate, "%M-%d-%Y %H:%i") as "ticketPosted",
            DATE_FORMAT(ticketStatus.tStart, "%M-%d-%Y %H:%i") as "startDate",
            DATE_FORMAT(ticketStatus.tEnd, "%M-%d-%Y %H:%i") as "endDate",
            ticketStatus.employeeID,
            ticket.tStatus
            FROM ticket
            INNER JOIN ticketStatus
            on ticket.id = ticketStatus.ticketID;