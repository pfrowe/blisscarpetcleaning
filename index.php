<!doctype html>
<?php
/**
 * @author Paul Rowe
 * @copyright 2016-11-05
 *
 * This website was created for Bliss Carpet Cleaning in Houston, TX, by Paul Rowe.
 **/
/**
 * The settings.php file defines values for $dbSettings and $emailSettings.
 * $dbSettings = [ "hostname", "database", "username", "password" ];
 * $emailSettings = [ "from", "host", "port", "replyTo", "subject", "to", "username", "password" ];
 **/
include "settings.php";
/**
 * This array will contain error messages. The messages are displayed at the top of the page and those messages link
 * (via JavaScript in js/bcc.base.js) to the relevant form control. Any form control which name is found in
 * $errorMessages is highlighted as "in error" and the error message is additionally displayed
 * just below the form control (or its description, if it has one).
 **/
$errorMessages = array();
/**
 * This array contains information about the service fields and their groupings. The top level of the array stores
 * the groups into which the fields are separated. Each group has two members: header and fields. The header stores
 * the text to display at the head of the group. The fields member is an array with information about the fields
 * belonging to that group. Within fields, each member is named for its control and contains the following members:
 * label (required), type (required), and hint (optional). The isFieldsetEmpty uses the grouping to see if a
 * particularly group has any user-provided data. Code near the end of the content-contact tab-pane utilizes
 * $fieldsServices to generate the Services portion of the form.
 **/
$fieldsServices = array(
  "carpet_cleaning" => [
    "header" => "Carpet Cleaning",
    "fields" => [
      "carpet_cleaning_count_rooms" => [
        "label" => "Number of rooms",
        "type" => "number"
      ],
      "carpet_cleaning_count_halls" => [
        "label" => "Number of hallways",
        "type" => "number"
      ],
      "carpet_cleaning_count_stairs" => [
        "label" => "Number of staircases",
        "type" => "number"
      ],
      "carpet_cleaning_count_protect" => [
        "label" => "Rooms to Scotch Guard",
        "type" => "number"
      ]
    ]
  ],
  "wood_cleaning" => [
    "header" => "Wood Floor Cleaning",
    "fields" => [
      "wood_cleaning_area" => [
        "label" => "Estimated square footage",
        "type" => "number"
      ]
    ]
  ],
  "tile_cleaning" => [
    "header" => "Tile & Grout Cleaning",
    "fields" => [
      "tile_cleaning_area" => [
        "label" => "Estimated square footage",
        "type" => "number"
      ]
    ]
  ],
  "upholstery" => [
    "header" => "Upholstery Cleaning",
    "fields" => [
      "upholstery_count_lg" => [
        "hint" => "Include couches, sofas, reclining chairs, and other items of similar size or complexity.",
        "label" => "Number of large items",
        "type" => "number"
      ],
      "upholstery_count_sm" => [
        "hint" => "Include armchairs, benches, footstools, and other items of similar size or complexity.",
        "label" => "Number of small items",
        "type" => "number"
      ]
    ]
  ],
  "carpet_repair" => [
    "header" => "Carpet Repair & Dying",
    "fields" => [
      "carpet_repair_count_patches" => [
        "label" => "Estimated number of patches",
        "type" => "number"
      ],
      "carpet_repair_have_extra" => [
        "label" => "I have spare matching carpet to use for patches.",
        "type" => "checkbox"
      ],
      "carpet_repair_count_dye" => [
        "label" => "Number of rooms to dye",
        "type" => "number"
      ]
    ]
  ],
  "air_duct" => [
    "header" => "Air Duct Cleaning",
    "fields" => [
      "air_duct_count_vents" => [
        "label" => "Number of vents",
        "type" => "number"
      ]
    ]
  ]
);
/**
 * This array contains information pertinent to validation form input from the Get a Quote page. It is also used by
 * isFieldsetEmpty to determine if the value provided via the form differs from the value that would be specified by
 * default. Each member of the $validation array is named according to a form control and contains a default member
 * with the default value for that field. It will also contain at least one of the following: rangeNumeric, regex,
 * and required.
 * When validateInput is called, it checks for the required member first. The required member will have three
 * members: whenToTest, testExpression, and messageFailure. The whenToTest member has a value that is evaluated to
 * determine if the field should be required. (In this situation, customer_email is only required if customer_prefer
 * is set to "email".) The testExpression member has a value that is evaluated to determine if the value passes the
 * requirement test. The messageFailure member has a string that is displayed with the required test fails.
 * If the required test passes, validateInput checks for the rangeNumeric member next. The rangeNumeric member will
 * have up to three members. The rangeMax member indicates the maximum allowable value; the rangeMin member indicates
 * the minimum allowable value; the messageFailure member provides an error string displayed when range validation
 * fails.
 * If all tests so far have passed, validateInput checks for the regex member. The regex member will have three
 * members. The whenToTest member will indicate when the field should be tested (so long as it isn't empty). The
 * patternToTest member will include a regular expression which should match a valid pattern and fail otherwise. The
 * messageFailure member will include an error string displayed when regular expression validation fails.
 **/
$validation = array(
  "customer_name" => [
    "default" => "",
    "required" => [
      "whenToTest" => true,
      "testExpression" => '$customer_name != ""',
      "messageFailure" => "Please provide your name or the name of your business."
    ]
  ],
  "customer_email" => [
    "default" => "",
    "regex" => [
      "whenToTest" => true,
      "patternToTest" => "/^[A-Z0-9._%+-]+@([A-Z0-9-]+\.)+[A-Z]{2,}$/i",
      "messageFailure" => "Please provide a valid email address."
    ],
    "required" => [
      "whenToTest" => '$customer_prefer == "email"',
      "testExpression" => '$customer_email !== ""',
      "messageFailure" => "Please provide your email address if you prefer we email you."
    ]
  ],
  "customer_phone" => [
    "default" => "",
    "regex" => [
      "whenToTest" => true,
      "patternToTest" => "/^(\([0-9]{3}\) |[0-9]{3}[ .-]?)[0-9]{3}[ .-]?[0-9]{4}$/",
      "messageFailure" => "Please provide a valid phone number and include the area code."
    ],
    "required" => [
      "whenToTest" => '$customer_prefer == "phone"',
      "testExpression" => '$customer_phone !== ""',
      "messageFailure" => "Please provide your phone number if you prefer we call you."
    ]
  ],
  "customer_prefer" => [
    "default" => "",
    "regex" => [
      "whenToTest" => true,
      "patternToTest" => "/^email|phone$/",
      "messageFailure" => "Please select whether you would prefer us to contact you via email or phone."
    ],
    "required" => [
      "whenToTest" => true,
      "testExpression" => '$customer_prefer !== ""',
      "messageFailure" => "Please select whether you would prefer us to contact you via email or phone."
    ]
  ],
  "address_street1" => [
    "default" => "",
    "required" => [
      "whenToTest" => true,
      "testExpression" => '$address_street1 !== ""',
      "messageFailure" => "Please provide the street address for the location where service should be provided."
    ]
  ],
  "address_city" => [
    "default" => "",
    "required" => [
      "whenToTest" => true,
      "testExpression" => '$address_city !== ""',
      "messageFailure" => "Please provide the city associated with the location where service should be provided."
    ]
  ],
  "address_zip" => [
    "default" => "",
    "regex" => [
      "whenToTest" => true,
      "patternToTest" => "/^\\d{5}(\-\\d{4})?$/",
      "messageFailure" => "Please provide the ZIP Code associated with the location where service should be provided."
    ],
    "required" => [
      "whenToTest" => true,
      "testExpression" => '$address_zip !== ""',
      "messageFailure" => "Please provide the ZIP Code associated with the location where service should be provided."
    ]
  ],
  "appt_date" => [
    "default" => "",
    "rangeDate" => [
      "minDate" => new DateTime("tomorrow"),
      "messageFailure" => "Please select a date in the future (not today) for the suggested appointment date."
    ]
  ],
  "appt_time" => [
    "default" => "",
    "regex" => [
      "whenToTest" => true,
      "patternToTest" => "/(0?[1-9]|1[0-2]:[0-5][05] [AP]M)/i",
      "messageFailure" => "Please specify a valid suggested appointment time."
    ]
  ],
  "carpet_cleaning_count_rooms" => [
    "default" => "",
    "rangeNumeric" => [
      "rangeMin" => 0,
      "messageFailure" => "Please provide a non-negative count of rooms to clean."
    ]
  ],
  "carpet_cleaning_count_halls" => [
    "default" => "",
    "rangeNumeric" => [
      "rangeMin" => 0,
      "messageFailure" => "Please provide a non-negative count of hallways to clean."
    ]
  ],
  "carpet_cleaning_count_stairs" => [
    "default" => "",
    "rangeNumeric" => [
      "rangeMin" => 0,
      "messageFailure" => "Please provide a non-negative count of stairways to clean."
    ]
  ],
  "carpet_cleaning_count_protect" => [
    "default" => "",
    "rangeNumeric" => [
      "rangeMin" => 0,
      "messageFailure" => "Please provide a non-negative count of rooms to Scotch Guard."
    ]
  ],
  "wood_cleaning_area" => [
    "default" => "",
    "rangeNumeric" => [
      "rangeMin" => 0,
      "messageFailure" => "Please provide a non-negative estimate of square footage of wood floor to clean."
    ]
  ],
  "tile_cleaning_area" => [
    "default" => "",
    "rangeNumeric" => [
      "rangeMin" => 0,
      "messageFailure" => "Please provide a non-negative estimate of square footage of tile and grout to clean."
    ]
  ],
  "upholstery_count_lg" => [
    "default" => "",
    "rangeNumeric" => [
      "rangeMin" => 0,
      "messageFailure" => "Please provide a non-negative count of large furniture to clean."
    ]
  ],
  "upholstery_count_sm" => [
    "default" => "",
    "rangeNumeric" => [
      "rangeMin" => 0,
      "messageFailure" => "Please provide a non-negative count of small furniture to clean."
    ]
  ],
  "carpet_repair_count_patches" => [
    "default" => "",
    "rangeNumeric" => [
      "rangeMin" => 0,
      "messageFailure" => "Please provide a non-negative count of carpet patches to repair."
    ]
  ],
  "carpet_repair_have_extra" => [
    "default" => "0",
    "regex" => [
      "whenToTest" => true,
      "patternToTest" => "/^0|1$/",
      "messageFailure" => "Please specify whether you have spare carpet to use for patch repairs."
    ]
  ],
  "carpet_repair_count_dye" => [
    "default" => "",
    "rangeNumeric" => [
      "rangeMin" => 0,
      "messageFailure" => "Please provide a non-negative count of rooms to dye."
    ]
  ],
  "air_duct_count_vents" => [
    "default" => "",
    "rangeNumeric" => [
      "rangeMin" => 0,
      "messageFailure" => "Please provide a non-negative count of indoor vents for your air duct system."
    ]
  ]
);
/**
 * This function checks to see if a field group is empty. It uses the groupings defined in $fieldsServices and the
 * default values specified by $validation to perform this check.
 * @param $nameFieldset: The name of the fieldset (a member of $fieldsServices) which field values should be checked.
 * @return true if the values of fields within the group are not set or match their defaults; false otherwise.
 **/
function isFieldsetEmpty($nameFieldset) {
  $returnValue = true;
  global $fieldsServices, $validation;
  if (array_key_exists($nameFieldset, $fieldsServices)) {
    foreach ($fieldsServices[$nameFieldset]["fields"] as $nameField => $infoField) {
      try {
        $isFieldEmpty = !isset($_POST[$nameField]);
        if (isset($validation[$nameField])) {
          $isFieldEmpty = $isFieldEmpty || ($_POST[$nameField] == $validation[$nameField]["default"]);
        }
        $returnValue = $returnValue && $isFieldEmpty;
      } catch (Exception $e) {
        var_dump($nameField); die();
      }
    }
  }
  return $returnValue;
}
/**
 * This function checks to see if the field values provided via POST match the validation rules specified in
 * $validation. Refer to the documentation for $validation for more information about these rules. If validation
 * fails, one or more messages will be stored in $errorMessages.
 * @see $validation
 * @return void
 **/
function validateInput() {
  global $validation, $errorMessages;
  foreach ($_POST as $key => $value) {
    $$key = preg_replace("/^\s+|\s+$/", "", $value);
    $_POST[$key] = $$key;
  }
  foreach ($validation as $key => $fieldValidation) {
    if (!isset($$key) && isset($fieldValidation["default"])) {
      $$key = $fieldValidation["default"];
      $_POST[$key] = $$key;
    }
  }
  foreach ($validation as $key => $fieldValidation) {
    if (
      (isset($fieldValidation["required"]))
      && (eval("return (" . $fieldValidation["required"]["whenToTest"] . ");") == true)
    ) {
      $isValid = eval("return (" . $fieldValidation["required"]["testExpression"] . ");");
      if ($isValid === false) {
        $errorMessages[$key] = $fieldValidation["required"]["messageFailure"];
      }
    }
    if (isset($fieldValidation["rangeDate"])) {
      $dateTest = strtotime($$key);
      $isValid = $dateTest !== false;
      if ($isValid) {
        if (isset($fieldValidation["rangeDate"]["rangeMax"])) {
          $isValid = $isValid && ($dateTest <= $fieldValidation["rangeDate"]["rangeMax"]);
        }
        if (isset($fieldValidation["rangeDate"]["rangeMin"])) {
          $isValid = $isValid && ($dateTest >= $fieldValidation["rangeDate"]["rangeMin"]);
        }
      }
      if (($isValid === false) && (!isset($errorMessages[$key]))) {
        $errorMessages[$key] = $fieldValidation["rangeDate"]["messageFailure"];
      }
    }
    if (isset($fieldValidation["rangeNumeric"])) {
      $isValid = ($$key == "") || is_numeric($$key);
      if ($isValid && ($$key != "")) {
        if (isset($fieldValidation["rangeNumeric"]["rangeMax"])) {
          $isValid = $isValid && ($$key <= $fieldValidation["rangeNumeric"]["rangeMax"]);
        }
        if (isset($fieldValidation["rangeNumeric"]["rangeMin"])) {
          $isValid = $isValid && ($$key >= $fieldValidation["rangeNumeric"]["rangeMin"]);
        }
      }
      if (($isValid === false) && (!isset($errorMessages[$key]))) {
        $errorMessages[$key] = $fieldValidation["rangeNumeric"]["messageFailure"];
      }
    }
    if (
      (isset($fieldValidation["regex"]))
      && (eval("return (" . $fieldValidation["regex"]["whenToTest"] . ");") == true)
    ) {
      $isValid = ($$key == "") || preg_match($fieldValidation["regex"]["patternToTest"], $$key);
      if (($isValid === false) && (!isset($errorMessages[$key]))) {
        $errorMessages[$key] = $fieldValidation["regex"]["messageFailure"];
      }
    }
  }
}
/**
 * This function commits the values provided via POST to the database. It assumes that validation has already been
 * performed on the form input and passed.
 * @return void
 **/
function postValues() {
  try {
    global $dbSettings, $validation;
    /* This SQL statement is responsible for inserting the order record. */
    $sqlInsertOrder = <<<__SQL
      INSERT INTO `order`
      SET
        `nameCustomer` = :customer_name,
        `emailAddress` = :customer_email,
        `phoneNumber` = :customer_phone,
        `preferenceContact` = :customer_prefer,
        `addressStreet1` = :address_street1,
        `addressStreet2` = :address_street2,
        `addressCity` = :address_city,
        `addressZip` = :address_zip,
        `dateAppointment` = :appt_date_time,
        `hasSpareCarpet` = :carpet_repair_have_extra,
        `textComment` = :comment_text;
__SQL;
    /* This SQL statement is instrumental for matching form control names with their service keys. */
    $sqlSelectServiceList = <<<__SQL
      SELECT `keyService`, `nameFormVar`
      FROM `service`;
__SQL;
    /* This SQL statement is used to insert records linking the new order with its associated services. */
    $sqlInsertService = <<<__SQL
      INSERT INTO `order_service`
      SET
        `keyOrder` = :keyNewOrder,
        `keyService` = :keyService,
        `countUnits` = :countUnits;
__SQL;
    /* Special handling for appt_date and appt_time fields. */
    if ($_POST["appt_date"] . $_POST["appt_time"] != "") {
      $dateAppointment =
        ($_POST["appt_date"] != "")
        ? $_POST["appt_date"]
        : (new DateTime("tomorrow"))->format("m/d/Y");
      $timeAppointment = ($_POST["appt_time"] != "") ? $_POST["appt_time"] : "08:00 AM";
      $_POST["appt_date_time"] = (new DateTime("$dateAppointment $timeAppointment"))->format("Y-m-d H:i");
    }
    /* Get the list of parameters from $sqlInsertOrder. */
    preg_match_all("/\:[\w_]+/", $sqlInsertOrder, $paramsDefined);
    /* Set up an empty array to store parameter values. */
    $valuesParams = array();
    /*
     * Loop through the list of parameters (which share names with form inputs) and assign their values in
     * $valuesParams.
     */
    foreach ($paramsDefined[0] as $index => $paramName) {
      $valuesParams[$paramName] = $_POST[preg_replace("/^\:/", "", $paramName)];
    }
    /* Create the connection to the database. */
    $pdoCnxn = new PDO(
      "mysql:host={$dbSettings['hostname']};dbname={$dbSettings['database']}",
      $dbSettings["username"],
      $dbSettings["password"]
    );
    /* Prepare the INSERT statement for the order record. */
    $stmtInsertOrder = $pdoCnxn->prepare($sqlInsertOrder);
    /* Execute the INSERT statement, attaching the parameter values. */
    $stmtInsertOrder->execute($valuesParams);
    /* Get the key for the new order record. */
    $keyNewOrder = $pdoCnxn->lastInsertId();
    /* Get all the service records so we can loop through them. */
    $resultsServices = $pdoCnxn->query($sqlSelectServiceList)->fetchAll();
    /* Loop through the service records. */
    foreach ($resultsServices as $index => $currentRow) {
      /*
       * All of these fields are numeric and, if requested, positive values. Check to see if they are set and greater
       * than zero.
       */
      if (isset($_POST[$currentRow["nameFormVar"]]) && ($_POST[$currentRow["nameFormVar"]] > 0)) {
        /* This service has been requested. Prepare the INSERT statement for its order_service record. */
        $stmtInsertService = $pdoCnxn->prepare($sqlInsertService);
        /* Bind the value of the order key. */
        $stmtInsertService->bindParam(":keyNewOrder", $keyNewOrder);
        /* Bind the value of the service key. */
        $stmtInsertService->bindParam(":keyService", $currentRow["keyService"]);
        /* Bind the value of the service count. */
        $stmtInsertService->bindParam(":countUnits", $_POST[$currentRow["nameFormVar"]]);
        /* Execute the INSERT statement. */
        $stmtInsertService->execute();
      }
    }
  } catch (Exception $e) {
    var_dump($e); die();
  }
}
$doPost = false;
/* Did the user submit a form post? */
$tryPost = (isset($_POST["bcc_quote_submit"]) && ($_POST["bcc_quote_submit"] == 1));
if ($tryPost === true) {
  /* Yes, the user submitted a form post. Is the input valid? */
  validateInput();
  /* If the input is valid, $errorMessages should have zero members and we can continue posting to the database. */
  $doPost = count($errorMessages) === 0;
  /* Was the input valid? */
  if ($doPost) {
    /* Yes, the input was valid. Post the order to the database. */
    postValues();
  }
}
?>
<html lang="en">
  <head>
    <title>Bliss Carpet Cleaning - Serving Houston, TX</title>
    <meta charset="utf-8" />
    <meta name="description"
          content="Bliss Carpet Cleaning is a family-owned and -operated business serving Houston, TX, and providing services including: carpet cleaning, wood floor cleaning, tile and grout cleaning, upholstery cleaning, carpet repair, carpet dying, and air duct cleaning." />
    <meta name="keywords"
          content="Bliss Carpet Cleaning, Perfection Carpet Cleaning, carpet cleaning, rug cleaning, hardwood floor cleaning, wood floor cleaning, hardwood polishing, tile cleaning, grout cleaning, tile and grout cleaning, upholstery cleaning, couch cleaning, sofa cleaning, armchair cleaning, carpet repair, carpet dye, carpet dying, air duct cleaning, air vent cleaning, vehicle cleaning, Scotch Guard, pre-treatment, water rinse, truck-mounted, anti-microbial, hypoallergenic, Houston, Cypress, Tomball" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <link rel="shortcut icon" href="favicon.ico" type="image/x-icon" />
    <link rel="stylesheet" type="text/css" href="css/jquery-ui.min.css" />
<!--
    <link rel="stylesheet" type="text/css" href="css/jquery-mobile.min.css" />
-->
    <link rel="stylesheet" type="text/css" href="css/jquery.timeentry.css" />
    <link rel="stylesheet" type="text/css" href="css/bootstrap.min.css" />
    <link rel="stylesheet" type="text/css" href="css/bcc.base.css" />
  </head>
  <body>
    <a class="sr-only sr-only-focusable" href="#begin-content">Skip navigation</a>
    <nav class="navbar navbar-fixed-top navbar-inverse">
      <div class="container-fluid">
        <!-- Brand and toggle get grouped for better mobile display -->
        <div class="navbar-header"><form action="#">
          <button type="button" class="navbar-toggle collapsed" data-toggle="collapse"
                  data-target="#navbar-menu-collapse" aria-expanded="false">
            <span class="sr-only">Toggle navigation</span>
            <span class="icon-bar"></span>
            <span class="icon-bar"></span>
            <span class="icon-bar"></span>
          </button>
          <img alt="Bliss Carpet Cleaning logo" class="logo" src="img/orange-cleaner.png" />
          <h1 class="navbar-brand">Bliss Carpet Cleaning</h1>
          <a class="navbar-contact" href="tel:2817097708">(281) 709-7708</a>
        </form></div>
        <!-- Collect the nav links, forms, and other content for toggling -->
        <div class="collapse navbar-collapse" id="navbar-menu-collapse">
          <ul class="nav navbar-nav navbar-right">
            <li <?= $tryPost ? '' : 'class="active"' ?>>
              <a href="#content-home" data-target="#content-home" data-toggle="tab">Home</a>
            </li>
            <li><a href="#content-specials" data-target="#content-specials" data-toggle="tab">Specials</a></li>
            <li <?= $tryPost && !$doPost ? 'class="active"' : '' ?>>
              <a href="#content-contact" data-target="#content-contact" data-toggle="tab">Get a Quote</a>
            </li>
          </ul>
        </div><!-- END .navbar-collapse -->
      </div><!-- END .container-fluid -->
    </nav>
    <main id="begin-content" tabindex="-1">
      <div class="container tab-content">
        <div class="container-fluid tab-pane <?= $tryPost ? '' : 'active' ?>" id="content-home">
          <div class="row">
            <div class="col-sm-12 col-md-12 col-lg-12 col-xl-12">
              <div class="well">
                <div class="hero-unit">
                  <div id="hero__carousel" class="carousel slide" data-ride="carousel">
                    <ol class="carousel-indicators">
                      <li data-target="#hero__carousel" data-slide-to="0" class="active"></li>
                      <li data-target="#hero__carousel" data-slide-to="1"></li>
                      <li data-target="#hero__carousel" data-slide-to="2"></li>
                    </ol>
                    <div class="carousel-inner">
                      <div class="carousel-item item active">
                        <img src="img/van-mounted.png" alt="Family owned and operated since 2007" />
                        <h1>Family owned and operated</h1>
                        <h2>Serving the Houston Area since 2007</h2>
                      </div> <!-- END .carousel-item .item :nth-child(1) -->
                      <div class="carousel-item item">
                        <img src="img/stairs-before-after-01.png" alt="Before and after stairway carpet cleaning" />
                        <h2 class="carousel-item__before">Before</h2>
                        <h2 class="carousel-item__after">After</h2>
                      </div> <!-- END .carousel-item .item :nth-child(2) -->
                      <div class="carousel-item item">
                        <img src="img/carpet-before-after-01.png" alt="Before and after carpet cleaning" />
                        <h2 class="carousel-item__before">Before</h2>
                        <h2 class="carousel-item__after">After</h2>
                      </div> <!-- END .carousel-item .item :nth-child(3) -->
                    </div> <!-- END .carousel-inner -->
                    <a class="left carousel-control" href="#hero__carousel" data-slide="prev">
                      <span class="icon-prev" aria-hidden="true"></span>
                      <span class="sr-only">Previous</span>
                    </a>
                    <a class="right carousel-control" href="#hero__carousel" data-slide="next">
                      <span class="icon-next" aria-hidden="true"></span>
                      <span class="sr-only">Next</span>
                    </a>
                  </div> <!-- END .carousel .slide #hero__carousel -->
                </div> <!-- END .hero-unit -->
              </div> <!-- END .well -->
            </div> <!-- END .col-sm-12 .col-md-12 .col-lg-12 .col-xl-12 -->
          </div> <!-- END .row -->
          <div class="row">
            <div class="col-sm-12 col-md-6 col-lg-6 col-xl-12">
              <div class="well">
                <h2>Our Services</h2>
                <p>Serving the Houston area since 2007, we offer a variety of cleaning services.</p>
                <ul>
                  <li>Carpet cleaning</li>
                  <li>Upholstery cleaning</li>
                  <li>Tile and grout cleaning</li>
                  <li>Wood floor cleaning</li>
                  <li>Carpet repair</li>
                  <li>Air duct cleaning</li>
                </ul>
              </div> <!-- END .well -->
            </div> <!-- END .col-sm-12 .col-md-6 .col-lg-6 .col-xl-12 -->
            <div class="col-sm-12 col-md-6 col-lg-6 col-xl-12">
              <div class="well">
                <h2>Quality Cleaning</h2>
                <p>We specialize in cleanliness, freshness, and customer service.</p>
                <h3>Hygienic</h3>
                <p>
                  Our truck-mounted carpet cleaning units kill germs with water heated to 230&deg;F. Thorough cleaning
                  also reduces the amount of allergens in the air, whether in your carpet or your air ducts.
                </p>
                <h3>Protective</h3>
                <p>We offer Scotch Guard treatment to lengthen the life of your carpets and keep them clean.</p>
                <h3>Environmentally conscious</h3>
                <p>Our cleaning products are bio-degradable.</p>
                <h3>Thorough</h3>
                <p>We pre-treat heavy traffic areas and rinse cleaned carpets with water.</p>
              </div> <!-- END .well -->
            </div> <!-- END .col-sm-12 .col-md-6 .col-lg-6 .col-xl-12 -->
          </div> <!-- END .row -->
        </div> <!-- END #content-home -->
        <div class="container-fluid tab-pane" id="content-specials">
          <div class="row">
            <div class="col-xs-12 col-sm-12 col-md-6 col-lg-6">
              <div class="well">
                <h2>5 Rooms Special &ndash; $99</h2>
                <h3>2 Free Hallways (Max)</h3>
                <p>Includes detergent, odor killer, and deodorizers.</p>
              </div> <!-- END .well -->
            </div> <!-- END .col-xs-12 .col-sm-12 .col-md-6 .col-lg-6 -->
            <div class="col-xs-12 col-sm-12 col-md-6 col-lg-6">
              <div class="well">
                <h2>3 Rooms Special &ndash; $75</h2>
                <h3>1 Free Hallway (Max)</h3>
                <p>Includes detergent, odor killer, and deodorizers.</p>
              </div> <!-- END .well -->
            </div> <!-- END .col-xs-12 .col-sm-12 .col-md-6 .col-lg-6 -->
          </div> <!-- END .row -->
          <div class="row">
            <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
              <div class="well">
                <h2>Standard Carpet Cleaning Rates</h2>
                <h3>$30 per room</h3>
                <p>Minimum of two rooms per visit. Includes detergent, odor killer, and deodorizers.</p>
                <h3>Pre-treatment</h3>
                <p>
                  Heavy traffic stains? Red stains? Gum residue? Rust stains? Pet stains? We use a unique combination of
                  products to break up these and other stains in your carpet before pulling them out with our cleaning
                  equipment. Pre-treatment starts at $15; this normally handles spot-cleaning of two rooms. Additional
                  charges may be incurred for more rooms and large or hard-to-lift stains.
                </p>
                <h3>Scotch Guard</h3>
                <p>Now that it's clean, protect your carpet or upholstery from future stains.</p>
              </div> <!-- END .well -->
            </div> <!-- END .col-xs-12 .col-sm-12 .col-md-12 .col-lg-12 -->
          </div> <!-- END .row -->
          <div class="row">
            <div class="col-xs-12 col-sm-12 col-md-6 col-lg-6">
              <div class="well">
                <h2>Sofa and Chair &ndash; $115</h2>
                <p>
                  For $115, we'll clean your sofa and chair. Ask us about applying Scotch Guard to keep the stains out.
                </p>
              </div> <!-- END .well -->
            </div> <!-- END .col-xs-12 .col-sm-12 .col-md-6 .col-lg-6 -->
            <div class="col-xs-12 col-sm-12 col-md-6 col-lg-6">
              <div class="well">
                <h2>VIP Customers</h2>
                <p>Once we come and clean your carpets, make sure to ask about becoming a VIP Customer!</p>
              </div> <!-- END .well -->
            </div> <!-- END .col-xs-12 .col-sm-12 .col-md-6 .col-lg-6 -->
          </div> <!-- END .row -->
          <div class="row">
            <div class="col-xs-12 col-sm-12 col-md-6 col-lg-6">
              <div class="well">
                <h2>Wood Floors</h2>
                <h3>79&cent; / sq. ft.</h3>
                <p>We'll restore the beauty of your hardwood floors for only $0.79 per square foot.</p>
              </div> <!-- END .well -->
            </div> <!-- END .col-xs-12 .col-sm-12 .col-md-6 .col-lg-6 -->
            <div class="col-xs-12 col-sm-12 col-md-6 col-lg-6">
              <div class="well">
                <h2>Tile &amp; Grout</h2>
                <h3>79&cent; / sq. ft.</h3>
                <p>Save your hips and your knees. We'll clean your tile floors for $0.79 per square foot.</p>
              </div> <!-- END .well -->
            </div> <!-- END .col-xs-12 .col-sm-12 .col-md-6 .col-lg-6 -->
          </div> <!-- END .row -->
        </div> <!-- END #content-specials -->
        <div class="container-fluid tab-pane <?= $tryPost && !$doPost ? 'active' : '' ?>" id="content-contact">
          <div class="row">
            <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
              <h2>Get a Quote</h2>
              <?php
                /* Do we have any error messages to display? */
                if (count($errorMessages) > 0) {
                  /* Yes, we do. Let's start with the wrapper... */
                  ?>
                    <div class="alert alert-danger"><ul>
                  <?php
                  /* And loop through the messages. */
                  foreach($errorMessages as $fieldName => $errorMessage) {
                    /* The JavaScript in bcc.base.js will handle the click event on these <li> elements. */
                    ?>
                      <li for="<?= $fieldName ?>">
                        <span class="glyphicon glyphicon-remove"></span>
                        <?= htmlspecialchars($errorMessage) ?>
                      </li>
                    <?php
                  }
                  /* We're done displaying error messages. Close the wrapper. */
                  ?>
                    </ul></div>
                  <?php
                }
              ?>
              <form action="#" method="post">
                <div class="form-text">
                  Asterisks (<span class="glyphicon glyphicon-asterisk"></span>) indicate required fields.
                </div>
                <fieldset class="form-group">
                  <legend>How should we contact you?</legend>
                  <div class="well">
                    <?php $isInvalid = isset($errorMessages["customer_name"]); ?>
                    <div class="form-group <?= $isInvalid ? 'has-error has-feedback' : '' ?>">
                      <label class="control-label" for="customer_name">
                        <span class="glyphicon glyphicon-asterisk"></span>
                        Name
                      </label>
                      <input type="text" class="form-control" id="customer_name" maxlength="1024"
                             name="customer_name"
                             <?php
                              if (isset($_POST["customer_name"]))
                                echo "value=\"" . htmlspecialchars($_POST["customer_name"]) . "\"";
                             ?> />
                      <?php
                      /* Did the customer_name field fail validation? */
                      if ($isInvalid && ($errorMessages["customer_name"] != "")) {
                        /* Yes, the customer_name field failed validation. Display its error message. */
                        ?>
                          <div class="form-control-feedback">
                            <span class="glyphicon glyphicon-remove"></span>
                            <?= htmlspecialchars($errorMessages["customer_name"]) ?>
                          </div>
                        <?php
                      }
                      ?>
                    </div> <!-- END .form-group -->
                    <?php $isInvalid = isset($errorMessages["customer_email"]); ?>
                    <div class="form-group <?= $isInvalid ? 'has-error has-feedback' : '' ?>">
                      <label class="control-label" for="customer_email">Email</label>
                      <input type="email" class="form-control" id="customer_email" maxlength="255"
                             name="customer_email"
                             <?php
                              if (isset($_POST["customer_email"]))
                                echo "value=\"" . htmlspecialchars($_POST["customer_email"]) . "\"";
                             ?> />
                      <?php
                      /* Did the customer_email field fail validation? */
                      if ($isInvalid && ($errorMessages["customer_email"] != "")) {
                        /* Yes, the customer_email field failed validation. Display its error message. */
                        ?>
                          <div class="form-control-feedback">
                            <span class="glyphicon glyphicon-remove"></span>
                            <?= htmlspecialchars($errorMessages["customer_email"]) ?>
                          </div>
                        <?php
                      }
                      ?>
                    </div> <!-- END .form-group -->
                    <?php $isInvalid = isset($errorMessages["customer_phone"]); ?>
                    <div class="form-group <?= $isInvalid ? 'has-error has-feedback' : '' ?>">
                      <label class="control-label" for="customer_phone">Phone</label>
                      <input type="tel" class="form-control" id="customer_phone" maxlength="50" name="customer_phone"
                             <?php
                              if (isset($_POST["customer_phone"]))
                                echo "value=\"" . htmlspecialchars($_POST["customer_phone"]) . "\"";
                             ?> />
                      <?php
                      /* Did the customer_phone field fail validation? */
                      if ($isInvalid && ($errorMessages["customer_phone"] != "")) {
                        /* Yes, the customer_phone field failed validation. Display its error message. */
                        ?>
                          <div class="form-control-feedback">
                            <span class="glyphicon glyphicon-remove"></span>
                            <?= htmlspecialchars($errorMessages["customer_phone"]) ?>
                          </div>
                        <?php
                      }
                      ?>
                    </div> <!-- END .form-group -->
                    <?php $isInvalid = isset($errorMessages["customer_prefer"]); ?>
                    <div class="form-group <?= $isInvalid ? 'has-error has-feedback' : '' ?>">
                      <div class="form-text">
                        <span class="glyphicon glyphicon-asterisk"></span>
                        Preferred method of contact
                      </div>
                      <div class="form-check <?= $isInvalid ? 'has-error' : '' ?>">
                        <input type="radio" id="customer_prefer_email" name="customer_prefer" value="email"
                               <?php 
                                if (isset($_POST["customer_prefer"]) && ($_POST["customer_prefer"] == "email"))
                                  echo 'checked="checked"';
                               ?> />
                        <label class="control-label form-check-label" for="customer_prefer_email">Email</label>
                      </div> <!-- END .form-check -->
                      <div class="form-check <?= $isInvalid ? 'has-error' : '' ?>">
                        <input type="radio" id="customer_prefer_phone" name="customer_prefer" value="phone"
                               <?php
                                if (isset($_POST["customer_prefer"]) && ($_POST["customer_prefer"] == "phone"))
                                  echo 'checked="checked"';
                               ?> />
                        <label class="control-label form-check-label" for="customer_prefer_phone">Phone</label>
                      </div> <!-- END .form-check -->
                      <?php
                      /* Did the customer_prefer field fail validation? */
                      if ($isInvalid && ($errorMessages["customer_prefer"] != "")) {
                        /* Yes, the customer_prefer field failed validation. Display its error message. */
                        ?>
                          <div class="form-control-feedback">
                            <span class="glyphicon glyphicon-remove"></span>
                            <?= htmlspecialchars($errorMessages["customer_prefer"]) ?>
                          </div>
                        <?php
                      }
                      ?>
                    </div> <!-- END .form-group -->
                  </div> <!-- END .well -->
                </fieldset>
                <fieldset class="form-group">
                  <legend>When and where does the work need to be done?</legend>
                  <div class="well">
                    <?php $isInvalid = isset($errorMessages["address_street1"]); ?>
                    <div class="form-group <?= $isInvalid ? 'has-error has-feedback' : '' ?>">
                      <label class="control-label" for="address_street1">
                        <span class="glyphicon glyphicon-asterisk"></span>
                        Street (line 1)
                      </label>
                      <input type="text" class="form-control" id="address_street1" maxlength="255"
                             name="address_street1"
                             <?php
                              if (isset($_POST["address_street1"]))
                                echo 'value="' . htmlspecialchars($_POST['address_street1']) . '"';
                             ?> />
                      <?php
                      /* Did the address_street1 field fail validation? */
                      if ($isInvalid && ($errorMessages["address_street1"] != "")) {
                        /* Yes, the address_street1 field failed validation. Display its error message. */
                        ?>
                          <div class="form-control-feedback">
                            <span class="glyphicon glyphicon-remove"></span>
                            <?= htmlspecialchars($errorMessages["address_street1"]) ?>
                          </div>
                        <?php
                      }
                      ?>
                    </div> <!-- END .form-group -->
                    <?php $isInvalid = isset($errorMessages["address_street2"]); ?>
                    <div class="form-group <?= $isInvalid ? 'has-error has-feedback' : '' ?>">
                      <label class="control-label" for="address_street2">Street (line 2)</label>
                      <input type="text" class="form-control" id="address_street2" maxlength="255"
                             name="address_street2"
                             <?php
                              if (isset($_POST["address_street2"]))
                                echo 'value="' . htmlspecialchars($_POST['address_street2']) . '"';
                             ?>
                             />
                      <?php
                      /* Did the address_street2 field fail validation? */
                      if ($isInvalid && ($errorMessages["address_street2"] != "")) {
                        /* Yes, the address_street2 field failed validation. Display its error message. */
                        ?>
                          <div class="form-control-feedback">
                            <span class="glyphicon glyphicon-remove"></span>
                            <?= htmlspecialchars($errorMessages["address_street2"]) ?>
                          </div>
                        <?php
                      }
                      ?>
                    </div> <!-- END .form-group -->
                    <?php $isInvalid = isset($errorMessages["address_city"]); ?>
                    <div class="form-group <?= $isInvalid ? 'has-error has-feedback' : '' ?>">
                      <div class="input-group">
                        <label class="control-label" for="address_city">
                          <span class="glyphicon glyphicon-asterisk"></span>
                          City
                        </label>
                        <input type="text" class="form-control" id="address_city" maxlength="255" name="address_city"
                               <?php
                                if (isset($_POST["address_city"]))
                                  echo 'value="' . htmlspecialchars($_POST["address_city"]) . '"';
                               ?>
                               />
                        <div class="input-group-addon">, Texas</div>
                      </div>
                      <?php
                      /* Did the address_city field fail validation? */
                      if ($isInvalid && ($errorMessages["address_city"] != "")) {
                        /* Yes, the address_city field failed validation. Display its error message. */
                        ?>
                          <div class="form-control-feedback">
                            <span class="glyphicon glyphicon-remove"></span>
                            <?= htmlspecialchars($errorMessages["address_city"]) ?>
                          </div>
                        <?php
                      }
                      ?>
                    </div> <!-- END .form-group -->
                    <?php $isInvalid = isset($errorMessages["address_zip"]); ?>
                    <div class="form-group <?= $isInvalid ? 'has-error has-feedback' : '' ?>">
                      <label class="control-label" for="address_zip">
                        <span class="glyphicon glyphicon-asterisk"></span>
                        ZIP Code
                      </label>
                      <input type="text" class="form-control" id="address_zip" maxlength="10" name="address_zip"
                             <?php
                              if (isset($_POST["address_zip"]))
                                echo 'value="' . htmlspecialchars($_POST["address_zip"]) . '"';
                             ?>
                             />
                      <?php
                      /* Did the address_zip field fail validation? */
                      if ($isInvalid && ($errorMessages["address_zip"] != "")) {
                        /* Yes, the address_zip field failed validation. Display its error message. */
                        ?>
                          <div class="form-control-feedback">
                            <span class="glyphicon glyphicon-remove"></span>
                            <?= htmlspecialchars($errorMessages["address_zip"]) ?>
                          </div>
                        <?php
                      }
                      ?>
                    </div> <!-- END .form-group -->
                    <?php $isInvalid = isset($errorMessages["appt_date"]); ?>
                    <div class="form-group <?= $isInvalid ? 'has-error has-feedback' : '' ?>">
                      <label class="control-label" for="appt_date">
                        Suggested Appointment Date
                      </label>
                      <input type="text" class="form-control date-control" id="appt_date" maxlength="10"
                             name="appt_date" style="z-index: 3"
                             <?php
                              if (isset($_POST["appt_date"]))
                                echo 'value="' . htmlspecialchars($_POST['appt_date']) . '"';
                             ?> />
                      <?php
                      /* Did the appt_date field fail validation? */
                      if ($isInvalid && ($errorMessages["appt_date"] != "")) {
                        /* Yes, the appt_date field failed validation. Display its error message. */
                        ?>
                          <div class="form-control-feedback">
                            <?= htmlspecialchars($errorMessages["appt_date"]) ?><br />
                          </div>
                        <?php
                      }
                      ?>
                    </div> <!-- END .form-group -->
                    <?php $isInvalid = isset($errorMessages["appt_time"]); ?>
                    <div class="form-group <?= $isInvalid ? 'has-error has-feedback' : '' ?>">
                      <label class="control-label" for="appt_time">
                        Suggested Appointment Time
                      </label>
                      <input type="text" class="form-control time-control" id="appt_time" maxlength="10"
                             name="appt_time" style="z-index: 3"
                             <?php
                              if (isset($_POST["appt_date"]))
                                echo 'value="' . htmlspecialchars($_POST['appt_time']) . '"';
                             ?> />
                      <?php
                      /* Did the appt_date field fail validation? */
                      if ($isInvalid && ($errorMessages["appt_time"] != "")) {
                        /* Yes, the appt_time field failed validation. Display its error message. */
                        ?>
                          <div class="form-control-feedback">
                            <span class="glyphicon glyphicon-remove"></span>
                            <?= htmlspecialchars($errorMessages["appt_time"]) ?>
                          </div>
                        <?php
                      }
                      ?>
                    </div> <!-- END .form-group -->
                  </div> <!-- END .well -->
                </fieldset>
                <fieldset class="form-group">
                  <legend>What work would you like quoted?</legend>
                  <div class="well">
                    <div class="panel-group" aria-multiselectable="true">
                      <?php
                      /* Loop through the groups in $fieldsServices. */
                      foreach ($fieldsServices as $fieldGroup => $infoGroup) {
                        /* Start by displaying the header. Follow that by opening the panel-body <div>. */
                        ?>
                          <div class="panel">
                            <div class="panel-heading" id="<?= $fieldGroup . '_hdr' ?>">
                              <a href="#<?= $fieldGroup ?>" data-toggle="collapse"
                                 aria-expanded="<?= isFieldsetEmpty($fieldGroup) ? 'false' : 'true' ?>"
                                 aria-controls="<?= $fieldGroup ?>">
                                <h3><?= htmlspecialchars($infoGroup["header"]) ?></h3>
                              </a>
                            </div> <!-- END #<?= $fieldGroup . '_hdr' ?> -->
                            <div class="panel-collapse collapse <?= isFieldsetEmpty($fieldGroup) ? '' : 'in' ?>"
                                 id="<?= $fieldGroup ?>" aria-labelledby="<?= $fieldGroup . '_hdr' ?>">
                              <div class="panel-body">
                                <?php
                                /* Loop through the fields within this group. */
                                foreach($infoGroup["fields"] as $nameField => $infoField) {
                                  /* Did this field fail validation? */
                                  $isInvalid = isset($errorMessages[$nameField]);
                                  ?>
                                  <div class="form-group <?= $isInvalid ? 'has-error has-feedback' : '' ?>">
                                    <?php
                                    /* Different types of fields get displayed different ways. Which type is this? */
                                    switch ($infoField["type"]) {
                                      case "checkbox":
                                        /* If a checkbox, display the input, then the label. */
                                        ?>
                                        <div class="form-check <?= $isInvalid ? 'has-error' : '' ?>">
                                          <input type="<?= $infoField["type"] ?>" id="<?= $nameField ?>"
                                                 name="<?= $nameField ?>" value="1"
                                                 <?php
                                                  if (isset($_POST[$nameField]) && ($_POST[$nameField] == 1))
                                                    echo 'checked="checked"'
                                                 ?> />
                                          <label class="control-label" for="<?= $nameField ?>">
                                            <?= htmlspecialchars($infoField["label"]) ?>
                                          </label>
                                        </div> <!-- END .form-check -->
                                        <?php
                                        break;
                                      case "number":
                                        /* If a number, display the label, then the input. */
                                        ?>
                                          <label class="control-label" for="<?= $nameField ?>">
                                            <?= htmlspecialchars($infoField["label"]) ?>
                                          </label>
                                          <input class="form-control" type="<?= $infoField["type"] ?>"
                                                 id="<?= $nameField ?>" name="<?= $nameField ?>"
                                                 <?php
                                                  if (isset($_POST[$nameField]) && ($_POST[$nameField] != ""))
                                                    echo 'value="' . htmlspecialchars($_POST[$nameField]) . '" ';
                                                  if (isset($infoField["hint"]) && ($infoField["hint"] != "")) {
                                                    echo "aria-describedby=\"{$nameField}_desc\" ";
                                                  }
                                                 ?>
                                                 />
                                        <?php
                                        break;
                                    }
                                    /* Is a hint defined for this field? */
                                    if (isset($infoField["hint"]) && ($infoField["hint"] != "")) {
                                      /* Yes, a hint is defined for this field. Display it. */
                                      ?>
                                      <div class="form-text text-muted" id="<?= $nameField ?>_desc">
                                        <?= htmlspecialchars($infoField["hint"]) ?>
                                      </div>
                                      <?php
                                    }
                                    /* Did this field fail validation? */
                                    if ($isInvalid && ($errorMessages[$nameField] != "")) {
                                      /* Yes, this field failed validation. Display its error message. */
                                      ?>
                                        <div class="form-control-feedback">
                                          <span class="glyphicon glyphicon-remove"></span>
                                          <?= htmlspecialchars($errorMessages[$nameField]) ?>
                                        </div> <!-- END .form-control-feedback -->
                                      <?php
                                    }
                                    ?>
                                  </div> <!-- END .form-group -->
                                  <?php
                                }
                                ?>
                              </div> <!-- END .panel-body -->
                            </div> <!-- END #<?= $fieldGroup ?> -->
                          </div> <!-- END .panel -->
                        <?php
                      }
                      ?>
                    </div> <!-- END .panel-group -->
                  </div> <!-- END .well -->
                </fieldset>
                <fieldset class="form-group">
                  <legend>Anything else you think would be helpful?</legend>
                  <div class="well">
                    <div class="form-group">
                      <label class="control-label" for="comment_text">Additional comments</label>
                      <textarea class="form-control" id="comment_text" name="comment_text" maxlength="16000"
                      ><?php
                        if (isset($_POST["comment_text"]))
                          echo htmlspecialchars($_POST["comment_text"]);
                      ?></textarea>
                    </div>
                  </div> <!-- END .well -->
                  <button type="submit" class="btn btn-primary" name="bcc_quote_submit" value="1">
                    Get a Quote
                  </button>
                </fieldset>
              </form>
            </div> <!-- END .col-xs-12 .col-sm-12 .col-md-12 .col-lg-12 -->
          </div> <!-- END .row -->
        </div> <!-- END #content-contact -->
        <?php
        if ($doPost === true) {
          ?>
          <div class="container-fluid tab-pane <?= $doPost ? 'active' : '' ?>" id="content-thanks">
            <div class="row">
              <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
                <div class="well">
                  <h2>Thank you!</h2>
                  <?php
                  /* Start caching the output so we can store it in a variable. */
                  ob_start()
                  ?>
                  <fieldset class="form-group">
                    <h3>Contact Info and Location</h3>
                    <div class="row">
                      <div class="col-xs-4 col-sm-3 col-md-3 col-lg-3">Name</div>
                      <div class="col-xs-8 col-sm-9 col-md-9 col-lg-9">
                        <?= htmlspecialchars($_POST["customer_name"]) ?>
                      </div>
                    </div> <!-- END .row -->
                    <div class="row">
                      <div class="col-xs-4 col-sm-3 col-md-3 col-lg-3">Email</div>
                      <div class="col-xs-8 col-sm-9 col-md-9 col-lg-9">
                        <?= htmlspecialchars($_POST["customer_email"]) ?>
                      </div>
                    </div> <!-- END .row -->
                    <div class="row">
                      <div class="col-xs-4 col-sm-3 col-md-3 col-lg-3">Phone</div>
                      <div class="col-xs-8 col-sm-9 col-md-9 col-lg-9">
                        <?= htmlspecialchars($_POST["customer_phone"]) ?>
                      </div>
                    </div> <!-- END .row -->
                    <div class="row">
                      <div class="col-xs-4 col-sm-3 col-md-3 col-lg-3">Preference</div>
                      <div class="col-xs-8 col-sm-9 col-md-9 col-lg-9">
                        <?=
                          $_POST["customer_prefer"] == "email"
                          ? 'Email'
                          : ($_POST["customer_prefer"] == "phone" ? 'Phone' : 'N / A')
                        ?>
                      </div> <!-- END .col-xs-6 .col-sm-9 .col-md-9 .col-lg-9 -->
                    </div> <!-- END .row -->
                    <div class="row">
                      <div class="col-xs-4 col-sm-3 col-md-3 col-lg-3">Service Location</div>
                      <div class="col-xs-8 col-sm-9 col-md-9 col-lg-9">
                        <?= htmlspecialchars($_POST["address_street1"]) ?><br />
                        <?=
                          $_POST["address_street2"] != ''
                          ? htmlspecialchars($_POST["address_street2"]) . '<br />'
                          : ''
                        ?>
                        <?= htmlspecialchars($_POST["address_city"]) ?>, TX
                        <?= htmlspecialchars($_POST["address_zip"]) ?>
                      </div>
                    </div> <!-- END .row -->
                    <?php
                    if (isset($_POST["appt_date_time"]) && ($_POST["appt_date_time"] != "")) {
                      ?>
                        <div class="row">
                          <div class="col-xs-4 col-sm-3 col-md-3 col-lg-3">Appointment Time</div>
                          <div class="col-xs-8 col-sm-9 col-md-9 col-lg-9">
                            <?= (new DateTime($_POST["appt_date_time"]))->format("m/d/Y h:i A") ?>
                          </div>
                        </div>
                      <?php
                    }
                    ?>
                  </fieldset>
                  <?php
                  /* Only show each of the following sections if form input was provided for them. */
                  if (!isFieldsetEmpty("carpet_cleaning")) { ?>
                    <h3>Carpet Cleaning</h3>
                    <div class="row">
                      <div class="col-xs-4 col-sm-3 col-md-3 col-lg-3">Rooms</div>
                      <div class="col-xs-8 col-sm-9 col-md-9 col-lg-9">
                        <?= htmlspecialchars($_POST["carpet_cleaning_count_rooms"]) ?>
                      </div> <!-- END .col-xs-8 .col-sm-9 .col-md-9 .col-lg-9 -->
                    </div> <!-- END .row -->
                    <div class="row">
                      <div class="col-xs-4 col-sm-3 col-md-3 col-lg-3">Hallways</div>
                      <div class="col-xs-8 col-sm-9 col-md-9 col-lg-9">
                        <?= htmlspecialchars($_POST["carpet_cleaning_count_halls"]) ?>
                      </div> <!-- END .col-xs-8 .col-sm-9 .col-md-9 .col-lg-9 -->
                    </div> <!-- END .row -->
                    <div class="row">
                      <div class="col-xs-4 col-sm-3 col-md-3 col-lg-3">Stairways</div>
                      <div class="col-xs-8 col-sm-9 col-md-9 col-lg-9">
                        <?= htmlspecialchars($_POST["carpet_cleaning_count_stairs"]) ?>
                      </div> <!-- END .col-xs-8 .col-sm-9 .col-md-9 .col-lg-9 -->
                    </div> <!-- END .row -->
                    <div class="row">
                      <div class="col-xs-4 col-sm-3 col-md-3 col-lg-3">Scotch Guard</div>
                      <div class="col-xs-8 col-sm-9 col-md-9 col-lg-9">
                        <?= htmlspecialchars($_POST["carpet_cleaning_count_protect"]) ?>
                      </div> <!-- END .col-xs-8 .col-sm-9 .col-md-9 .col-lg-9 -->
                    </div> <!-- END .row -->
                  <?php }
                  if (!isFieldsetEmpty("wood_cleaning")) { ?>
                    <h3>Wood Floor Cleaning</h3>
                    <div class="row">
                      <div class="col-xs-4 col-sm-3 col-md-3 col-lg-3">Estimated area</div>
                      <div class="col-xs-8 col-sm-9 col-md-9 col-lg-9">
                        <?= htmlspecialchars($_POST["wood_cleaning_area"]) ?> sq. ft.
                      </div> <!-- END .col-xs-8 .col-sm-9 .col-md-9 .col-lg-9 -->
                    </div> <!-- END .row -->
                  <?php }
                  if (!isFieldsetEmpty("tile_cleaning")) { ?>
                    <h3>Tile &amp; Grout Cleaning</h3>
                    <div class="row">
                      <div class="col-xs-4 col-sm-3 col-md-3 col-lg-3">Estimated area</div>
                      <div class="col-xs-8 col-sm-9 col-md-9 col-lg-9">
                        <?= htmlspecialchars($_POST["tile_cleaning_area"]) ?> sq. ft.
                      </div> <!-- END .col-xs-8 .col-sm-9 .col-md-9 .col-lg-9 -->
                    </div> <!-- END .row -->
                  <?php }
                  if (!isFieldsetEmpty("upholstery")) { ?>
                    <h3>Upholstery Cleaning</h3>
                    <div class="row">
                      <div class="col-xs-4 col-sm-3 col-md-3 col-lg-3">Large items</div>
                      <div class="col-xs-8 col-sm-9 col-md-9 col-lg-9">
                        <?= htmlspecialchars($_POST["upholstery_count_lg"]) ?>
                      </div> <!-- END .col-xs-8 .col-sm-9 .col-md-9 .col-lg-9 -->
                    </div> <!-- END .row -->
                    <div class="row">
                      <div class="col-xs-4 col-sm-3 col-md-3 col-lg-3">Small items</div>
                      <div class="col-xs-8 col-sm-9 col-md-9 col-lg-9">
                        <?= htmlspecialchars($_POST["upholstery_count_sm"]) ?>
                      </div> <!-- END .col-xs-8 .col-sm-9 .col-md-9 .col-lg-9 -->
                    </div> <!-- END .row -->
                  <?php }
                  if (!isFieldsetEmpty("carpet_repair")) { ?>
                    <h3>Carpet Repair &amp; Dying</h3>
                    <div class="row">
                      <div class="col-xs-4 col-sm-3 col-md-3 col-lg-3">Carpet repair</div>
                      <div class="col-xs-8 col-sm-9 col-md-9 col-lg-9">
                        <?= htmlspecialchars($_POST["carpet_repair_count_patches"]) ?> patches
                      </div> <!-- END .col-xs-8 .col-sm-9 .col-md-9 .col-lg-9 -->
                    </div> <!-- END .row -->
                    <div class="row">
                      <?php
                        $haveExtra = isset($_POST["carpet_repair_have_extra"])
                          && ($_POST["carpet_repair_have_extra"] == 1);
                      ?>
                      <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
                        I <?= $haveExtra ? '' : 'do not' ?> have extra carpet to use for patches.
                      </div> <!-- END .col-xs-12 .col-sm-12 .col-md-12 .col-lg-12 -->
                    </div> <!-- END .row -->
                    <div class="row">
                      <div class="col-xs-4 col-sm-3 col-md-3 col-lg-3">Carpet dying</div>
                      <div class="col-xs-8 col-sm-9 col-md-9 col-lg-9">
                        <?= htmlspecialchars($_POST["carpet_repair_count_dye"]) ?> rooms
                      </div> <!-- END .col-xs-8 .col-sm-9 .col-md-9 .col-lg-9 -->
                    </div> <!-- END .row -->
                  <?php }
                  if (!isFieldsetEmpty("air_duct")) { ?>
                    <h3>Air Duct Cleaning</h3>
                    <div class="row">
                      <div class="col-xs-4 col-sm-3 col-md-3 col-lg-3">Vents</div>
                      <div class="col-xs-8 col-sm-9 col-md-9 col-lg-9">
                        <?= htmlspecialchars($_POST["air_duct_count_vents"]) ?>
                      </div> <!-- END .col-xs-8 .col-sm-9 .col-md-9 .col-lg-9 -->
                    </div> <!-- END .row -->
                  <?php }
                  if (isset($_POST["comment_text"]) && ($_POST["comment_text"] != "")) { ?>
                    <h3>Additional Comments</h3>
                    <div class="row">
                      <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
                        <p><?= preg_replace("/\\n+/", "</p></p>", htmlspecialchars($_POST["comment_text"])) ?></p>
                      </div>
                    </div>
                  <?php }
                  /*
                   * We've got the content of the quote confirmation cached in the buffer. Let's store it in a
                   * variable for easy inclusion in an email, then send it to the client. We'll send out the email
                   * after everything has been sent to the client.
                   */
                  $emailBody = ob_get_clean();
                  ob_flush();
                  echo $emailBody;
                  ?>
                </div> <!-- END .well -->
              </div> <!-- END .col-xs-12 .col-sm-12 .col-md-12 .col-lg-12 -->
            </div> <!-- END .row -->
          </div> <!-- END #content-thanks -->
        <?php
        }
        ?>
      </div> <!-- END container tab-content -->
    </main>
    <script type="text/javascript" src="js/jquery.min.js"></script>
    <script type="text/javascript" src="js/jquery-ui.min.js"></script>
<!--
    <script type="text/javascript" src="js/jquery-mobile.min.js"></script>
-->
    <script type="text/javascript" src="js/jquery.plugin.min.js"></script>
    <script type="text/javascript" src="js/jquery.timeentry.min.js"></script>
    <script type="text/javascript" src="js/bootstrap.min.js"></script>
    <script type="text/javascript" src="js/bcc.base.js"></script>
<?php
/* Are we supposed to send out an email? */
if ($doPost) {
  /* The first thing we'll do is tweak the HTML output so it will render fairly well in an email client. */
  $emailBody = str_replace(
    "<div class=\"col-xs-4 col-sm-3 col-md-3 col-lg-3\">",
    "<div style=\"float: left; width: 25%\">",
    $emailBody
  );
  $emailBody = str_replace(
    "<div class=\"col-xs-8 col-sm-9 col-md-9 col-lg-9\">",
    "<div style=\"float: left; width: 70%\">",
    $emailBody
  );
  $emailBody = str_replace(
    "<!-- END .row -->",
    "<div style=\"clear: both; height: 1px; overflow: hidden\">&nbsp;</div><!-- END .row -->",
    $emailBody
  );
  /* With that done, now we'll tweak the HTML output into something that renders well as plain text. */
  $emailBodyPlainText = preg_replace("/\s+</", "<", $emailBody);
  $emailBodyPlainText = preg_replace("/>\s+/", ">", $emailBodyPlainText);
  $emailBodyPlainText = preg_replace("/\s+/", " ", $emailBodyPlainText);
  $emailBodyPlainText = preg_replace("/<h3>(.+?)<\/h3>/", "\n$1\n=========================\n", $emailBodyPlainText);
  $emailBodyPlainText = str_replace("</fieldset>", "\n", $emailBodyPlainText);
  $emailBodyPlainText = str_replace(
    "<div style=\"clear: both; height: 1px; overflow: hidden\">&nbsp;</div><!-- END .row -->",
    "\n",
    $emailBodyPlainText
  );
  $emailBodyPlainText = str_replace("<div style=\"float: left; width: 70%\">", "\n\t", $emailBodyPlainText);
  $emailBodyPlainText = str_replace("<br />", "\n\t", $emailBodyPlainText);
  $emailBodyPlainText = preg_replace("/<[^>]+?>/", "", $emailBodyPlainText);
  include "swiftmailer/lib/swift_required.php";
  try {
    /* Try creating the Mail object. */
    $smtpTransport = Swift_SmtpTransport::newInstance($emailSettings["host"], $emailSettings["port"])
      ->setUsername($emailSettings["username"])
      ->setPassword($emailSettings["password"]);
    $smtpMailer = Swift_Mailer::newInstance($smtpTransport);
    $message = Swift_Message::newInstance($emailSettings["subject"])
      ->setSubject("{$emailSettings["subject"]} by {$_POST["customer_name"]}")
      ->setFrom($emailSettings["to"])
      ->setReplyTo([ $_POST["customer_email"] => $_POST["customer_name"] ])
      ->setTo($emailSettings["to"])
      ->setBody($emailBodyPlainText)
      ->addPart($emailBody, "text/html");
    /* Try sending the email out. */
    $mail = $smtpMailer->send($message);
  } catch (Exception $e) {
    /* Something failed. Dump the exception for debugging purposes. */
    error_log($e);
  }
}
?>
  </body>
</html>
