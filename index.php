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


    public function toList()
    {
        return $this->name . ":  " . $this->quantity;

    }
}

class Person {
    private string $email;
    private string $zip;
    private string $city;
    private string $street;
    private string $streetnumber;
    static array $errors = array();

    public function __construct(string $email, string $zip, string $city, string $street, string $streetnumber)
    {
        $this->email = $this-> test_input($email);
        $this->zip = $this-> test_input($zip);
        $this->city = $this-> test_input($city);
        $this->street= $this-> test_input($street);
        $this->streetnumber= $this-> test_input($streetnumber);
        $this->testHub();

    }

    public function getEmail(): string
    {
        return $this->email;
    }

     public function getZip(): string
    {
        return $this->zip;
    }

       public function getCity(): string
    {
        return $this->city;
    }

     public function getStreet(): string
    {
        return $this->street;
    }


    public function getStreetnumber(): string
    {
        return $this->streetnumber;
    }

    function testmail(){
        if (!filter_var($this->email, FILTER_VALIDATE_EMAIL)) {

            self::$errors[] = "invalid email format";
        }
    }
    function teststreet(){
        if (!preg_match("/^[a-zA-Z-' ]*$/", $this->street)) {
            self::$errors[] = "invalid street name";
        }
    }
    function testcity(){
        if (!preg_match("/^[a-zA-Z-' ]*$/", $this->city)) {
            self::$errors[] = "invalid city name";
        }
    }function teststreetnum(){
        if (!preg_match("/^[1-9][0-9]{1,4}$/", $this->streetnumber))
        {
            self::$errors[] = "invalid street number";
        }
    }
    function testZip(){
        if (!preg_match("/^[1-9][0-9]{0,3}$/", $this->zip)) {
            self::$errors[] = "invalid zip code";
        }
    }
    function testHub(){
        $this->testZip();
        $this->teststreetnum();
        $this->teststreet();
        $this->testmail();
        $this->testcity();
        if (empty(self::$errors)){
            $_SESSION['person']= $this;
        }
    }
    function test_input($data)
    {
        $data = trim($data);
        $data = stripslashes($data);
        $data = htmlspecialchars($data);
        return $data;
    }
    function listErrors(){
        return implode(", ", self::$errors);
    }
    function showAdress(){
        return 'They will be delivered at the following address:' . "</br>" .
            $this->zip . " " . $this->city . ", " . $this->street . " street " . $this->streetnumber . "</br>";
    }
}
if (isset($_POST['products']))
{
    whatIsHappening();
    $person = new Person( $_POST['email'], $_POST['zipcode'], $_POST['city'], $_POST['street'], $_POST['streetnumber']);

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
        if (count(Person::$errors) > 0) {
            $GLOBALS['classValid'] = 'alert-danger';
            return false;
        } elseif (Product::$totalPrice>0) {


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

        global $person;
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

            echo '<div class="alert ' . $classValid . '" role="alert">' . $person ->listErrors(). '</div>';
        }
    }
}

if (valid() !== "undefined" && valid() == true) {
    $message = "</br>" .
        'Things you ordered:' . "</br>" .
        $listProducts . "</br></br>" .
      $person->showAdress() .

        'today around ' . "<strong>" . $delivery . " </strong></br></br>" .
        'the sum is: ' . "<strong>" . Product::$totalPrice . "€" . $delcost . "</strong>";
}
require 'form-view.php';

