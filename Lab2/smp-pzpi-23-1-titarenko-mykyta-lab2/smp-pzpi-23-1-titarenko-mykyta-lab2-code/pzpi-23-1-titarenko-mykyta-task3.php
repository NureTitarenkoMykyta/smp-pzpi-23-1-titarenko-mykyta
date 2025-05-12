<?php
include 'pzpi-23-1-titarenko-mykyta-task3-products.php';
// Магазин 
class GroceryStore{
    private $shopProducts;
    public const NAME = "НАЗВА";
    public const PRICE = "ЦІНА";
    public const AMOUNT = "КІЛЬКІСТЬ";
    public const COST = "ВАРТІСТЬ";
    public const TOTAL_PAYMENT = "РАЗОМ ДО CПЛАТИ: ";
    public const CART_EMPTY = "КОШИК ПОРОЖНІЙ";
    public const IN_CART = "У КОШИКУ:";
    private $selectedProducts;
    // Конструктор (ініціалізація масиву з продуктами)
    public function __construct(){
        $products = new Products();
        $this->shopProducts = $products->getProducts();
        $this->selectedProducts = $products->getSelectedProducts();
    }
    // Виведення назви магазину
    private function printShopName(){
        echo "
################################
# ПРОДОВОЛЬЧИЙ МАГАЗИН \"ВЕСНА\" #
################################
";
    }
    // Виведення дій користувача
    private function printPossibleActions(){
        echo "1 Вибрати товари
2 Отримати підсумковий рахунок
3 Налаштувати свій профіль
0 Вийти з програми
Введіть команду: ";
    }
    // Виведення заголовку для таблиці (загально, у кошику, у рахунку)
    private function printTitle($maxLengthForNumber, $maxLengthForName, $price, $quntity){
        if ($maxLengthForNumber != -1){
            echo "№" . $this->formattedString("№", $maxLengthForNumber);
        }
        echo self::NAME . $this->formattedString(self::NAME, $maxLengthForName);
        if ($price){
            echo self::PRICE;
        }
        if ($quntity){
            if ($price){
                echo "  ";
            }
            echo self::AMOUNT;
            if ($price){
                echo "  " . self::COST;
            }
        }
        echo "\n";
    }
    //Обчислення максимальної довжини назви товару
    private function maxLengthForName($products){
        $lengths = array_map(function ($product){
            return iconv_strlen($product->getName());
        }, $products);
        return ceil(max($lengths));
    }
    //Обчислення довжини номера (необов'язково)
    private function maxLengthForNumber($products){
        return strlen(strval(count($products)) );
    }
    //Виведення товару (загально, у кошику, у рахунку)
    private function printProduct($productIndex, $maxLengthForNumber, $name, $maxLengthForName, $price, $quantity = -1, $sum = -1){
        if ($productIndex != -1){
            echo $productIndex + 1 . $this->formattedString(strval($productIndex + 1), $maxLengthForNumber);
        } 
        echo $name . $this->formattedString($name, $maxLengthForName);
        if ($price != -1){
            echo $price;
        }
        if ($quantity != -1){
            if ($price != -1){
                echo $this->formattedString(strval($price), iconv_strlen(self::PRICE));
            }
            echo $quantity;
            if ($sum != -1){
                echo $this->formattedString(strval($quantity), iconv_strlen(self::AMOUNT)) . $sum;
            }
        }
    }
    //Виведення товарів (усіх, у кошику або в рахунку)
    private function printProducts($products, $cart, $totalBill){
        if ($cart == true && count($products) == 0) {
            echo self::CART_EMPTY . "\n";
        } else {
            if ($cart == true && !$totalBill){
                echo self::IN_CART . "\n";
            }
            $maxLengthForNumber = -1;
            $maxLengthForName = $this->maxLengthForName($products);
            if ($totalBill || !$cart){
                $maxLengthForNumber = $this->maxLengthForNumber($products);
            }
            $this->printTitle($maxLengthForNumber, $maxLengthForName, $totalBill || !$cart, $cart);
            $totalSum = 0;
            for($i = 0; $i < count($products); $i++){
                $productIndex = -1;
                $price = -1;
                $quantity = -1;
                $sum = -1;
                if ($totalBill || !$cart){
                    $productIndex = $i;
                    $price = $products[$i]->getPrice();
                    if ($totalBill){
                        $quantity = $products[$i]->getQuantity();
                        $sum = $products[$i]->getQuantity() * $products[$i]->getPrice();
                        $totalSum += $sum;
                    }
                } else {
                    $quantity = $products[$i]->getQuantity();
                }
                $this->printProduct($productIndex, 
                $maxLengthForNumber,
                $products[$i]->getName(),
                $maxLengthForName,
                $price,
                $quantity,
                $sum
                );
                if ($i + 1 < count($products)){
                    echo "\n";
                }
            }
            if ($totalBill){
                echo "\n" . self::TOTAL_PAYMENT . $totalSum . "\n";
            } else if (!$cart){
                echo $this->formattedString("", $maxLengthForNumber) . "\n-----------\n";
                echo "0" . $this->formattedString("0", $maxLengthForNumber);
                echo "ПОВЕРНУТИСЯ\nВиберіть товар: ";
            } else {
                echo "\n";
            }
        }
        if ($totalBill){
            echo "\n";
        }
    }
    // Обрання товару
    private function selectProduct(){
        while (true){
            $number = readline($this->printProducts($this->shopProducts, false, false));
            if ($number == 0){
                break;
            }
            if (count($this->shopProducts) < $number || $number < 0){
                echo "ПОМИЛКА! Ви вказали неправильний номер товару\n";
            } else {
                echo "Вибрано: " . $this->shopProducts[$number - 1]->getName() . "\n";
                $this->selectQuantity($number - 1);
                $this->printProducts($this->selectedProducts, true,false);
            }
            echo "\n";
        }
    }
    // Обрання кількості обраного товару
    private function selectQuantity($productIndex){
            $quantity = readline("Введіть кількість, штук: ");
            if (is_numeric($quantity) && $quantity < 100 && $quantity >= 0){
                $selectedProductIndex = $this->findProductFromCart($this->shopProducts[$productIndex]->getName());
                if ($selectedProductIndex != -1){
                    if ($quantity == 0){
                        unset($this->selectedProducts[$selectedProductIndex]);
                        $this->selectedProducts = array_values($this->selectedProducts);
                        echo "ВИДАЛЯЮ ТОВАР З КОШИКА\n";
                    } else {
                        $this->selectedProducts[$selectedProductIndex]->setQuantity($quantity);
                    }
                } else {
                    if ($quantity != 0){
                        array_push($this->selectedProducts, new SelectedProduct($this->shopProducts[$productIndex], $quantity));
                    }
                }
                return true;
            } else {
                echo "ПОМИЛКА! Ви вказали неправильну кількість товару\n";
                return false;
            }
    }
    // Перевірка наявності товару у кошику (перевірка за назвою) 
    private function findProductFromCart($productName){
        for ($selectedProductIndex = 0; $selectedProductIndex < count($this->selectedProducts); $selectedProductIndex++){
            if ($this->selectedProducts[$selectedProductIndex]->getName() == $productName){
                return $selectedProductIndex;
            }
        }
        return -1;
    }
    // Додавання вирівнювання до рядка
    private function formattedString($data, $maxLength){
        $secondSpace = "";
        return str_pad($secondSpace, ceil($maxLength - iconv_strlen($data)) + 2);
    }
    // Введення ім'я і віку користувача
    private function register(){
        while (true) {
            $name = readline("Ваше ім'я: ");
            if ($name == "") {
                continue;
            } else if (preg_match("/^[\p{L}\'\- ]+$/u", $name)){
                break;
            } else {
                echo "ПОМИЛКА! Імʼя може містити лише літери, апостроф «'», дефіс «-», пробіл\n\n";
            }
        }
        while(true) {
            $age = readline("Ваш вік: ");
            if ($age < 7 || $age > 150){
                echo "ПОМИЛКА! Користувач повинен мати вік від 7 та до 150 років\n\n";
            } else {
                break;
            }
        }
        echo "\n";
    }
    // Зчитування події
    private function readAction(){
        $number = readline($this->printPossibleActions());
        switch ($number) {
            case 1:
                $this->selectProduct();
                break;
            case 2:
                $this->printProducts($this->selectedProducts, true,true);
                break;
            case 3:
                $this->register();
                break;
            case 0:
                return false;
            default:
                echo "ПОМИЛКА! Введіть правильну команду";
            }
        return true;
    }
    // Запуск програми
    public function run(){
        $this->printShopName();
        while ($this->readAction());
    }
}

// Початок програми
$shope = new GroceryStore();
$shope->run();