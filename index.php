<?php
//this line makes PHP behave in a more strict way
declare(strict_types=1);


//we are going to use session variables so we need to enable sessions
session_set_cookie_params(0);
session_start();

$errors = array();


if (!isset($_COOKIE['totalValue'])) {
    setcookie("totalValue", "0");
}

if (!isset($_GET['food'])) {
    $index = 1;
} else {
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
    $Cola = new Product ('Cola', 2),
    $Fanta = new Product ('Fanta', 2),
    $Sprite = new Product ('Sprite', 2),
    $tea = new Product ('Ice-tea', 3)
];
$products[1] = [
    $Ham = new Product ('Club Ham', 3.20),
    $Cheese = new Product ('Club Cheese', 3),
    $CheeseHam = new Product ('Club Cheese & Ham', 4),
    $Chicken = new Product ('Club Chicken', 4),
    $Salmon = new Product ('Club Salmon', 5)
];
$products[2] = array_merge($products[0],$products[1]);


class  Product
{
    protected string $name;
    protected float $price;
    protected int $quantity = 0;
    public static float $totalPrice = 0;

    public function __construct(string $name, float $price)
    {
        $this->name = $name;
        $this->price = $price;
    }

    public function setQuantity(int $quantity): void
    {
        $this->quantity = $quantity;
        self:: $totalPrice += $this->sumOfFood();
    }

    function sumOfFood()
    {
        return $this->quantity * $this->price;
    }

    public function getName(): string
    {
        return $this->name;
    }


    public function getPrice(): float
    {
        return $this->price;
    }


    public function getQuantity(): int
    {
        return $this->quantity;
    }

    public function toList()
    {
        return $this->name . ":  " . $this->quantity;

    }


}
function test_input($data)
{
    $data = trim($data);
    $data = stripslashes($data);
    $data = htmlspecialchars($data);
    return $data;
}
if (isset($_POST['products']))
{

    $email = test_input($_POST["email"]);
    $street = test_input($_POST["street"]);
    $streetnumber = test_input($_POST["streetnumber"]);
    $city = test_input($_POST["city"]);
    $zipcode = test_input($_POST["zipcode"]);
    if (empty($_POST["email"]))
    {
    global $errors;
    $errors[] = "email required";
    }else{
    $email = test_input($_POST["email"]);
    // check if e-mail address is well-formed
        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            global $errors;
            $errors[] = "invalid email format";
        } else {
            $_SESSION['person']['email'] = $email;
        }
    }

    if (!preg_match("/^[a-zA-Z-' ]*$/", $street)) {
        $errors[] = "invalid street name";
    } else {
        $_SESSION['person']['street'] = $street;
    }

    if (!preg_match("/^[a-zA-Z-' ]*$/", $city)) {
        $errors[] = "invalid city name";
    } else {
        $_SESSION['person']['city'] = $city;
    }

    if (!preg_match("/^[1-9][0-9]{1,4}$/", $streetnumber)) {
        $errors[] = "invalid street number";
    } else {
        $_SESSION['person']['streetnumber'] = $streetnumber;
    }

    if (!preg_match("/^[1-9][0-9]{0,3}$/", $zipcode)) {
        $errors[] = "invalid zip code";
    } else {
        $_SESSION['person']['zipcode'] = $zipcode;
    }

    $listProducts = array();
    $price = 0;
    for ($i = 0; count($products[$index]) > $i; $i++) {
        $obj = $products[$index][$i];

        if ($_POST['products'][$index][$i] > 0) {
            $obj->setQuantity(intval($_POST['products'][$index][$i]));

            $price += $obj->sumOfFood();
            $listProducts[] = $obj->toList();
        }

    }
    $newtotal = $_COOKIE['totalValue'] + Product::$totalPrice;

    setcookie("totalValue", "$newtotal");

    $listProducts = implode("</br>", $listProducts);


    $delivery = "+2 hours";
    $delcost = "";
        if (isset($_POST['express_delivery'])) {
            $delivery = "+45 minutes";
            $delcost = "plus 5 € for express delivery";

            $finalprice = $_POST['express_delivery'] + $price;
        }
    $d = strtotime($delivery);
    $delivery = date("H:i", $d);
}


function valid()
{
    if (isset($_POST)) {
        global $errors;
        global $index;
        if (count($errors) > 0) {
            $GLOBALS['classValid'] = 'alert-danger';
            return false;
        } elseif (isset($_POST['products']) && !empty($_POST['products'][$index])) {


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
    if (!empty($_POST)) {

        global $errors;
        global $classValid;
        global $index;
        global $message;
        if (valid() === "undefined") {
            if (empty($_POST['products'][$index])) {
                echo '<div class="alert ' . $classValid . '" role="alert"> Fill in your orders </div>';
            }
        } elseif (valid() == true) {

            echo '<div class="alert ' . $classValid . '" role="alert"> Order successfully sent </br> ' . $message . '</div>';

        } else {
            $errorList = implode(", ", $errors);
            echo '<div class="alert ' . $classValid . '" role="alert">' . $errorList . '</div>';
        }
    }
}

if (valid() !== "undefined" && valid() == true) {
    $message = "</br>" .
        'Things you ordered:' . "</br>" .
        $listProducts . "</br></br>" .
        'They will be delivered at the following address:' . "</br>" .
        $_SESSION['person']['zipcode'] . " " .
        $_SESSION['person']['city'] . ", " .
        $_SESSION['person']['street'] . " street " .
        $_SESSION['person']['streetnumber'] . "</br>" .

        'today around ' . "<strong>" . $delivery . " </strong></br></br>" .
        'the sum is: ' . "<strong>" . Product::$totalPrice . "€" . $delcost . "</strong>";
}
require 'form-view.php';

