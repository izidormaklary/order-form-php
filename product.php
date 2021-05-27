<?php
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
};