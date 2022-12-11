DELIMITER //
CREATE PROCEDURE getUserNameById (
  id int(11)
)
BEGIN
  SELECT username
  FROM User 
  WHERE userId = id;
END
//

DELIMITER ;


-- $user_id = 10;
-- $listing_id = 10;
-- $city = "Palo alto";
-- $state = "California";
-- $condition = "New";
-- $price = 56.97;
-- $description = "This book is about sql and php.";

DELIMITER //
CREATE PROCEDURE updateListingsInfo (
  user_id int,
  listing_id int,
  city varchar(120),
  state varchar(120),
  book_condition varchar(120),
  price float,
  description varchar(120)
)
BEGIN
  UPDATE Listing
  SET city = city,
      state = state,
      description = description
  WHERE listingId = listing_id 
  AND userId = user_id;

  UPDATE Book
  SET bookCondition = book_condition,
      price = price
  WHERE listingId = listing_id
  AND userId = user_id;
END
//

DELIMITER ;

DELIMITER //
CREATE PROCEDURE deleteListingsInfo (
  listing_id int
)
BEGIN
  SET FOREIGN_KEY_CHECKS = 0;

  DELETE FROM Listing 
  WHERE listingId = listing_id;

  DELETE FROM Book 
  WHERE listingId = listing_id;

  SET FOREIGN_KEY_CHECKS = 1;
END
//

DELIMITER ;

-- ===========================================================================

DELIMITER //
CREATE PROCEDURE createListing (
  user_id int,
  city varchar(200),
  state varchar(200),
  book_condition varchar(200),
  price float,
  description varchar(200),
  start_date date,
  quantity int,
  photo_source varchar(200)
)
BEGIN
  SET FOREIGN_KEY_CHECKS = 0;

  CREATE TEMPORARY TABLE currentListingId (
    listingId int
  );

  INSERT INTO Listing 
  SET userId = user_id,
      city = city,
      state = state,
      quantity = quantity,
      description = description,
      startDate = start_date;
  
  INSERT INTO currentListingId
  SELECT AUTO_INCREMENT - 1 as listingId
  FROM information_schema.TABLES
  WHERE TABLE_SCHEMA = "tradespace"
  AND TABLE_NAME = "Listing";

  SET @listing_id = (SELECT SUM(listingId) FROM currentListingId);

  INSERT INTO Book
  SET listingId = @listing_id,
      userId = user_id,
      bookCondition = book_condition,
      price = price;

  INSERT INTO Photos
  SET listingId = @listing_id,
      userId = user_id,
      photoSource = photo_source;

  SET FOREIGN_KEY_CHECKS = 1;
  DROP TABLE currentListingId;
END
//

DELIMITER ;

-- https://upcdn.io/FW25az3/raw/uploads/2022/12/11/s-l500-6UsJ.jpg
CALL createListing(17, 'Seattle', 'Washington', 'Used', 32.99, 'Used but great for ruby on rails!', '2022-12-10', 1, 'https://upcdn.io/FW25az3/raw/uploads/2022/12/11/s-l500-6UsJ.jpg');

-- ===========================================================================

-- Test insert to listings and books.

INSERT INTO Listing SET userId = 17, description = 'Book about ruby', quantity = 1, city = 'Los Angeles', state = 'California', startDate = '2022-12-09';

INSERT INTO Book SET listingId = 13, userId = 17, bookCondition = 'New', price = 10.99;

CREATE VIEW UserListingsInfo AS
SELECT *
FROM User
NATURAL JOIN Listing
NATURAL JOIN Photos
NATURAL JOIN Book;


SELECT *
FROM Listing
INNER JOIN Photos ON Listing.listingId = Photos.listingId
INNER JOIN Book ON Photos.listingId = Book.listingId;

CREATE VIEW listingInfos AS
SELECT l.userId,
       l.listingId,
       l.description,
       l.city,
       l.state,
       l.startDate,
       l.endDate,
       b.bookCondition,
       b.price
FROM Listing as l
INNER JOIN Book as b ON l.listingId = b.listingId;

CREATE VIEW UserListingsInfo AS
SELECT u.username,
       u.userRating,
       l.userId,
       l.listingId,
       l.description,
       l.city,
       l.state,
       l.startDate,
       l.endDate,
       b.bookCondition,
       b.price,
       p.photoSource
FROM User as u
INNER JOIN  Listing as l ON u.userId = l.userId
INNER JOIN Book as b ON l.listingId = b.listingId
INNER JOIN Photos as p ON b.listingId = p.listingId

-- CREATE VIEW UserListingsInfo AS
-- SELECT *
-- FROM Photos
-- INNER JOIN (
--   SELECT * 
--   FROM Listing
--   INNER JOIN Book
--   ON Listing.listingId = Book.listingId
-- ) j ON Photos.listingId = j.listingId;