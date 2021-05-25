<?php
//this line makes PHP behave in a more strict way
declare(strict_types=1);


//we are going to use session variables so we need to enable sessions
session_set_cookie_params(0);
session_start();

$errors = array();

//$index = 1 ;
if (!isset($_COOKIE['totalValue']))
{
    setcookie("totalValue",  "0");
}

if (!isset($_GET['food'])) {
    $index = 1;
}else {
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


$products[0] = [
    ['name' => 'Cola', 'price' => 2],
    ['name' => 'Fanta', 'price' => 2],
    ['name' => 'Sprite', 'price' => 2],
    ['name' => 'Ice-tea', 'price' => 3],
];
$products[1] = [
    ['name' => 'Club Ham', 'price' => 3.20],
    ['name' => 'Club Cheese', 'price' => 3],
    ['name' => 'Club Cheese & Ham', 'price' => 4],
    ['name' => 'Club Chicken', 'price' => 4],
    ['name' => 'Club Salmon', 'price' => 5]
];


function test_input($data)
{
    $data = trim($data);
    $data = stripslashes($data);
    $data = htmlspecialchars($data);
    return $data;
}





//if (!isset($_SESSION['order'])) {
//    $_SESSION['order'] = array();
//}


if (isset($_POST['products'])) {

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
            $_SESSION['person']['email'] = $email;
        }
    }

    if (!preg_match("/^[a-zA-Z-' ]*$/", $street)) {
        $errors[] = "invalid street name";
    }else{
        $_SESSION['person']['street'] = $street;
    }

    if (!preg_match("/^[a-zA-Z-' ]*$/", $city)) {
        $errors[] = "invalid city name";
    }else{
        $_SESSION['person']['city'] = $city;
    }

    if (!preg_match("/^[1-9][0-9]{1,4}$/", $streetnumber)) {
        $errors[] = "invalid street number";
    }else{
        $_SESSION['person']['streetnumber'] = $streetnumber;
    }

    if (!preg_match("/^[1-9][0-9]{0,3}$/", $zipcode)) {
        $errors[] = "invalid zip code";
    }else{
        $_SESSION['person']['zipcode'] = $zipcode;
    }


    $price = 0;
    for ($i = 0; count($products[$index]) > $i; $i++) {

        if (isset($_POST['products'][$index][$i])) {
            $price= $price + $products[$index][$i]['price'];
            echo $products[$index][$i]['name'];

        }
    };


    $newtotal = $_COOKIE['totalValue'] + $price;
    setcookie("totalValue", "$newtotal" );

//    foreach ($products[$index] as $product){
//        $nameToSearch = $product['name'];
//        $arrayToLook =array_column($_SESSION['order'], 'name');
//        $found =array_search($nameToSearch, $arrayToLook);
//        if (!$found == false) {
//            echo $arr[$found]['name'];
//        }
//    }




}



function valid()
{if (isset($_POST)) {
    global $errors;
    if (count($errors) > 0) {
        $GLOBALS['classValid'] = 'alert-danger';
        return false;
    } elseif (isset($_POST['products'])) {

        $GLOBALS['classValid'] = 'alert-success';
        return true;
    } else {
        $GLOBALS['classValid'] = 'alert-warning';
        return "undefined";
    }
}
}

function showError()
{
    var_dump(valid());
    global $errors;
    global $classValid;
    if(valid() === "undefined"){
        if (isset($_POST)){
            echo '<div class="alert ' . $classValid . '" role="alert"> Fill in your orders </div>';
        }
    }elseif (valid()) {

        echo '<div class="alert ' . $classValid . '" role="alert"> Order successfully sent </div>';

    } else {
        $errorList = implode(", ", $errors);
        echo '<div class="alert ' . $classValid . '" role="alert">' . $errorList . '</div>';
    }


}



whatIsHappening();

require 'form-view.php';
//if ( valid()){
//    $_SESSION['order'] = array();
//}
