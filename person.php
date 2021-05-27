<?php
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
    function test_input($data) : string
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
};