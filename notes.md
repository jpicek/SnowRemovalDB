I removed this from snow_removal_service.sql, it was commented out

This is how I was working towards calculating the TotalPrice

-- show the sum price
select SnowRemovalPrice, SaltingPrice, SUM(SnowRemovalPrice + SaltingPrice) from Address GROUP BY AddressID;

-- Names, plow, salt, and total prices
select CONCAT(firstName, ' ', lastName) AS Name,
       SnowRemovalPrice, SaltingPrice,
	   SUM(SnowRemovalPrice + SaltingPrice) AS TotalPrice
	   FROM Customer AS C
	   RIGHT JOIN Address A
	   ON A.CustomerID = C.CustomerID
	   GROUP BY AddressID;
	   
-- select with IFs
select SUM(IF(wasSnowRemoved <> 0, SnowRemovalPrice, 0) + IF(wasWalkwaySalted <> 0, SaltingPrice, 0)) AS TotalPrice
 FROM Address A
 RIGHT JOIN SnowRemoval R
 ON A.AddressID = R.AddressID
 GROUP BY R.AddressID;
 
-- can I combine them?
SELECT CONCAT(firstName, ' ', lastName) AS Name,
   SUM(IF(wasSnowRemoved <> 0, SnowRemovalPrice, 0) + IF(wasWalkwaySalted <> 0, SaltingPrice, 0)) AS TotalPrice,
   EventID
   FROM Customer C
   LEFT JOIN Address A
   ON C.CustomerID = A.CustomerID
   RIGHT JOIN SnowRemoval R
   ON A.AddressID = R.AddressID
   GROUP BY R.EventID, C.CustomerID;

SELECT SUM(IF(wasSnowRemoved IS TRUE THEN SnowRemovalPrice ELSE 0), IF(wasWalkwaySalted IS TRUE THEN SaltingPrice ELSE 0))
AS TotalPrice
FROM Address, SnowRemoval
GROUP BY AddressID;

Initally, I tried setting SnowRemoval.TotalPrice via either a Trigger or Function
but couldn't get it, so I ended up removing that column and calculating and displaying it with a View.

	... FAILED ATTEMPTS with TRIGGER and FUNCTION :( ...
-- create trigger to calculate TotalPrice for a SnowRemoval

DELIMITER //
CREATE FUNCTION ComputeTotalPrice (add INT, snow BOOLEAN, salt BOOLEAN)
	RETURNS INT
	READS SQL DATA
BEGIN
	DECLARE totalPrice INT, snowPrice INT, saltPrice INT;
	
	SELECT SnowRemovalPrice, SaltingPrice
	INTO snowPrice, saltPrice
	FROM Address
	WHERE Address.AddressID = add;
	
	IF snow IS TRUE THEN
		SET totalPrice = totalPrice + snowPrice;
	END IF;
	
	IF salt IS TRUE THEN
		SET totalPrice = totalPrice + saltPrice;
	END IF;
	
	RETURN totalPrice;
END; //

DELIMITER ;