# Alba Ferries (Alba Wildlife)

Alba Ferries is a website made as part of a larger academic project - creating and designing a ferry booking web-system for a ficticious company.

# Setup

If the website is to be set up on a **local device** then the following steps have to be undertaken.

## DB Setup  

A local mySQL/mariaDB server has to be configured with the following:

```sql
CREATE DATABASE Alba;
```

Create the database with the name "Alba".

```sql
CREATE USER 'admin'@'localhost' IDENTIFIED by 'admin';
```

Create a new user called admin at localhost, with the password the same as the username.

*Different details can be chosen, however take care to substitute any custom details in later steps that require 'admin'*

```sql
GRANT ALL PRIVILEGES ON Alba.* TO 'admin'@'localhost'
```

Grant all privilges to the user.

```sql
FLUSH PRIVILEGES
```

Apply changes to privileges.

**Remember to create & populate the required tables within the database via the create tables sql script.**

## DB Credentials

*This step is only required if custom details were chosen.*

Navigate to ``php/db/DbConnect.php``.

```php
<?php
    DEFINE ('DB_USER', 'admin'); //Put custom username here
    DEFINE ('DB_PASSWORD','admin'); //Put custom password here
    DEFINE ('DB_HOST', 'localhost'); //Put custom database host here
    DEFINE ('DB_NAME', 'Alba'); //Put custom database name here
    $DB = mysqli_connect(DB_HOST,DB_USER,DB_PASSWORD,DB_NAME);
    if (mysqli_connect_errno())
    {
    echo 'Cannot connect to the database: ' . mysqli_connect_error();
    }
?>
```

# SQL

## Create Script (Tables & Data)

If, for whatever reason, the sql files have not been provided the tables - and their data - can be generated with the following sql script:

```sql
use Alba;

CREATE TABLE IF NOT EXISTS AlbaCustomer (
	CustomerEmail varchar(50) NOT NULL,
	CustomerForename varchar(30) NOT NULL,
    CustomerSurname varchar(40) NOT NULL,
    CustomerDOB date NOT NULL,
    CustomerPhone varchar(11),
    CustomerTier varchar(5) NOT NULL,
    CustomerPassword varchar(500) NOT NULL, -- Large length to accomodate for hashes
    primary key (CustomerEmail)
);

CREATE TABLE IF NOT EXISTS AlbaDiscount (
	DiscountID int(2) NOT NULL,
    DiscountValue int(3) NOT NULL,
    DiscountName varchar(30) NOT NULL,
    primary key (DiscountID)
);

CREATE TABLE IF NOT EXISTS AlbaCustomerDiscount (
	CustomerEmail varchar(50) NOT NULL,
    DiscountID int(2) NOT NULL,
    primary key (CustomerEmail, DiscountID),
    foreign key (CustomerEmail) references AlbaCustomer (CustomerEmail),
    foreign key (DiscountID) references AlbaDiscount (DiscountID)
);

CREATE TABLE IF NOT EXISTS AlbaRoute (
	RouteNo int(2) NOT NULL,
    RouteDepart varchar(20) NOT NULL,
    RouteDestination varchar(20) NOT NULL,
    primary key (RouteNo)
);

CREATE TABLE IF NOT EXISTS AlbaFerry (
	FerryNo int(8) NOT NULL,
    RouteNo int(2) NOT NULL,
    FerrySeats int(3) NOT NULL,
    FerryReturn dec(5,2) NOT NULL,
    FerrySingle dec(5,2) NOT NULL,
    FerryDepart time NOT NULL,
    FerryArrive time NOT NULL,
    FerryStart date NOT NULL,
	FerryEnd date NOT NULL,
	FerryDay char(3) NOT NULL,
    primary key (FerryNo),
    foreign key (RouteNo) references AlbaRoute (RouteNo)
);

CREATE TABLE IF NOT EXISTS AlbaPassenger (
	PassengerNo int(3) NOT NULL,
    PassengerForename varchar(30) NOT NULL,
    PassengerSurname varchar(40) NOT NULL,
    PassengerAgeBracket varchar(10) NOT NULL,
    PassengerWheelchair bool NOT NULL,
    primary key (PassengerNo)
);

CREATE TABLE IF NOT EXISTS AlbaBooking (
	BookingNo int(12) NOT NULL AUTO_INCREMENT,
    CustomerEmail varchar(50) NOT NULL,
    PassengerNo int(3) NOT NULL,
    FerryNo int(8) NOT NULL,
    BookingStatus varchar(10),
    primary key (BookingNo),
    foreign key (CustomerEmail) references AlbaCustomer (CustomerEmail),
    foreign key (PassengerNo) references AlbaPassenger (PassengerNo),
    foreign key (FerryNo) references AlbaFerry (FerryNo)
);

INSERT INTO AlbaRoute (RouteNo, RouteDepart, RouteDestination) VALUES
(1, 'Mallaig', 'Eigg'),
(2, 'Mallaig', 'Muck'),
(3, 'Mallaig', 'Rum'),
(4, 'Eigg', 'Muck'),
(5, 'Eigg', 'Rum'),
(6, 'Muck', 'Eigg'),
(7, 'Eigg', 'Mallaig'),
(8, 'Rum', 'Eigg'),
(9, 'Rum', 'Mallaig')
;

INSERT INTO AlbaFerry (FerryNo, RouteNo, FerrySeats, FerryReturn, FerrySingle, FerryDepart, FerryArrive, FerryStart, FerryEnd, FerryDay) VALUES 
-- Monday, Eigg & Muck, 13th May - 18th Oct
(1, 1, 30, 18.00, 18.00 * 0.7, '11:00:00', '12:00:00', '2025-05-13', '2025-09-18', 'Mon'),
(2, 4, 30, 10.00, 10.00 * 0.7, '12:30:00', '13:30:00', '2025-05-13', '2025-09-18', 'Mon'),
(3, 6, 30, 10.00, 10.00 * 0.7, '15:30:00', '16:00:00', '2025-05-13', '2025-09-18', 'Mon'),
(4, 7, 30, 18.00, 18.00 * 0.7, '16:30:00', '17:30:00', '2025-05-13', '2025-09-18', 'Mon'),
-- Tuesday, Eigg & Rum, 13th May - 18th Oct
(5, 1, 30, 18.00, 18.00 * 0.7, '11:00:00', '12:00:00', '2025-05-13', '2025-09-18', 'Tue'),
(6, 5, 30, 16.00, 16.00 * 0.7, '12:30:00', '13:30:00', '2025-05-13', '2025-09-18', 'Tue'),
(7, 8, 30, 16.00, 16.00 * 0.7, '15:30:00', '16:00:00', '2025-05-13', '2025-09-18', 'Tue'),
(8, 7, 30, 18.00, 18.00 * 0.7, '16:30:00', '17:30:00', '2025-05-13', '2025-09-18', 'Tue'),
-- Wednesday, Eigg & Muck, 13th May - 18th Oct
(9, 1, 30, 18.00, 18.00 * 0.7, '11:00:00', '12:00:00', '2025-05-13', '2025-09-18', 'Wed'),
(10, 4, 30, 10.00, 10.00 * 0.7, '12:30:00', '13:30:00', '2025-05-13', '2025-09-18', 'Wed'),
(11, 6, 30, 10.00, 10.00 * 0.7, '15:30:00', '16:00:00', '2025-05-13', '2025-09-18', 'Wed'),
(12, 7, 30, 18.00, 18.00 * 0.7, '16:30:00', '17:30:00', '2025-05-13', '2025-09-18', 'Wed'),
-- Thursday, Rum, 13th May -18th Oct
(13, 3, 30, 24.00, 24.00 * 0.7, '11:00:00', '12:45:00', '2025-05-13', '2025-09-18', 'Thu'),
(14, 9, 30, 24.00, 24.00 * 0.7, '15:45:00', '17:30:00', '2025-05-13', '2025-09-18', 'Thu'),
-- Friday, Eigg & Muck, 13th May - 18th Oct
(15, 1, 30, 18.00, 18.00 * 0.7, '11:00:00', '12:00:00', '2025-05-13', '2025-09-18', 'Fri'),
(16, 4, 30, 10.00, 10.00 * 0.7, '12:30:00', '13:30:00', '2025-05-13', '2025-09-18', 'Fri'),
(17, 6, 30, 10.00, 10.00 * 0.7, '15:30:00', '16:00:00', '2025-05-13', '2025-09-18', 'Fri'),
(18, 7, 30, 18.00, 18.00 * 0.7, '16:30:00', '17:30:00', '2025-05-13', '2025-09-18', 'Fri'),
-- Saturday, Eigg & Rum, Jun-Aug
(19, 1, 30, 18.00, 18.00 * 0.7, '11:00:00', '12:00:00', '2025-06-01', '2025-08-31', 'Sat'),
(20, 5, 30, 16.00, 16.00 * 0.7, '12:30:00', '13:30:00', '2025-06-01', '2025-08-31', 'Sat'),
(21, 8, 30, 16.00, 16.00 * 0.7, '15:30:00', '16:00:00', '2025-06-01', '2025-08-31', 'Sat'),
(22, 7, 30, 18.00, 18.00 * 0.7, '16:30:00', '17:30:00', '2025-06-01', '2025-08-31', 'Sat'),
-- Sunday, Eigg & Much, Jun-Aug
(23, 1, 30, 18.00, 18.00 * 0.7, '11:00:00', '12:00:00', '2025-06-01', '2025-08-31', 'Sun'),
(24, 4, 30, 10.00, 10.00 * 0.7, '12:30:00', '13:30:00', '2025-06-01', '2025-08-31', 'Sun'),
(25, 6, 30, 10.00, 10.00 * 0.7, '15:30:00', '16:00:00', '2025-06-01', '2025-08-31', 'Sun'),
(26, 7, 30, 18.00, 18.00 * 0.7, '16:30:00', '17:30:00', '2025-06-01', '2025-08-31', 'Sun')
;
```