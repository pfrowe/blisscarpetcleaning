<!doctype html>
<?php
require_once "Mail.php";
include "settings.php";
$errorMessages = array();
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
        "label" => "Number of stairways",
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
      "patternToTest" => "/^(\([0-9]{3}\) |[0-9]{3}[ .-])[0-9]{3}[ .-][0-9]{4}$/",
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
function isFieldsetEmpty($nameFieldset) {
  $returnValue = true;
  global $fieldsServices, $validation;
  if (array_key_exists($nameFieldset, $fieldsServices)) {
    foreach ($fieldsServices[$nameFieldset]["fields"] as $nameField => $infoField) {
      try {
        $returnValue = $returnValue
          && (!isset($_POST[$nameField]) || ($_POST[$nameField] == $validation[$nameField]["default"]));
      } catch (Exception $e) {
        var_dump($nameField); exit();
      }
    }
  }
  return $returnValue;
}
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
    if (isset($fieldValidation["rangeNumeric"])) {
      $isValid = ($$key == "") || is_numeric($$key);
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
function postValues() {
  global $dbSettings, $validation;
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
      `hasSpareCarpet` = :carpet_repair_have_extra;
__SQL;
  $sqlSelectServiceList = <<<__SQL
    SELECT `keyService`, `nameFormVar`
    FROM `service`;
__SQL;
  $sqlInsertService = <<<__SQL
    INSERT INTO `order_service`
    SET
      `keyOrder` = :keyNewOrder,
      `keyService` = :keyService,
      `countUnits` = :countUnits;
__SQL;
  preg_match_all("/\:[\w_]+/", $sqlInsertOrder, $paramsDefined);
  $valuesParams = array();
  foreach ($paramsDefined[0] as $index => $paramName) {
    $valuesParams[$paramName] = $_POST[preg_replace("/^\:/", "", $paramName)];
  }
  $pdoCnxn = new PDO(
    "mysql:host={$dbSettings['hostname']};dbname={$dbSettings['database']}",
    $dbSettings["username"],
    $dbSettings["password"]
  );
  $stmtInsertOrder = $pdoCnxn->prepare($sqlInsertOrder);
  $stmtInsertOrder->execute($valuesParams);
  $keyNewOrder = $pdoCnxn->lastInsertId();
  $resultsServices = $pdoCnxn->query($sqlSelectServiceList)->fetchAll();
  foreach ($resultsServices as $index => $currentRow) {
    if (isset($_POST[$currentRow["nameFormVar"]]) && ($_POST[$currentRow["nameFormVar"]] > 0)) {
      $stmtInsertService = $pdoCnxn->prepare($sqlInsertService);
      $stmtInsertService->bindParam(":keyNewOrder", $keyNewOrder);
      $stmtInsertService->bindParam(":keyService", $currentRow["keyService"]);
      $stmtInsertService->bindParam(":countUnits", $_POST[$currentRow["nameFormVar"]]);
      $stmtInsertService->execute();
    }
  }
}
$doPost = false;
$tryPost = (isset($_POST["bcc_quote_submit"]) && ($_POST["bcc_quote_submit"] == 1));
if ($tryPost === true) {
  validateInput();
  $doPost = count($errorMessages) === 0;
  if ($doPost) {
    postValues();
  }
}
?>
<html>
  <head>
    <title>Bliss Carpet Cleaning - Serving Houston, TX</title>
    <meta name="description"
          content="Bliss Carpet Cleaning is a family-owned and -operated business serving Houston, TX, and providing services including: carpet cleaning, wood floor cleaning, tile and grout cleaning, upholstery cleaning, carpet repair, carpet dying, and air duct cleaning." />
    <meta name="keywords"
          content="Bliss Carpet Cleaning, Perfection Carpet Cleaning, carpet cleaning, rug cleaning, hardwood floor cleaning, wood floor cleaning, hardwood polishing, tile cleaning, grout cleaning, tile and grout cleaning, upholstery cleaning, couch cleaning, sofa cleaning, armchair cleaning, carpet repair, carpet dye, carpet dying, air duct cleaning, air vent cleaning, vehicle cleaning, Scotch Guard, pre-treatment, water rinse, truck-mounted, anti-microbial, hypoallergenic" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <link rel="shortcut icon" href="favicon.ico" type="image/x-icon" />
    <link rel="stylesheet" type="text/css" href="css/bootstrap.min.css" />
    <link rel="stylesheet" type="text/css" href="css/bcc.base.css" />
  </head>
  <body>
    <a class="sr-only sr-only-focusable" href="#begin-content">Skip navigation</a>
    <nav class="navbar navbar-fixed-top navbar-inverse">
      <div class="container-fluid">
        <!-- Brand and toggle get grouped for better mobile display -->
        <div class="navbar-header">
          <button type="button" class="navbar-toggle collapsed" data-toggle="collapse"
                  data-target="#navbar-menu-collapse" aria-expanded="false">
            <span class="sr-only">Toggle navigation</span>
            <span class="icon-bar"></span>
            <span class="icon-bar"></span>
            <span class="icon-bar"></span>
          </button>
          <img alt="Bliss Carpet Cleaning logo" class="logo" src="img/orange-cleaner.png" />
          <a class="navbar-brand" href="#">Bliss Carpet Cleaning</a>
          <a class="navbar-contact" href="tel:2817097708">281 709 7708</a>
        </div>
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
    <div class="container tab-content" id="begin-content">
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
                    <li data-target="#hero__carousel" data-slide-to="3"></li>
                    <li data-target="#hero__carousel" data-slide-to="4"></li>
                    <li data-target="#hero__carousel" data-slide-to="5"></li>
                  </ol>
                  <div class="carousel-inner" role="listbox">
                    <div class="carousel-item item active">
                      <img src="img/family-operated.png" alt="Family owned and operated since 2007" />
                      <h1>Family owned and operated</h1>
                      <h2>Serving the Houston Area since 2007</h2>
                    </div> <!-- END .carousel-item .item :nth-child(1) -->
                    <div class="carousel-item item">
                      <img src="img/wood-before-after.png" alt="Wood floor cleaning" />
                      <h1>More than carpet cleaning&hellip;</h1>
                      <h2>Wood floor cleaning</h2>
                    </div> <!-- END .carousel-item .item :nth-child(3) -->
                    <div class="carousel-item item">
                      <img src="img/tile-before-after.png" alt="Tile and grout cleaning" />
                      <h1>More than carpet cleaning&hellip;</h1>
                      <h2>Tile and grout cleaning</h2>
                    </div> <!-- END .carousel-item .item :nth-child(4) -->
                    <div class="carousel-item item">
                      <img src="img/upholstery-before-after.png" alt="Upholstery cleaning" />
                      <h1>More than carpet cleaning&hellip;</h1>
                      <h2>Upholstery cleaning</h2>
                    </div> <!-- END .carousel-item .item :nth-child(7) -->
                    <div class="carousel-item item">
                      <img src="img/repair-before-after.png" alt="Carpet repair" />
                      <h1>More than carpet cleaning&hellip;</h1>
                      <h2>Carpet repair and dying</h2>
                    </div> <!-- END .carousel-item .item :nth-child(2) -->
                    <div class="carousel-item item">
                      <img src="img/duct-before-after.png" alt="Air duct cleaning" />
                      <h1>More than carpet cleaning&hellip;</h1>
                      <h2>Air duct cleaning</h2>
                    </div> <!-- END .carousel-item .item :nth-child(5) -->
                  </div> <!-- END .carousel-inner -->
                  <a class="left carousel-control" href="#hero__carousel" role="button" data-slide="prev">
                    <span class="icon-prev" aria-hidden="true"></span>
                    <span class="sr-only">Previous</span>
                  </a>
                  <a class="right carousel-control" href="#hero__carousel" role="button" data-slide="next">
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
                <li>Wood floor cleaning</li>
                <li>Tile and grout cleaning</li>
                <li>Upholstery cleaning</li>
                <li>Carpet repair</li>
                <li>Air duct cleaning</li>
              </ul>
            </div> <!-- END .well -->
          </div> <!-- END .col-sm-12 .col-md-6 .col-lg-6 .col-xl-12 -->
          <div class="col-sm-12 col-md-6 col-lg-6 col-xl-12">
            <div class="well">
              <h2>Quality Cleaning</h2>
              <h3>Hygienic</h3>
              <p>
                Our truck-mounted cleaning units kill germs with water heated to 230&deg;F. Thorough cleaning also
                reduces the amount of allergens in the air, whether in your carpet or your air ducts.
              </p>
              <h3>Protective</h3>
              <p>We offer Scotch Guard treatment to lengthen the life of your carpets.</p>
              <h3>Environmentally conscious</h3>
              <p>Our cleaning products are natural and bio-degradable.</p>
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
              <h2>Five Rooms &ndash; $99</h2>
              <p>
                Get five rooms cleaned for only $99! Take advantage of this special and get up to two hallways cleaned
                FREE.
              </p>
            </div> <!-- END .well -->
          </div> <!-- END .col-xs-12 .col-sm-12 .col-md-6 .col-lg-6 -->
          <div class="col-xs-12 col-sm-12 col-md-6 col-lg-6">
            <div class="well">
              <h2>VIP Customers</h2>
              <p>
                Ask us about how to become a VIP customer and get discounts on our services!
              </p>
            </div> <!-- END .well -->
          </div> <!-- END .col-xs-12 .col-sm-12 .col-md-6 .col-lg-6 -->
        </div> <!-- END .row -->
        <div class="row">
          <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
            <div class="well">
              <h2>Standard Carpet Cleaning Rates</h2>
              <h3>$25 per room</h3>
              <p>
                We'll clean your rooms for $25 each. Minimum of two rooms per visit. Larger rooms may incur an
                additional charge.
              </p>
              <h3>Hallways</h3>
              <h3>Staircases</h3>
            </div> <!-- END .well -->
          </div> <!-- END .col-xs-12 .col-sm-12 .col-md-12 .col-lg-12 -->
        </div> <!-- END .row -->
        <div class="row">
          <div class="col-xs-12 col-sm-12 col-md-6 col-lg-6">
            <div class="well">
              <h2>Wood Floor Cleaning Rates</h2>
              <h3>79&cent; / sq. ft.</h3>
              <p>
                We'll restore the beauty of your hardwood floors for only $0.79 per square foot.
              </p>
            </div> <!-- END .well -->
          </div> <!-- END .col-xs-12 .col-sm-12 .col-md-6 .col-lg-6 -->
          <div class="col-xs-12 col-sm-12 col-md-6 col-lg-6">
            <div class="well">
              <h2>Tile &amp; Grout Cleaning Rates</h2>
              <h3>79&cent; / sq. ft.</h3>
              <p>
                Save your hips and your knees. We'll clean your tile floors for $0.79 per square foot.
              </p>
            </div> <!-- END .well -->
          </div> <!-- END .col-xs-12 .col-sm-12 .col-md-6 .col-lg-6 -->
        </div> <!-- END .row -->
        <div class="row">
          <div class="col-xs-12 col-sm-12 col-md-6 col-lg-6">
            <div class="well">
              <h2>Carpet Repair</h2>
              <h3>$50 / patch</h3>
              <p>
                Do you have stains that won't come out? Maybe your pet thinks your carpet is rich in dietary fiber?
              </p>
            </div> <!-- END .well -->
          </div> <!-- END .col-xs-12 .col-sm-12 .col-md-6 .col-lg-6 -->
          <div class="col-xs-12 col-sm-12 col-md-6 col-lg-6">
            <div class="well">
              <h2>Carpet Dying</h2>
              <h3>$100 / room</h3>
              <p>
                Does your room need a change of mood without a complete repaint or new carpet? A carpet dye may be the
                answer.
              </p>
            </div> <!-- END .well -->
          </div> <!-- END .col-xs-12 .col-sm-12 .col-md-6 .col-lg-6 -->
        </div> <!-- END .row -->
        <div class="row">
          <div class="col-xs-12 col-sm-12 col-md-6 col-lg-6">
            <div class="well">
              <h2>Upholstery Cleaning</h2>
              <h3>Starting at $79</h3>
              <p>
                Did your upholstery have a little too much to drink last weekend? We'll be happy to come by and help it
                come clean.
              </p>
            </div> <!-- END .well -->
          </div> <!-- END .col-xs-12 .col-sm-12 .col-md-6 .col-lg-6 -->
          <div class="col-xs-12 col-sm-12 col-md-6 col-lg-6">
            <div class="well">
              <h2>Air Duct Cleaning</h2>
              <h3>$25 / vent</h3>
              <p>
                Are your allergies acting up? Maybe there's a smell in the air, but it's not coming from a specific
                room. It might be time for you to get the air ducts cleaned.
              </p>
            </div> <!-- END .well -->
          </div> <!-- END .col-xs-12 .col-sm-12 .col-md-6 .col-lg-6 -->
        </div> <!-- END .row -->
      </div> <!-- END #content-specials -->
      <div class="container-fluid tab-pane <?= $tryPost && !$doPost ? 'active' : '' ?>" id="content-contact">
        <div class="row">
          <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
            <h2>Get a Quote</h2>
            <?php
              if (count($errorMessages) > 0) {
                ?>
                  <div class="alert alert-danger" role="alert"><ul>
                <?php
                foreach($errorMessages as $fieldName => $errorMessage) {
                  ?>
                    <li for="<?= $fieldName ?>">
                      <span class="glyphicon glyphicon-remove"></span>
                      <?= htmlspecialchars($errorMessage) ?>
                    </li>
                  <?php
                }
                ?>
                  </ul></div>
                <?php
              }
            ?>
            <form method="post">
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
                    <input type="text" class="form-control" id="customer_name" maxlength="1024" name="customer_name"
                           <?php
                            if (isset($_POST["customer_name"]))
                              echo "value=\"" . htmlspecialchars($_POST["customer_name"]) . "\"";
                           ?> />
                    <?php
                    if ($isInvalid && ($errorMessages["customer_name"] != "")) {
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
                    <input type="email" class="form-control" id="customer_email" maxlength="255" name="customer_email" 
                           <?php
                            if (isset($_POST["customer_email"]))
                              echo "value=\"" . htmlspecialchars($_POST["customer_email"]) . "\"";
                           ?> />
                    <?php
                    if ($isInvalid && ($errorMessages["customer_email"] != "")) {
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
                    if ($isInvalid && ($errorMessages["customer_phone"] != "")) {
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
                    <label class="control-label">Preferred method of contact</label>
                    <div class="form-check <?= $isInvalid ? 'has-error' : '' ?>">
                      <label class="control-label form-check-label" for="customer_prefer_email">
                        <input type="radio" id="customer_prefer_email" name="customer_prefer" value="email"
                               <?php 
                                if (isset($_POST["customer_prefer"]) && ($_POST["customer_prefer"] == "email"))
                                  echo 'checked="checked"';
                               ?> />
                        Email
                      </label>
                    </div> <!-- END .form-check -->
                    <div class="form-check <?= $isInvalid ? 'has-error' : '' ?>">
                      <label class="control-label form-check-label" for="customer_prefer_phone">
                        <input type="radio" id="customer_prefer_phone" name="customer_prefer" value="phone"
                               <?php
                                if (isset($_POST["customer_prefer"]) && ($_POST["customer_prefer"] == "phone"))
                                  echo 'checked="checked"';
                               ?> />
                        Phone
                      </label>
                    </div> <!-- END .form-check -->
                    <?php
                    if ($isInvalid && ($errorMessages["customer_prefer"] != "")) {
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
                <legend>Where does the work need to be done?</legend>
                <div class="well">
                  <?php $isInvalid = isset($errorMessages["address_street1"]); ?>
                  <div class="form-group <?= $isInvalid ? 'has-error has-feedback' : '' ?>">
                    <label class="control-label" for="address_street1">
                      <span class="glyphicon glyphicon-asterisk"></span>
                      Street (line 1)
                    </label>
                    <input type="text" class="form-control" id="address_street1" maxlength="255" name="address_street1"
                           <?php
                            if (isset($_POST["address_street1"]))
                              echo 'value="' . htmlspecialchars($_POST['address_street1']) . '"';
                           ?> />
                    <?php
                    if ($isInvalid && ($errorMessages["address_street1"] != "")) {
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
                    <input type="text" class="form-control" id="address_street2" maxlength="255" name="address_street2"
                           <?php
                            if (isset($_POST["address_street2"]))
                              echo 'value="' . htmlspecialchars($_POST['address_street2']) . '"';
                           ?>
                           />
                    <?php
                    if ($isInvalid && ($errorMessages["address_street2"] != "")) {
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
                    <label class="control-label" for="address_city">
                      <span class="glyphicon glyphicon-asterisk"></span>
                      City
                    </label>
                    <div class="input-group">
                      <input type="text" class="form-control" id="address_city" maxlength="255" name="address_city"
                             <?php
                              if (isset($_POST["address_city"]))
                                echo 'value="' . htmlspecialchars($_POST["address_city"]) . '"';
                             ?>
                             />
                      <div class="input-group-addon">, Texas</div>
                    </div>
                    <?php
                    if ($isInvalid && ($errorMessages["address_city"] != "")) {
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
                    if ($isInvalid && ($errorMessages["address_zip"] != "")) {
                      ?>
                        <div class="form-control-feedback">
                          <span class="glyphicon glyphicon-remove"></span>
                          <?= htmlspecialchars($errorMessages["address_zip"]) ?>
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
                    foreach ($fieldsServices as $fieldGroup => $infoGroup) {
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
                              foreach($infoGroup["fields"] as $nameField => $infoField) {
                                $isInvalid = isset($errorMessages[$nameField]);
                                ?>
                                <div class="form-group <?= $isInvalid ? 'has-error has-feedback' : '' ?>">
                                  <?php
                                  switch ($infoField["type"]) {
                                    case "checkbox":
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
                                      ?>
                                        <label class="control-label" for="<?= $nameField ?>">
                                          <?= htmlspecialchars($infoField["label"]) ?>
                                        </label>
                                        <input class="form-control" type="<?= $infoField["type"] ?>"
                                               id="<?= $nameField ?>" name="<?= $nameField ?>"
                                               <?php
                                                if (isset($_POST[$nameField]) && ($_POST[$nameField] != ""))
                                                  echo 'value="' . htmlspecialchars($_POST[$nameField]) . '"';
                                               ?>
                                               />
                                      <?php
                                      break;
                                  }
                                  if ($isInvalid && ($errorMessages[$nameField] != "")) {
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
                  <button type="submit" class="btn btn-primary" name="bcc_quote_submit" value="1">Get a Quote</button>
                </div> <!-- END .well -->
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
                <?php ob_start() ?>
                <fieldset class="form-group">
                  <h3>Contact Info and Location</h3>
                  <div class="row">
                    <div class="col-xs-4 col-sm-3 col-md-3 col-lg-3">Name</div>
                    <div class="col-xs-8 col-sm-9 col-md-9 col-lg-9"><?= htmlspecialchars($_POST["customer_name"]) ?></div>
                  </div> <!-- END .row -->
                  <div class="row">
                    <div class="col-xs-4 col-sm-3 col-md-3 col-lg-3">Email</div>
                    <div class="col-xs-8 col-sm-9 col-md-9 col-lg-9"><?= htmlspecialchars($_POST["customer_email"]) ?></div>
                  </div> <!-- END .row -->
                  <div class="row">
                    <div class="col-xs-4 col-sm-3 col-md-3 col-lg-3">Phone</div>
                    <div class="col-xs-8 col-sm-9 col-md-9 col-lg-9"><?= htmlspecialchars($_POST["customer_phone"]) ?></div>
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
                      <?= $_POST["address_street2"] != '' ? htmlspecialchars($_POST["address_street2"]) . '<br />' : '' ?>
                      <?= htmlspecialchars($_POST["address_city"]) ?>, TX <?= htmlspecialchars($_POST["address_zip"]) ?>
                    </div>
                  </div> <!-- END .row -->
                </fieldset>
                <?php
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
                      $haveExtra = isset($_POST["carpet_repair_have_extra"]) && ($_POST["carpet_repair_have_extra"] == 1);
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
                $emailBody = ob_get_clean();
                $email = array(
                  "headers" => array(
                    "From" => $emailDefault["from"],
                    "Reply-To" => $emailDefault["replyTo"],
                    "MIME-Version" => "1.0",
                    "Content-Type" => "text/html; charset=UTF-8"
                  ),
                  "message" => $emailBody,
                  "subject" => $emailDefault["subject"],
                  "to" => $emailDefault["to"]
                );
                try {
                  $smtp = Mail::factory(
                    "smtp",
                    array(
                      "auth" => false
                    )
                  );
                  $mail = $smtp->send($email["to"], $email["headers"], $email["message"]);
                } catch (Exception $e) {
                  var_dump($e);
                }
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
    <script type="text/javascript" src="js/jquery.min.js"></script>
    <script type="text/javascript" src="js/bootstrap.min.js"></script>
    <script type="text/javascript" src="js/bcc.base.js"></script>
  </body>
</html>