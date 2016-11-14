<!doctype html>
<html>
  <head>
    <title>Bliss Carpet Cleaning - DB Setup</title>
  </head>
  <body>
    <pre>Setting up `blisscarpetcleaning` database...</pre>
<?php
include "settings.php";
$sqlCreateCategoryTable = <<<__SQL
CREATE TABLE `category` (
  `keyCategory` INT(11) PRIMARY KEY AUTO_INCREMENT COMMENT 'Unique internal identifier for this service category',
  `nameCategory` VARCHAR(255) UNIQUE KEY NOT NULL COMMENT 'Unique name for this service category'
);
__SQL;
$sqlCreateServiceTable = <<<__SQL
CREATE TABLE `service` (
  `keyService` INT(11) PRIMARY KEY AUTO_INCREMENT COMMENT 'Unique internal identifier for this service',
  `keyCategory` INT(11) NOT NULL COMMENT 'Unique internal identifier for category to which this service belongs',
  `nameFormVar` VARCHAR(255) UNIQUE KEY NOT NULL COMMENT 'Form variable used to populate counts for this service',
  `nameService` VARCHAR(255) NOT NULL COMMENT 'Unique name for this service',
  `allowPartialUnits` TINYINT(1) NOT NULL DEFAULT 0 COMMENT 'Allow partial units for this service?',
  `unitSingular` VARCHAR(255) NOT NULL COMMENT 'Singular form of unit for measuring this service',
  `unitPlural` VARCHAR(255) NOT NULL COMMENT 'Plural form of unit for measuring this service',
  `costStandard` DECIMAL(6,2) NOT NULL DEFAULT 0 COMMENT 'Standard cost for one instance of this service',
  UNIQUE KEY (`keyCategory`, `nameService`),
  FOREIGN KEY (`keyCategory`)
    REFERENCES `category`(`keyCategory`)
    ON DELETE CASCADE
    ON UPDATE CASCADE
);
__SQL;
$sqlCreateSpecialTable = <<<__SQL
CREATE TABLE `special` (
  `keySpecial` INT(11) PRIMARY KEY AUTO_INCREMENT COMMENT 'Unique internal identifier for this special',
  `nameSpecial` VARCHAR(255) UNIQUE KEY NOT NULL COMMENT 'Unique name for this special',
  `codeRequired` VARCHAR(255) COMMENT 'Code, if any, required to activate this special',
  `dateBegin` DATE NOT NULL DEFAULT '0000-01-01' COMMENT 'Date after which this special is valid',
  `dateEnd` DATE NOT NULL DEFAULT '9999-12-31' COMMENT 'Date before which this special is valid',
  `isExclusive` TINYINT(1) NOT NULL DEFAULT 0 COMMENT 'Can be combined with other offers?',
  `blocksExclusive` TINYINT(1) NOT NULL DEFAULT 0 COMMENT 'Prevents use of exclusive offers?'
);
__SQL;
$sqlCreateSpecialEffectTable = <<<__SQL
CREATE TABLE `special_effect` (
  `keySpecialEffect` INT(11) PRIMARY KEY AUTO_INCREMENT COMMENT 'Unique internal identifier for this special effect',
  `keySpecial` INT(11) NOT NULL COMMENT 'Unique internal identifier for the special which effect is defined',
  `keyService` INT(11) COMMENT 'Unique internal identifier for the service (entire quote if NULL) affected',
  `countMax` INT(11) DEFAULT 1 COMMENT 'Maximum count of service units (NULL=all, ignored if entire quote) affected',
  `typeDiscount` ENUM('fixRed', 'fixCost', 'pctRed') NOT NULL DEFAULT 'fixRed'
    COMMENT 'Type of discout: fixed reduction, fixed cost, percent reduction',
  `rateDiscount` DECIMAL(6,2) NOT NULL DEFAULT 0
    COMMENT 'Rate associated with discount: fixRed => dollars discounted; fixCost => end cost; pctRed => multiplier between 0 and 1 of original amount to deduct',
  FOREIGN KEY (`keySpecial`)
    REFERENCES `special`(`keySpecial`)
    ON DELETE CASCADE
    ON UPDATE CASCADE,
  FOREIGN KEY (`keyService`)
    REFERENCES `service`(`keyService`)
    ON DELETE CASCADE
    ON UPDATE CASCADE
);
__SQL;
$sqlCreateSpecialPrereqTable = <<<__SQL
CREATE TABLE `special_prereq` (
  `keySpecialPrereq` INT(11) PRIMARY KEY AUTO_INCREMENT COMMENT 'Unique internal identifier for this special prereq',
  `keySpecial` INT(11) NOT NULL COMMENT 'Unique internal identifier for the special associated with this prereq',
  `keyService` INT(11) NOT NULL COMMENT 'Unique internal identifier for the service associated with this prereq',
  `countMin` INT(11) NOT NULL DEFAULT 0 COMMENT 'Minimum count of service units required to meet this prereq',
  `countMax` INT(11) COMMENT 'Maximum count of service units allowed to meet this prereq',
  FOREIGN KEY (`keySpecial`)
    REFERENCES `special`(`keySpecial`)
    ON DELETE CASCADE
    ON UPDATE CASCADE,
  FOREIGN KEY (`keyService`)
    REFERENCES `service`(`keyService`)
    ON DELETE CASCADE
    ON UPDATE CASCADE
);
__SQL;
$sqlCreateOrderTable = <<<__SQL
CREATE TABLE `order` (
  `keyOrder` INT(11) PRIMARY KEY AUTO_INCREMENT COMMENT 'Unique internal identifier for this order',
  `nameCustomer` VARCHAR(1024) NOT NULL COMMENT 'Name of customer placing the order',
  `emailAddress` VARCHAR(255) NOT NULL COMMENT 'Email address provided by customer',
  `phoneNumber` VARCHAR(50) NOT NULL COMMENT 'Phone number provided by customer',
  `preferenceContact` ENUM('email', 'phone') NOT NULL DEFAULT 'email' COMMENT 'Preferred method of contact',
  `addressStreet1` VARCHAR(255) COMMENT 'Street address (Line 1) of the service location',
  `addressStreet2` VARCHAR(255) COMMENT 'Street address (Line 2) of the service location',
  `addressCity` VARCHAR(255) COMMENT 'City associated with the service location',
  `addressZip` VARCHAR(10) COMMENT 'ZIP Code associated with the service location',
  `hasSpareCarpet` TINYINT(1) COMMENT 'Customer indicated spare carpet is available for repairs',
  `textComment` TEXT COMMENT 'Additional notes provided by the customer',
  `dateQuoted` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP COMMENT 'Date and time quote request was received',
  `dateAppointment` DATETIME COMMENT 'Date and time customer suggested for appointment',
  `dateServiced` DATETIME COMMENT 'Date and time service was provided in response to this quote',
  `amountQuoted` DECIMAL (8, 2) NOT NULL DEFAULT 0 COMMENT 'Total amount quoted (may deviate from calculated amount)'
);
__SQL;
$sqlCreateOrderServiceTable = <<<__SQL
CREATE TABLE `order_service` (
  `keyOrderService` INT(11) PRIMARY KEY AUTO_INCREMENT
    COMMENT 'Unique internal identifier for this order/service association',
  `keyOrder` INT(11) NOT NULL COMMENT 'Unique internal identifier for the associated order',
  `keyService` INT(11) NOT NULL COMMENT 'Unique internal identifier for the associated service',
  `countUnits` DECIMAL(8, 2) NOT NULL DEFAULT 0 COMMENT 'Count of service units associated with this order',
  UNIQUE KEY (`keyOrder`, `keyService`),
  FOREIGN KEY (`keyOrder`)
    REFERENCES `order`(`keyOrder`)
    ON DELETE CASCADE
    ON UPDATE CASCADE,
  FOREIGN KEY (`keyService`)
    REFERENCES `service`(`keyService`)
    ON DELETE RESTRICT
    ON UPDATE CASCADE
);
__SQL;
$sqlCreateOrderSpecialTable = <<<__SQL
CREATE TABLE `order_special` (
  `keyOrderSpecial` INT(11) PRIMARY KEY AUTO_INCREMENT
    COMMENT 'Unique internal identifier for this order/special association',
  `keyOrder` INT(11) NOT NULL COMMENT 'Unique internal identifier for the associated order',
  `keySpecial` INT(11) NOT NULL COMMENT 'Unique internal identifier for the associated special',
  `amountDiscount` DECIMAL(8, 2) NOT NULL DEFAULT 0 COMMENT 'Calculated amount of effective cost reduction',
  UNIQUE KEY (`keyOrder`, `keySpecial`),
  FOREIGN KEY (`keyOrder`)
    REFERENCES `order`(`keyOrder`)
    ON DELETE CASCADE
    ON UPDATE CASCADE,
  FOREIGN KEY (`keySpecial`)
    REFERENCES `special`(`keySpecial`)
    ON DELETE RESTRICT
    ON UPDATE CASCADE
);
__SQL;
$sqlPopulateCategoryTable = <<<__SQL
INSERT INTO `category` (`nameCategory`)
VALUES
  ('Air Duct Cleaning'), -- 1
  ('Carpet Cleaning'), -- 2
  ('Carpet Repair and Dying'), -- 3
  ('Tile and Grout Cleaning'), -- 4
  ('Upholstery Cleaning'), -- 5
  ('Vehicle Cleaning'), -- 6
  ('Wood Floor Cleaning'); -- 7
__SQL;
$sqlPopulateServiceTable = <<<__SQL
INSERT INTO `service` (
  `keyCategory`, `nameFormVar`, `nameService`, `allowPartialUnits`, `unitSingular`, `unitPlural`, `costStandard`
)
VALUES
  (1, 'air_duct_count_vents', 'Number of Vents', 0, 'vent', 'vents', 15.00), -- 1
  (2, 'carpet_cleaning_count_rooms', 'Number of Rooms', 0, 'room', 'rooms', 25.00), -- 2
  (2, 'carpet_cleaning_count_halls', 'Number of Hallways', 0, 'hallway', 'hallways', 15.00), -- 3
  (2, 'carpet_cleaning_count_stairs', 'Number of Stairways', 0, 'stairway', 'stairways', 20.00), -- 4
  (2, 'carpet_cleaning_count_protect', 'Rooms to Scotch Guard', 0, 'room', 'rooms', 15.00), -- 5
  (3, 'carpet_repair_count_patches', 'Number of Patches', 0, 'patch', 'patches', 50.00), -- 6
  (3, 'carpet_repair_count_dye', 'Number of Rooms to Dye', 0, 'room', 'rooms', 100.00), -- 7
  (4, 'tile_cleaning_area', 'Estimated Sq. Ft.', 1, 'sq. ft.', 'sq. ft.', 0.79), -- 8
  (5, 'upholstery_count_lg', 'Large Items', 0, 'item', 'items', 25.00), -- 9
  (5, 'upholstery_count_sm', 'Small Items', 0, 'item', 'items', 15.00), -- 10
  (6, 'vehicle_cleaning_count_lg', 'Large Vehicles', 0, 'vehicle', 'vehicles', 75.00), -- 11
  (6, 'vehicle_cleaning_count_sm', 'Small Vehicles', 0, 'vehicle', 'vehicles', 50.00), -- 12
  (7, 'wood_cleaning_area', 'Estimated Sq. Ft.', 1, 'sq.ft.', 'sq. ft.', 0.79); -- 13
__SQL;
$sqlPopulateSpecialTable = <<<__SQL
INSERT INTO `special` (`nameSpecial`, `codeRequired`, `dateBegin`, `dateEnd`, `isExclusive`, `blocksExclusive`)
VALUES
  ('Five Rooms for $99', NULL, '0000-01-01', '9999-12-31', 0, 0);
__SQL;
$sqlPopulateSpecialEffectTable = <<<__SQL
INSERT INTO `special_effect` (`keySpecial`, `keyService`, `countMax`, `typeDiscount`, `rateDiscount`)
VALUES
  (1, 2, 5, 'fixCost', 19.80), -- Five rooms for $99 (99 / 5 = 19.80)
  (1, 3, 2, 'fixCost', 0.00) -- Five rooms for $99, up to 2 hallways free
__SQL;
$sqlPopulateSpecialPrereqTable = <<<__SQL
INSERT INTO `special_prereq` (`keySpecial`, `keyService`, `countMin`, `countMax`)
VALUES
  (1, 2, 5, NULL) -- Five Rooms for $99 (at least 5 rooms quoted)
__SQL;
$structsSetup = array(
  "category" => array(
    "test" => "SHOW TABLES LIKE 'category';",
    "exec" => $sqlCreateCategoryTable,
    "populate" => $sqlPopulateCategoryTable
  ),
  "service" => array(
    "test" => "SHOW TABLES LIKE 'service';",
    "exec" => $sqlCreateServiceTable,
    "populate" => $sqlPopulateServiceTable
  ),
  "special" => array(
    "test" => "SHOW TABLES LIKE 'special';",
    "exec" => $sqlCreateSpecialTable,
    "populate" => $sqlPopulateSpecialTable
  ),
  "special_effect" => array(
    "test" => "SHOW TABLES LIKE 'special_effect';",
    "exec" => $sqlCreateSpecialEffectTable,
    "populate" => $sqlPopulateSpecialEffectTable
  ),
  "special_prereq" => array(
    "test" => "SHOW TABLES LIKE 'special_prereq';",
    "exec" => $sqlCreateSpecialPrereqTable,
    "populate" => $sqlPopulateSpecialPrereqTable
  ),
  "order" => array(
    "test" => "SHOW TABLES LIKE 'order';",
    "exec" => $sqlCreateOrderTable
  ),
  "order_service" => array(
    "test" => "SHOW TABLES LIKE 'order_service';",
    "exec" => $sqlCreateOrderServiceTable
  ),
  "order_special" => array(
    "test" => "SHOW TABLES LIKE 'order_special';",
    "exec" => $sqlCreateOrderSpecialTable
  )
);
// Establish a connection to the database.
$pdoCnxn = new PDO(
  "mysql:host={$dbSettings['hostname']};dbname={$dbSettings['database']}",
  $dbSettings["username"],
  $dbSettings["password"]
);
echo "<pre>Connected.</pre>";
// Iterate through the setup checklist.
foreach($structsSetup as $key => $structSetup) {
  echo "<pre>";
  // Check for existence of this item.
  $results = $pdoCnxn->query($structSetup["test"])->fetchAll();
  echo "Checking for `$key`.\n";
  echo "  " . count($results) . " record(s) found.\n";
  if (count($results) === 0) {
    // The item does not exist. Execute the statement to create it.
    $countRecords = $pdoCnxn->exec($structSetup["exec"]);
    if ($countRecords === FALSE) {
      echo "  Exception raised: " . $pdoCnxn->errorInfo()[2] . "\n";
    } else {
      echo "  Creation statement executed. $countRecords record(s) affected.\n";
    }
  }
  // Is there a statement to populate the item?
  if (array_key_exists("populate", $structSetup)) {
    // Yes, a statement exists to populate the item. Execute that statement.
    $countRecords = $pdoCnxn->exec($structSetup["populate"]);
    if ($countRecords === FALSE) {
      echo "  Exception raised: " . $pdoCnxn->errorInfo()[2] . "\n";
    } else {
      echo "  Populate statement executed. $countRecords record(s) affected.\n";
    }
  }
  echo "</pre>";
}
$pdoCnxn = null;
?>
  </body>
</html>
