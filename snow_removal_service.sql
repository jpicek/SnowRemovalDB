/*
Picek, Johnathan
Dr. Cindy Howard
CPSC 33000-001
Final Project - snow_removal_service.sql
physical design, insert statements, PHP user creation and access granting
December 14, 2021

snowservice.html is the starting page

*/

-- create physical deisgn: databases, tables, constraints, indexes
DROP USER 'snowservPHPuser'@'localhost';
DROP DATABASE IF EXISTS snowservice;
CREATE DATABASE snowservice;
USE snowservice;

-- create tables
CREATE TABLE Customer (
	CustomerID 		SMALLINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
	FirstName 		VARCHAR(30),
	LastName 		VARCHAR(30),
	PhoneNumber 	CHAR(10)
	);
	
CREATE TABLE Address (
	AddressID 			SMALLINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
	CustomerID 			SMALLINT UNSIGNED,
	StreetNumber		SMALLINT UNSIGNED,
	Street				VARCHAR(50),
	City				VARCHAR(30),
	ZipCode 			CHAR(5),
	SnowRemovalPrice	SMALLINT UNSIGNED,
	SaltingPrice		SMALLINT UNSIGNED,
	CONSTRAINT fk_address_customerid FOREIGN KEY (CustomerID) REFERENCES Customer(CustomerID)
	ON UPDATE CASCADE
	ON DELETE SET NULL
	);
	
CREATE TABLE SnowfallEvent (
	EventID			SMALLINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
	TimeDate		DATETIME NOT NULL,
	InchesOfSnow	TINYINT
	);

CREATE TABLE SnowRemoval (
	RemovalNumber 		SMALLINT UNSIGNED AUTO_INCREMENT,
	AddressID 			SMALLINT UNSIGNED,
	EventID 			SMALLINT UNSIGNED,
	wasSnowRemoved 		BOOLEAN NOT NULL,
	wasWalkwaySalted 	BOOLEAN NOT NULL,
	wasPaid 			BOOLEAN DEFAULT '0',
	PRIMARY KEY (RemovalNumber, AddressID, EventID),
	CONSTRAINT fk_snowremoval_addressid FOREIGN KEY (AddressID) REFERENCES Address(AddressID)
	ON UPDATE CASCADE
	ON DELETE RESTRICT,
	CONSTRAINT fk_snowremoval_eventid FOREIGN KEY (EventID) REFERENCES SnowfallEvent(EventID)
	ON UPDATE CASCADE
	ON DELETE RESTRICT
	);
	
-- create indexes
create index lname_index on Customer (LastName);
create index zip_index on Address (ZipCode);
create index date_index on SnowfallEvent (TimeDate);

-- insert sample data
INSERT INTO Customer (FirstName, LastName, PhoneNumber) 
VALUES ('Johnathan', 'Picek', '7085551111'),
       ('Cindy', 'Howard', '8155550000'),
	   ('Mario', 'Mario', '8155556464'),
	   ('Luigi', 'Mario', '8155554646'),
	   ('Peach', 'Toadstool', '8155557777'),
	   ('Steve', 'Rogers', '6781367092'),
	   ('Peter', 'Parker', '8155554444'),
	   ('Bruce', 'Wayne', '8155552468'),
	   ('Wanda', 'Maximoff', '8155551357');
	   
INSERT INTO Address (CustomerID, StreetNumber, Street, City, ZipCode, SnowRemovalPrice, SaltingPrice)
VALUES (1, '6789', 'Parkside Ave', 'Countryside', '60525', 25, 10),
       (2, '1', 'University Pkwy', 'Romeoville', '60446', 35, 15),
	   (3, '64', 'Star Lane', 'Mushroom Kingdom', '92345', 30, 10),
	   (4, '46', 'Star Drive', 'Mushroom Kingdom', '92345', 30, 5),
	   (5, '55', 'Castle Road', 'Mushroom Kingdom', '92346', 75, 30),
	   (6, '569', 'Leaman Place', 'Brooklyn Heights', '11231', 2, 1),
	   (7, '20', 'Ingram Street', 'Queens', '11375', 10, 5),
	   (8, '88', 'Wayne Lane', 'Gotham', '12345', 25, 10),			-- home 1
	   (8, '1007', 'Mountain Drive', 'Gotham', '12345', 90, 30), 	-- home 2
	   (8, '1939', 'Kane Street', 'Gotham', '12345', 125, 25), 		-- wayne tower, the business
	   (9, '2800', 'Sherwood Drive', 'Westview', '08801', 35, 15);
	   
INSERT INTO SnowfallEvent
VALUES (DEFAULT, '2021-12-14T23:59:59', 7),
	   (DEFAULT, '2021-12-22T12:34:56', 2),
	   (DEFAULT, '2021-12-29T07:15:32', 4),
	   (DEFAULT, '2022-01-24T18:00:01', 3),
	   (DEFAULT, '2022-02-13T04:30:30', 12);

INSERT INTO SnowRemoval (AddressID, EventID, wasSnowRemoved, wasWalkwaySalted)
VALUES (1, 1, 1, 1),
	   (2, 1, 1, 1),
	   (3, 1, 1, 0),
	   (4, 1, 1, 0),
	   (5, 1, 1, 1),
	   (6, 1, 1, 0),
	   (7, 1, 1, 0),
	   (8, 1, 1, 0),
	   (9, 1, 1, 1),
	   (10, 1, 1, 1),
	   (11, 1, 1, 0),
	   (1, 2, 1, 1),
	   (3, 2, 1, 0),
	   (4, 2, 1, 0),
	   (5, 2, 1, 1),
	   (11, 2, 1, 1),
	   (1, 3, 1, 1),
	   (2, 3, 1, 1),
	   (3, 3, 1, 0),
	   (4, 3, 1, 0),
	   (5, 3, 1, 1),
	   (6, 3, 1, 0),
	   (7, 3, 1, 0),
	   (8, 3, 1, 0),
	   (9, 3, 1, 1),
	   (11, 3, 1, 0),
	   (1, 4, 1, 1),
	   (3, 4, 1, 0),
	   (4, 4, 1, 0),
	   (5, 4, 1, 1),
	   (10, 4, 1, 1),
	   (11, 4, 1, 0),
	   (1, 5, 1, 0),
	   (2, 5, 1, 0),
	   (3, 5, 1, 0),
	   (4, 5, 1, 0),
	   (5, 5, 1, 0),
	   (6, 5, 1, 0),
	   (7, 5, 1, 0),
	   (8, 5, 1, 0),
	   (9, 5, 1, 0),
	   (10, 5, 1, 0),
	   (11, 5, 1, 0),
	   (1, 5, 1, 1),
	   (2, 5, 1, 1),
	   (3, 5, 1, 1),
	   (4, 5, 1, 1),
	   (5, 5, 1, 1),
	   (6, 5, 1, 0),
	   (7, 5, 1, 0),
	   (8, 5, 1, 0),
	   (9, 5, 1, 1),
	   (10, 5, 1, 1),
	   (11, 5, 1, 0);

-- PHP user creation and grant access
CREATE USER 'snowservPHPuser'@'localhost' identified by 'cpsc33000';
GRANT SELECT, INSERT, UPDATE on snowservice.* TO 'snowservPHPuser'@'localhost';

-- create view to display TotalPrice for a SnowRemoval got a little complicated
-- DROP VIEW RemovalsPrices;
CREATE VIEW RemovalsPrices
AS SELECT CONCAT(firstName, ' ', lastName) AS Name,
   SUM(IF(wasSnowRemoved <> 0, SnowRemovalPrice, 0) + IF(wasWalkwaySalted <> 0, SaltingPrice, 0)) AS TotalPrice,
   E.TimeDate AS Date_and_Time
   FROM Customer C
   LEFT JOIN Address A
   ON C.CustomerID = A.CustomerID
   RIGHT JOIN SnowRemoval R
   ON A.AddressID = R.AddressID
   LEFT JOIN SnowfallEvent E
   ON E.EventID = R.EventID
   GROUP BY E.TimeDate, R.RemovalNumber, C.CustomerID;
   
CREATE VIEW CustomerSearchView
AS SELECT CONCAT(FirstName, ' ', LastName) AS Name,
   StreetNumber, Street, City, ZipCode
   FROM Address A
   RIGHT JOIN Customer C
   ON A.CustomerID = C.CustomerID;