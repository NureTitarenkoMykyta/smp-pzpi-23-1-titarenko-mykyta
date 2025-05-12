<?php
class Products{
    private $products;
    private $selectedProducts;
    
    public function __construct(){
        $this->products = [
            new Product("Молоко пастеризоване", 12), 
            new Product("Хліб чорний", 9),
            new Product("Сир білий",  21),
            new Product("Сметана 20%", 25),
            new Product("Кефір 1%", 19),
            new Product("Вода газована", 18),
            new Product("Печиво \"Весна\"", 14)];
            $this->selectedProducts = [];
    }

    public function &getProducts(){
        return $this->products;
    }

    public function &getSelectedProducts(){
        return $this->selectedProducts;
    }

    public function getTotalPrice(){
        $totalPrice = 0;
        foreach($this->selectedProducts as $selectedProduct){
            $totalPrice += $selectedProduct->getPrice() * $selectedProduct->getQuantity();
        }
        return $totalPrice;
    }
}
// Товар
class Product{
    private $name;
    private $price;

    public function __construct($name, $price){
        $this->name = $name;
        $this->price = $price;
    }

    public function getName(){
        return $this->name;
    }

    public function getPrice(){
        return $this->price;
    }
}
// Обраний товар (товар у кошику)
class SelectedProduct extends Product{
    private $quanity;
    private $index;
    public function __construct($product, $quanity, $index) {
        parent::__construct($product->getName(), $product->getPrice());
        $this->quanity = $quanity;
        $this->index = $index;
    }

    public function getQuantity(){
        return $this->quanity;
    }

    public function setQuantity($quantity){
        $this->quanity = $quantity;
    }

    public function getIndex(){
        return $this->index;
    }
}