<?php
//this line makes PHP behave in a more strict way
declare(strict_types=1);

//we are going to use session variables so we need to enable sessions
session_set_cookie_params(0);
session_start();
$errors = array();
$index = 1;
if (isset($_GET['food'])){
    $index = $_GET['food'];
}
function whatIsHappening()
{
    echo '<h2>$_GET</h2>';
    var_dump($_GET);
    echo '<h2>$_POST</h2>';
    var_dump($_POST);
    echo '<h2>$_COOKIE</h2>';
    var_dump($_COOKIE);
    echo '<h2>$_SESSION</h2>';
    var_dump($_SESSION);
}

//your products with their price.
$products[1] = [
    ['name' => 'Club Ham', 'price' => 3.20],
    ['name' => 'Club Cheese', 'price' => 3],
    ['name' => 'Club Cheese & Ham', 'price' => 4],
    ['name' => 'Club Chicken', 'price' => 4],
    ['name' => 'Club Salmon', 'price' => 5]
];

$products[0] = [
    ['name' => 'Cola', 'price' => 2],
    ['name' => 'Fanta', 'price' => 2],
    ['name' => 'Sprite', 'price' => 2],
    ['name' => 'Ice-tea', 'price' => 3],
];

$totalValue = 0;
function test_input($data)
{
    $data = trim($data);
    $data = stripslashes($data);
    $data = htmlspecialchars($data);
    return $data;
}


if (!empty($_POST)) {

    $email = test_input($_POST["email"]);
    $street = test_input($_POST["street"]);
    $streetnumber = test_input($_POST["streetnumber"]);
    $city = test_input($_POST["city"]);
    $zipcode = test_input($_POST["zipcode"]);
    if (empty($_POST["email"])) {
        global $errors;
        $errors[] = "email required";
    } else {
        $email = test_input($_POST["email"]);
        // check if e-mail address is well-formed
        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            global $errors;
            $errors[] = "invalid email format";
        }else{
            $_SESSION['email'] = $email;
        }
    }

    if (!preg_match("/^[a-zA-Z-' ]*$/", $street)) {
        $errors[] = "invalid street name";
    }else{
        $_SESSION['street'] = $street;
    }

    if (!preg_match("/^[a-zA-Z-' ]*$/", $city)) {
        $errors[] = "invalid city name";
    }else{
        $_SESSION['city'] = $city;
    }

    if (!preg_match("/^[1-9][0-9]{1,4}$/", $streetnumber)) {
        $errors[] = "invalid street number";
    }else{
        $_SESSION['streetnumber'] = $streetnumber;
    }

    if (!preg_match("/^[1-9][0-9]{0,3}$/", $zipcode)) {
        $errors[] = "invalid zip code";
    }else{
        $_SESSION['zipcode'] = $zipcode;
    }


}
function valid()
{
    global $errors;
    if (count($errors) > 0) {
        $GLOBALS['classValid'] = 'alert-danger';
        return false;
    } else {
        $GLOBALS['classValid'] = 'alert-success';
        return true;
    }
}
function showError()
{
    global $errors;
    global $classValid;
    if(valid()== 'undefined'){

    }elseif (valid()) {
        echo '<div class="alert ' . $classValid . '" role="alert"> Order successfully sent </div>';
    } else {
        $errorList = implode(" ,", $errors);
        echo '<div class="alert ' . $classValid . '" role="alert">' . $errorList . '</div>';
    }


}

whatIsHappening();

require 'form-view.php';

